<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $rsoID = $inData["rso_id"] ?? null;

    if (!$currentUser || !$rsoID) {
        returnError("All fields must be filled.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Check if the user can edit this RSO.
    try {
        $stmt = $conn->prepare("SELECT * FROM rsos R WHERE R.rso_id=? AND R.admin_id=?");
        $stmt->bind_param("ii", $rsoID, $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    if ($stmt->get_result()->fetch_assoc()) {
        $result = '{"result": "You can edit the RSO."}';
        returnObject($result);
        return;
    }


    // Return unsuccessful result
    returnError("You cannot edit this RSO.");
?>