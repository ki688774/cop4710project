<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;

    if (!$currentUser || !$eventID) {
        returnError("All fields must be filled.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;

    // Check if the user can create an event.
    try {
        $stmt = $conn->prepare("EXISTS (SELECT * FROM universities U WHERE U.super_admin_id=?) 
        OR EXISTS (SELECT * FROM rsos R WHERE R.admin_id=? AND R.active=(1))");
        $stmt->bind_param("i", $eventID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }
    

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "Event deleted successfully."}';
    returnObject($result);
?>