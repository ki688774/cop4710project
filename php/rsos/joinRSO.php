<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $rsoID = $inData["rso_id"] ?? null;
    $currentUser = $inData["current_user"] ?? null;

    if (!$currentUser || !$rsoID) {
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

    // Check if the user's university is the same as the RSO's.
    try {
        $stmt = $conn->prepare("SELECT * from rsos WHERE rso_id=? AND university_id=?");
        $stmt->bind_param("ii", $rsoID, $universityID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    if (!$stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("Could not find RSO.", $stmt, $conn);
        return;
    }

    // Join the RSO.
    try {
        $stmt = $conn->prepare("INSERT INTO rso_joins (uid, rso_id) VALUES (?,?)");
        $stmt->bind_param("ii", $currentUser, $rsoName);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "RSO joined successfully."}';
    returnObject($result);
    return;
?>