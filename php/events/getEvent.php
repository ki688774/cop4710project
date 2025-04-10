<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $eventID = $inData["event_id"] ?? null;

    if (!$currentUser) {
        returnError("You must be signed in to view events.");
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
    try {
        $stmt = $conn->prepare("SELECT * FROM events E WHERE E.event_id=? AND (
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

    $row = $stmt->get_result()->fetch_assoc();



    // Return events as result
    $result = '{"result": ' . json_encode(expandLocationID($row, $stmt, $conn)) . '}';
    returnObject($result);
    $stmt->close();
    $conn->close();
    return;
?>