<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $searchTerm = "%" . $inData["search"] . "%";
    $yourRSOsOnly = $inData["your_rsos_only"] ?? 0;

    if (!$currentUser) {
        returnError("All fields must be filled.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;


    
    // Get the current user's university.
    try {
        $stmt = $conn->prepare("SELECT university_id FROM users WHERE uid=?");
        $stmt->bind_param("i", $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $universityID = $stmt->get_result()->fetch_assoc()["university_id"];

    if (!$universityID) {
        returnErrorAndClose("Could not find current user.", $stmt, $conn);
        return;
    }

    // Search for RSOs of the user's university.
    try {
        $stmt = $conn->prepare("SELECT * from rsos WHERE university_id=? AND rso_name LIKE ? AND ((NOT 1 = ?) OR (admin_id=?))");
        $stmt->bind_param("isii", $universityID, $searchTerm, $yourRSOsOnly, $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    assembleJsonArrayFromQuery($stmt, $conn, $rows);



    // Return RSOs as result
    $result = '{"result": ' . $rows . '}';
    returnObject($result);
    $stmt->close();
    $conn->close();
    return;
?>