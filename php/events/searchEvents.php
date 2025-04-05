<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $searchTerm = "%" . $inData["search"] . "%";
    $minTime = $inData["minimum_time"] ?? "1000-01-01 00:00:00";
    $maxTime = $inData["maximum_time"] ?? "9999-12-31 23:59:59";

    if (!$currentUser) {
        returnError("You must be signed in to search events.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Find user's university (which must exist)
    $stmt = $conn->prepare("SELECT * FROM users WHERE uid=?");
    $stmt->bind_param("i", $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    $universityID = $stmt->get_result()->fetch_assoc()["university_id"];

    // Search events that are still occurring between $minTime and $maxTime that the user has access to.
    $stmt = $conn->prepare("SELECT * FROM events E WHERE event_name LIKE ? AND (NOT ((start_time < ? AND end_time < ?) OR (start_time > ? AND end_time > ?))) AND (
	    EXISTS (SELECT * FROM public_events P WHERE P.event_id=E.event_id) OR
	    EXISTS (SELECT * FROM private_events P WHERE P.event_id=E.event_id AND university_id=?) OR
	    EXISTS (SELECT * FROM rso_events R WHERE R.event_id=E.event_id AND EXISTS (SELECT * FROM rso_joins J WHERE R.rso_id=J.rso_id AND J.uid=?)))");
    $stmt->bind_param("sssssii", $searchTerm, $minTime, $minTime, $maxTime, $maxTime, $universityID, $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    assembleJsonArrayFromQuery($stmt, $conn, $rows);



    // Return events as result
    $result = '{"result": ' . $rows . '}';
    returnObject($result);
    $stmt->close();
    $conn->close();
    return;
?>