<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $search = $inData["search"] ?? null;
    $minRating = $inData["minimum_rating"] ?? 1;
    $maxRating = $inData["maximum_rating"] ?? 5;
    $minTime = $inData["minimum_time"] ?? "1000-01-01 00:00:00";
    $maxTime = $inData["maximum_time"] ?? "9999-12-31 23:59:59";
    $eventID = $inData["event_id"] ?? null;

    if (!$currentUser || !$eventID) {
        returnError("All critical fields must be filled.");
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



    // Search for comments.
    $stmt = $conn->prepare("SELECT * FROM comments WHERE event_id=? AND rating>=? AND rating<=? AND timestamp>=? AND timestamp<=?");
    $stmt->bind_param("iiiss", $eventID, $minRating, $maxRating, $minTime, $maxTime);

    if (!attemptExecute($stmt, $conn))
        return;

    assembleJsonArrayFromQuery($stmt, $conn, $rows);
    
    
    
    // Return comments as result
    $result = '{"result": ' . $rows . '}';
    returnObject($result);
    $stmt->close();
    $conn->close();
    return;
?>