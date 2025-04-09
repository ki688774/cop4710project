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


    
    // Check if the user is the admin of the RSO.
    try {
        $stmt = $conn->prepare("SELECT * FROM rsos WHERE rso_id=? AND admin_id=?");
        $stmt->bind_param("ii", $rsoID, $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    if ($stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("You cannot leave an RSO as the admin.", $stmt, $conn);
        return;
    }

    // Check if the user is a member of the RSO.
    try {
        $stmt = $conn->prepare("SELECT * FROM rso_joins WHERE rso_id=? AND uid=?");
        $stmt->bind_param("ii", $rsoID, $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    if (!$stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("You are already not a member of the RSO.", $stmt, $conn);
        return;
    }

    // Leave the RSO.
    try {
        $stmt = $conn->prepare("DELETE FROM rso_joins WHERE rso_id=? AND uid=?");
        $stmt->bind_param("ii", $rsoID, $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "RSO left successfully."}';
    returnObject($result);
    return;
?>