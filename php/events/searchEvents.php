<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $searchTerm = "%" . $inData["search"] . "%";

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





    // Search events
    $stmt = $conn->prepare("SELECT * FROM events E WHERE event_name LIKE ? AND (
	    EXISTS (SELECT * FROM public_events P WHERE P.event_id=E.event_id) OR
	    EXISTS (SELECT * FROM private_events P WHERE P.event_id=E.event_id AND university_id=?) OR
	    EXISTS (SELECT * FROM rso_events R WHERE R.event_id=E.event_id AND EXISTS (SELECT * FROM rso_joins J WHERE R.rso_id=J.rso_id AND J.uid=?)))");
    $stmt->bind_param("sii", $searchTerm, $universityID, $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    $rows = "";

    if (assembleJsonArrayFromQuery($stmt, $rows) == 0) {
        returnErrorAndClose("No events found.", $stmt, $conn);
        return;
    }



    // Return events as result
    $result = '{"result": ' . $rows . '}';
    returnObject($result);
    $stmt->close();
    $conn->close();
    return;
?>