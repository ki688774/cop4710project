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


    
    // Check if the RSO belongs to the user.
    try {
        $stmt = $conn->prepare("SELECT * FROM rsos WHERE rso_id=? AND admin_id=?");
        $stmt->bind_param("ii", $rsoID, $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    if (!$stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("RSO not found or does not belong to user.", $stmt, $conn);
        return;
    }

    // Delete the RSO.
    try {
        $stmt = $conn->prepare("DELETE FROM rsos WHERE rso_id=?");
        $stmt->bind_param("i", $rso_id);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "RSO deleted successfully."}';
    returnObject($result);
    return;
?>