<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $eventID = $inData["event_id"] ?? null;
    $rating = $inData["rating"] ?? null;

    if (!$currentUser || !$eventID || !$rating) {
        returnError("All fields must be filled.");
        return;
    }

    if ($rating < 1 || $rating > 5) {
        returnError("Rating must be between 1 and 5.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Find user's university (which must exist).
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE uid=?");
        $stmt->bind_param("i", $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $universityID = $stmt->get_result()->fetch_assoc()["university_id"];
    
    if (!$universityID) {
        returnError("User not found.");
        return;
    }

    // Check if the user has access to this event.
    try {
        $stmt = $conn->prepare("SELECT * FROM events E WHERE event_id=? AND (
	        EXISTS (SELECT * FROM public_events P WHERE P.event_id=E.event_id) OR
	        EXISTS (SELECT * FROM private_events P WHERE P.event_id=E.event_id AND university_id=?) OR
	        EXISTS (SELECT * FROM rso_events R WHERE R.event_id=E.event_id AND EXISTS (SELECT * FROM rso_joins J WHERE R.rso_id=J.rso_id AND J.uid=?)))");
        $stmt->bind_param("iii", $eventID, $universityID, $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    if (!$stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("You do not have access to this event.");
        return;
    }



    try {
        $stmt = $conn->prepare("SELECT * FROM ratings WHERE uid=? AND event_id=?");
        $stmt->bind_param("ii", $currentUser, $eventID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    if ($stmt->get_result()->fetch_assoc()) {
        // Edit the rating.
        try {
            $stmt = $conn->prepare("UPDATE ratings SET rating=? WHERE uid=? AND event_id=?");
            $stmt->bind_param("iii", $rating, $currentUser, $eventID);
        } catch (Exception $error){
            returnMYSQLErrorAndClose($stmt, $conn);
            return;
        }

        if (!attemptExecute($stmt, $conn))
            return;

    } else {
        // Add the rating.
        try {
            $stmt = $conn->prepare("INSERT INTO ratings (uid, event_id, rating) VALUES (?,?,?)");
            $stmt->bind_param("iii", $currentUser, $eventID, $rating);
        } catch (Exception $error){
            returnMYSQLErrorAndClose($stmt, $conn);
            return;
        }

        if (!attemptExecute($stmt, $conn))
            return;

    }



    // Return successful result
    $result = '{"result": "Rating added successfully."}';
    returnObject($result);
    return;
?>