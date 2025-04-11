<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $searchTerm = "%" . $inData["search"] . "%";
    $onlyYourEvents = ($inData["only_your_events"] ?? false) ? 1 : 0;
    $sortType = $inData["sort_type"] ?? 0;
    $minRating = $inData["minimum_rating"] ?? 0;
    $maxRating = $inData["maximum_rating"] ?? 5;
    $now = new DateTime();
    $minTime = $inData["minimum_time"] ?? "1000-01-01 00:00:00"; 
    $maxTime = $inData["maximum_time"] ?? "9999-12-31 23:59:59";

    if ($minTime == "")
        $minTime = "1000-01-01 00:00:00"; 

    if ($maxTime == "")
        $maxTime = "9999-12-31 23:59:59";

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

    $sortTypeText = null;

    switch ($sortType) {
        case 1:
            $sortTypeText = "start_time DESC";
            break;
        case 2:
            $sortTypeText = "end_time ASC";
            break;
        case 3:
            $sortTypeText = "end_time DESC";
            break;
        case 4:
            $sortTypeText = "total_rating ASC";
            break;
        case 5:
            $sortTypeText = "total_rating DESC";
            break;
        case 6:
            $sortTypeText = "event_name ASC";
            break;
        case 7:
            $sortTypeText = "event_name DESC";
            break;
        default:
            $sortTypeText = "start_time ASC";
    }

    // Search events that are still occurring between $minTime and $maxTime that the user has access to.
    try {
        $stmt = $conn->prepare("SELECT * FROM events E WHERE (event_name LIKE ? or event_description LIKE ?) AND (total_rating>=? OR (total_rating IS NULL AND ?=0)) AND (total_rating<=? OR (total_rating IS NULL AND ?=0)) AND (NOT ((start_time < ? AND end_time < ?) OR (start_time > ? AND end_time > ?))) AND (
	        EXISTS (SELECT * FROM public_events P WHERE P.event_id=E.event_id AND ((NOT 1 = ?) OR EXISTS (SELECT * FROM universities U WHERE U.university_id=P.university_id AND U.super_admin_id=?))) OR
	        EXISTS (SELECT * FROM private_events P WHERE P.event_id=E.event_id AND university_id=? AND ((NOT 1 = ?) OR EXISTS (SELECT * FROM rsos R WHERE P.rso_id=R.rso_id AND R.admin_id=?))) OR
	        EXISTS (SELECT * FROM rso_events RE WHERE RE.event_id=E.event_id AND EXISTS (SELECT * FROM rso_joins J WHERE RE.rso_id=J.rso_id AND J.uid=?) AND ((NOT 1 = ?) OR EXISTS (SELECT * FROM rsos R WHERE RE.rso_id=R.rso_id AND R.admin_id=?)))) 
            ORDER BY " . $sortTypeText);
        $stmt->bind_param("ssiiiissssiiiiiiii", $searchTerm, $searchTerm, $minRating, $minRating, $maxRating, $minRating, $minTime, $minTime, $maxTime, $maxTime, $onlyYourEvents, $currentUser, $universityID, $onlyYourEvents, $currentUser, $currentUser, $onlyYourEvents, $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

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