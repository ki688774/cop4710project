<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    $phoneRegex = "/^\([0-9]{3}\) [0-9]{3}-[0-9]{4}$/";

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $rsoID = $inData["rso_id"] ?? null;

    $startTime = $inData["start_time"] ?? null;
    $endTime = $inData["end_time"] ?? null;
    $eventName = $inData["event_name"] ?? null;
    $eventDescription = $inData["event_description"] ?? null;
    $contactPhone = $inData["contact_phone"] ?? null;
    $contactEmail = $inData["contact_email"] ?? null;
    $locationID = $inData["location_id"] ?? null;

    if (!$currentUser || !$startTime || !$endTime || !$eventName || !$eventDescription || !$contactPhone || !$locationID) {
        returnError("All fields must be filled.");
        $stmt->close();
        $conn->close();
        return;
    }

    if (!preg_match($phoneRegex, $contactPhone)) {
        returnError("Contact phone number is not in the proper format.");
        $stmt->close();
        $conn->close();
        return;
    }

    if (!filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
        returnError("Contact email is not in the proper format.");
        return;
    }



    // Check if current user is the admin of the target RSO.
    $stmt = $conn->prepare("SELECT * FROM rsos WHERE admin_id=? AND rso_id=?");
    $stmt->bind_param("ii", $currentUser, $rsoID);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    if (!$stmt->get_result()->fetch_assoc()) {
        returnError("RSO not found or does not belong to current user");
        $stmt->close();
        $conn->close();
        return;
    }



    // Create the event in the events table and add its reference to the rso_events table.
    $stmt = $conn->prepare("INSERT INTO events (start_time, end_time, event_name, event_description, contact_phone, contact_email, location_id) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssi", $startTime, $endTime, $eventName, $eventDescription, $contactPhone, $contactEmail, $locationID);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    $eventID = $conn->insert_id;
    $stmt = $conn->prepare("INSERT INTO rso_events (event_id, rso_id) VALUES (?,?)");
    $stmt->bind_param("ii", $eventID, $rsoID);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    

    $result = '{"result": "RSO event added successfully."}';
    returnObject($result);
    return;
?>