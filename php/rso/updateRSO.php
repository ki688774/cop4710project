<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $rsoID = $inData["rso_id"] ?? null;
    $rsoName = $inData["rso_name"] ?? null;
    $adminID = $inData["admin_id"] ?? null;
    $currentUser = $inData["current_user"] ?? null;

    if (!$rsoID || !$adminID || !$currentUser || !$rsoName) {
        returnError("All fields must be filled.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Check if the target RSO belongs to the user.
    $stmt = $conn->prepare("SELECT * FROM rsos WHERE rso_id=? AND admin_id=?");
    $stmt->bind_param("ii", $rsoID, $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    $universityID = $stmt->get_result()->fetch_assoc()["university_id"];

    if (!$universityID) {
        returnErrorAndClose("RSO not found or is not owned by user.", $stmt, $conn);
        return;
    }

    // Check if the new super-admin actually exists
    $stmt = $conn->prepare("SELECT university_id FROM users WHERE uid=? AND university_id=?");
    $stmt->bind_param("ii", $adminID, $universityID);

    if (!attemptExecute($stmt, $conn))
        return;

    if (!$stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("New admin not found or belongs to another university.", $stmt, $conn);
        return;
    }

    // Update the RSO's information.
    $stmt = $conn->prepare("UPDATE rsos SET rso_name=?, admin_id=? WHERE rso_id=?");
    $stmt->bind_param("sii", $rsoName, $adminID, $rsoID);

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "RSO updated successfully."}';
    returnObject($result);
    return;
?>