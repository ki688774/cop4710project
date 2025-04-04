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
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
    $stmt->bind_param("i", $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    $universityID = $stmt->get_result()->fetch_assoc()["university_id"];





    // Search events
    $stmt = $conn->prepare("SELECT * FROM public_events WHERE event_name LIKE ?
        UNION SELECT * FROM P private_events WHERE event_name LIKE ? AND university_id = ?
        UNION SELECT * FROM E rso_events WHERE event_name LIKE ? AND EXISTS (SELECT * FROM R rso_joins WHERE E.rso_id=R.rso_id AND R.uid = ?)");
    $stmt->bind_param("ssisi", $searchTerm, $searchTerm, $universityID, $searchTerm, $currentUser);

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