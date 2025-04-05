<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;

    $eventID = $inData["event_id"] ?? null;
    $startTime = $inData["start_time"] ?? null;
    $endTime = $inData["end_time"] ?? null;
    $eventName = $inData["event_name"] ?? null;
    $eventDescription = $inData["event_description"] ?? null;
    $contactPhone = $inData["contact_phone"] ?? null;
    $contactEmail = strtolower($inData["contact_email"]) ?? null;
    $locationID = $inData["location_id"] ?? null;

    if (!$currentUser || !$eventID || !$startTime || !$endTime || !$eventName || !$eventDescription || !$contactPhone || !$locationID) {
        returnError("All fields must be filled.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    $stmt = $conn->prepare("SELECT * FROM private_events WHERE event_id=?");
    $stmt->bind_param("i", $eventID);

    if (!attemptExecute($stmt, $conn))
        return;

    $rsoID = $stmt->get_result()->fetch_assoc()["rso_id"];

    if ($rsoID) {
        rsoBasedCheck($stmt, $conn);
        return;
    }



    $stmt = $conn->prepare("SELECT * FROM rso_events WHERE event_id=?");
    $stmt->bind_param("i", $eventID);

    if (!attemptExecute($stmt, $conn))
        return;

    $rsoID = $stmt->get_result()->fetch_assoc()["rso_id"];

    if ($rsoID) {
        rsoBasedCheck($stmt, $conn);
        return;
    }   



    $stmt = $conn->prepare("SELECT * FROM public_events WHERE event_id=?");
    $stmt->bind_param("i", $eventID);

    if (!attemptExecute($stmt, $conn))
        return;

    $universityID = $stmt->get_result()->fetch_assoc()["university_id"];

    if ($universityID) {
        $stmt = $conn->prepare("SELECT * FROM universities WHERE university_id=? AND super_admin_id=?");
        $stmt->bind_param("ii", $universityID, $currentUser);

        if (!attemptExecute($stmt, $conn))
            return;

        if (!$stmt->get_result()->fetch_assoc()) {
            returnErrorAndClose("User is not the super-admin of the event's university.", $stmt, $conn);
            return;
        }

        postCheckUpdateEvent($stmt, $conn);
        return;
    }    



    // If it's not in any of the specific event lists, then it doesn't exist.
    returnErrorAndClose("Event not found.", $stmt, $conn);
    return;



    function rsoBasedCheck (&$stmt, &$conn) {
        global $currentUser, $eventID;
        $stmt = $conn->prepare("SELECT * FROM rso WHERE rso_id=? AND admin_id=?");
        $stmt->bind_param("ii", $eventID, $currentUser);

        if (!attemptExecute($stmt, $conn))
            return;

        if (!$stmt->get_result()->fetch_assoc()) {
            returnErrorAndClose("User is not the admin of the RSO.", $stmt, $conn);
            return;
        }

        postCheckUpdateEvent($stmt, $conn);
    }

    // Update the event.
    function postCheckUpdateEvent (&$stmt, &$conn) {
        global $currentUser, $eventID, $startTime, $endTime, $eventName, $eventDescription, $contactPhone, $contactEmail, $locationID;
        $stmt = $conn->prepare("UPDATE events SET start_time=?, end_time=?, event_name=?, event_description=?, contact_phone=?, contact_email=?, location_id=?, total_rating=NULL WHERE event_id=?");
        $stmt->bind_param("ssssssii", $startTime, $endTime, $eventName, $eventDescription, $contactPhone, $contactEmail, $locationID, $eventID);

        if (!attemptExecute($stmt, $conn))
            return;

        $stmt = $conn->prepare("DELETE FROM comments WHERE event_id=?");
        $stmt->bind_param("i", $eventID);

        if (!attemptExecute($stmt, $conn))
            return;



        // Return successful result
        $result = '{"result": "Event updated successfully."}';
        returnObject($result);
    }
?>