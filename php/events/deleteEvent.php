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

    // Check if the event can be deleted.
    if (canEditEvent($currentUser, $eventID, $stmt, $conn))
        postCheckDeleteEvent($stmt, $conn);
    
    

    // Delete the event and its corresponding location.
    function postCheckDeleteEvent (&$stmt, &$conn) {
        global $eventID;
        $stmt = $conn->prepare("DELETE FROM events WHERE event_id=?");
        $stmt->bind_param("i", $eventID);

        if (!attemptExecute($stmt, $conn))
            return;



        // Return successful result
        $result = '{"result": "Event deleted successfully."}';
        returnObject($result);
    }
?>