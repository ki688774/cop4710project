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



    // Check if the rating exists.
    try {
        $stmt = $conn->prepare("SELECT * FROM ratings WHERE uid=? AND event_id=?");
        $stmt->bind_param("ii", $currentUser, $eventID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    if (!$stmt->get_result()->fetch_assoc()) {
        returnError("Rating not found.");
        return;
    }



    // Delete the rating.
    try {
        $stmt = $conn->prepare("DELETE FROM ratings WHERE uid=? AND event_id=?");
        $stmt->bind_param("ii", $currentUser, $eventID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "Rating deleted successfully."}';
    returnObject($result);
    return;
?>