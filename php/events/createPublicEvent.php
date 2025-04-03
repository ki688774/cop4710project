<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    $phoneRegex = "/^\([0-9]{3}\) [0-9]{3}-[0-9]{4}$/";

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;

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
        $stmt->close();
        $conn->close();
        return;
    }



    // Check if current user is a super-admin.
    $stmt = $conn->prepare("SELECT university_id FROM universities WHERE super_admin_id=?");
    $stmt->bind_param("i", $currentUser);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    $universityID = $stmt->get_result()->fetch_assoc()["univeristy_id"];

    if (!$universityID) {
        returnError("Attempted to create public event without being a super-admin.");
        $stmt->close();
        $conn->close();
        return;
    }



    // Create the event in the events table and add its reference to the public_events table.
    $stmt = $conn->prepare("INSERT INTO events (start_time, end_time, event_name, event_description, contact_phone, contact_email, location_id) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssi", $startTime, $endTime, $eventName, $eventDescription, $contactPhone, $contactEmail, $locationID);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    $eventID = $conn->insert_id;
    $stmt = $conn->prepare("INSERT INTO public_events (event_id, university_id) VALUES (?,?)");
    $stmt->bind_param("ii", $eventID, $universityID);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }
    


    $result = '{"result": "Public event added successfully."}';
    returnObject($result);
    return;
?>