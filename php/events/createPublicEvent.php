<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    $phoneRegex = "/^\([0-9]{3}\) [0-9]{3}-[0-9]{4}$/";
    require __DIR__ . '/../locations/createLocation.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;

    $locationName = $inData["location_name"] ?? null;
    $address = $inData["address"] ?? null;
    $longitude = $inData["longitude"] ?? null;
    $latitude = $inData["latitude"] ?? null;

    $startTime = $inData["start_time"] ?? null;
    $endTime = $inData["end_time"] ?? null;
    $eventName = $inData["event_name"] ?? null;
    $eventDescription = $inData["event_description"] ?? null;
    $contactPhone = $inData["contact_phone"] ?? null;
    $contactEmail = strtolower($inData["contact_email"]) ?? null;

    if (!$currentUser || !$startTime || !$endTime || !$eventName || !$eventDescription || !$contactPhone || !$locationName || !$address || is_null($longitude) || is_null($latitude)) {
        returnError("All fields must be filled.");
        return;
    }

    if (!preg_match($phoneRegex, $contactPhone)) {
        returnError("Contact phone number is not in the proper format.");
        return;
    }

    if (!filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
        returnError("Contact email is not in the proper format.");
        return;
    }

    

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Check if current user is a super-admin.
    $stmt = $conn->prepare("SELECT university_id FROM universities WHERE super_admin_id=?");
    $stmt->bind_param("i", $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    $universityID = $stmt->get_result()->fetch_assoc()["university_id"];

    if (!$universityID) {
        returnErrorAndClose("Attempted to create public event without being a super-admin.", $stmt, $conn);
        return;
    }



    $conn->begin_transaction();

    if (!createLocation($locationName, $address, $longitude, $latitude, $stmt, $conn))
        return;

    $locationID = $conn->insert_id;

    // Create the event in the events table and add its reference to the public_events table.
    $stmt = $conn->prepare("INSERT INTO events (start_time, end_time, event_name, event_description, contact_phone, contact_email, location_id) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssi", $startTime, $endTime, $eventName, $eventDescription, $contactPhone, $contactEmail, $locationID);

    if (!attemptExecute($stmt, $conn))
        return;

    $eventID = $conn->insert_id;
    $stmt = $conn->prepare("INSERT INTO public_events (event_id, university_id) VALUES (?,?)");
    $stmt->bind_param("ii", $eventID, $universityID);

    if (!attemptExecute($stmt, $conn))
        return;

    $conn->commit();



    // Return successful result
    $result = '{"result": "Public event added successfully."}';
    returnObject($result);
    return;
?>