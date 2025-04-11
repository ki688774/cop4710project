<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';
    require __DIR__ . '/canEditEvent.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $eventID = $inData["event_id"] ?? null;

    if (!$currentUser || !$eventID) {
        returnError("All fields must be filled.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;

    // Check if the event can be edited.
    if (canEditEvent($currentUser, $eventID, $stmt, $conn)) {
        $result = '{"result": "Public event added successfully."}';
        returnObject($result);
    }

    return;
?>