<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $commentID = $inData["comment_id"] ?? null;

    if (!$currentUser || !$commentID) {
        returnError("All fields must be filled.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Check if that comment exists.
    try {
        $stmt = $conn->prepare("SELECT * FROM comments WHERE uid=? AND comment_id=?");
        $stmt->bind_param("ii", $currentUser, $commentID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    if (!$stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("Comment not found.");
        return;
    }



    // Delete the comment.
    try {
        $stmt = $conn->prepare("DELETE FROM comments WHERE comment_id=?");
        $stmt->bind_param("i", $commentID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "Comment deleted successfully."}';
    returnObject($result);
    return;
?>