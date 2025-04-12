<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;

    if (!$currentUser) {
        returnError("All fields must be filled.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Check if the user can create an event.
    try {
        $stmt = $conn->prepare("SELECT * FROM universities U WHERE U.super_admin_id=?");
        $stmt->bind_param("i", $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    if ($stmt->get_result()->fetch_assoc()) {
        $result = '{"result": "You can create events."}';
        returnObject($result);
        return;
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM rsos R WHERE R.admin_id=? AND NOT R.active=0");
        $stmt->bind_param("i", $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    if ($stmt->get_result()->fetch_assoc()) {
        $result = '{"result": "You can create events."}';
        returnObject($result);
        return;
    }



    // Return unsuccessful result
    returnError("You cannot create events.");
?>