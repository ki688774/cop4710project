<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $commentID = $inData["comment_id"] ?? null;
    $eventID = $inData["event_id"] ?? null;
    $text = $inData["text"] ?? null;

    if (!$currentUser || !$eventID || !$commentID || !$text) {
        returnError("All fields must be filled.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;


    // Check if the user has already made a comment with that ID.
    $stmt = $conn->prepare("SELECT * FROM comments WHERE uid=? AND event_id=? AND comment_id=?");
    $stmt->bind_param("iii", $currentUser, $eventID, $commentID);

    if (!attemptExecute($stmt, $conn))
        return;

    if (!$stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("Comment not found.");
        return;
    }



    // Update the comment.
    $stmt = $conn->prepare("UPDATE comments SET text=?, timestamp=? WHERE comment_id=?");
    $stmt->bind_param("ssi", $text, date("Y-m-d H:i:s"), $commentID);

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "Comment updated successfully."}';
    returnObject($result);
    return;
?>