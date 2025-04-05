<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

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



    // Check if the user has already made a comment.
    $stmt = $conn->prepare("SELECT * FROM comments WHERE uid=? AND event_id=?");
    $stmt->bind_param("ii", $currentUser, $eventID);

    if (!attemptExecute($stmt, $conn))
        return;

    if (!$stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("You have not already left a comment.");
        return;
    }



    // Delete the comment.
    $stmt = $conn->prepare("DELETE FROM comments WHERE uid=? AND event_id=?");
    $stmt->bind_param("ii", $currentUser, $eventID);

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "Comment deleted successfully."}';
    returnObject($result);
    return;
?>