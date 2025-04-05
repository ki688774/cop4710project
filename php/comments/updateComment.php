<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $eventID = $inData["event_id"] ?? null;
    $rating = $inData["rating"] ?? null;
    $text = $inData["text"] ?? null;

    if (!$currentUser || !$eventID || !$rating) {
        returnError("All fields must be filled.");
        return;
    }

    if ($rating < 1 || $rating > 5) {
        returnError("Rating must be from 1 to 5.");
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



    // Update the comment.
    $stmt = $conn->prepare("UPDATE comments SET text=?, rating=?, timestamp=? WHERE uid=? AND event_id=?");
    $stmt->bind_param("sisii", $text, $rating, date("Y-m-d H:i:s"), $currentUser, $eventID);

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "Comment updated successfully."}';
    returnObject($result);
    return;
?>