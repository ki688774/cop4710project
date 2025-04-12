<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $rsoID = $inData["rso_id"] ?? null;
    $rsoName = $inData["rso_name"] ?? null;
    $currentUser = $inData["current_user"] ?? null;

    if (!$rsoID || !$currentUser || !$rsoName) {
        returnError("All fields must be filled.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Check if the target RSO belongs to the user.
    try {
        $stmt = $conn->prepare("SELECT * FROM rsos WHERE rso_id=? AND admin_id=?");
        $stmt->bind_param("ii", $rsoID, $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $universityID = $stmt->get_result()->fetch_assoc()["university_id"];

    if (!$universityID) {
        returnErrorAndClose("RSO not found or is not owned by user.", $stmt, $conn);
        return;
    }

    // Update the RSO's information.
    try {
        $stmt = $conn->prepare("UPDATE rsos SET rso_name=? WHERE rso_id=?");
        $stmt->bind_param("si", $rsoName, $rsoID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "RSO updated successfully."}';
    returnObject($result);
    return;
?>