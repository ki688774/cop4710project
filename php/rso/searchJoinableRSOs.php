<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;

    if (!$currentUser || !$rsoID) {
        returnError("All fields must be filled.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;


    
    // Get the current user's university.
    $stmt = $conn->prepare("SELECT university_id FROM users WHERE uid=?");
    $stmt->bind_param("i", $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    $universityID = $stmt->get_result()->fetch_assoc()["university_id"];

    if (!$universityID) {
        returnErrorAndClose("Could not find current user.", $stmt, $conn);
        return;
    }

    // Search for RSOs of the user's university.
    $stmt = $conn->prepare("SELECT * from rsos WHERE university_id=?");
    $stmt->bind_param("i", $universityID);

    if (!attemptExecute($stmt, $conn))
        return;

    if (assembleJsonArrayFromQuery($stmt, $rows) == 0) {
        returnErrorAndClose("No joinable RSOs found.", $stmt, $conn);
        return;
    }



    // Return RSOs as result
    $result = '{"result": ' . $rows . '}';
    returnObject($result);
    $stmt->close();
    $conn->close();
    return;
?>