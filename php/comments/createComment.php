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



    // Find user's university (which must exist).
    $stmt = $conn->prepare("SELECT * FROM users WHERE uid=?");
    $stmt->bind_param("i", $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    $universityID = $stmt->get_result()->fetch_assoc()["university_id"];

    // Check if the user has access to this event.
    $stmt = $conn->prepare("SELECT * FROM events E WHERE event_id=? AND (
	    EXISTS (SELECT * FROM public_events P WHERE P.event_id=E.event_id) OR
	    EXISTS (SELECT * FROM private_events P WHERE P.event_id=E.event_id AND university_id=?) OR
	    EXISTS (SELECT * FROM rso_events R WHERE R.event_id=E.event_id AND EXISTS (SELECT * FROM rso_joins J WHERE R.rso_id=J.rso_id AND J.uid=?)))");
    $stmt->bind_param("iii", $eventID, $universityID, $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    if (!$stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("You do not have access to this event.");
        return;
    }



    // Add the comment.
    $stmt = $conn->prepare("INSERT INTO comments (uid, event_id, text, rating, timestamp) VALUES (?,?,?,?,?)");
    $stmt->bind_param("iisis", $currentUser, $eventID, $text, $rating, date("Y-m-d H:i:s"));

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "Comment added successfully."}';
    returnObject($result);
    return;
?>