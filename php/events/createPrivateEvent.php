<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    $phoneRegex = "/^\([0-9]{3}\) [0-9]{3}-[0-9]{4}$/";
    require __DIR__ . '/../locations/createLocation.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $rsoID = $inData["rso_id"] ?? null;

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

    if (!$currentUser || !$rsoID || !$startTime || !$endTime || !$eventName || !$eventDescription || !$contactPhone || !$locationName || !$address || is_null($longitude) || is_null($latitude)) {
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



    // Check if current user is the admin of the target RSO.
    try {
        $stmt = $conn->prepare("SELECT * FROM rsos WHERE admin_id=? AND rso_id=? AND active=1");
        $stmt->bind_param("ii", $currentUser, $rsoID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    if (!$stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("RSO not found or does not belong to current user.", $stmt, $conn);
        return;
    }


    $conn->begin_transaction();

    if (!createLocation($locationName, $address, $longitude, $latitude, $stmt, $conn))
        return;

    $locationID = $conn->insert_id;

    // Create the event in the events table and add its reference to the private_events table.
    try {
        $stmt = $conn->prepare("INSERT INTO events (start_time, end_time, event_name, event_description, contact_phone, contact_email, location_id) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssi", $startTime, $endTime, $eventName, $eventDescription, $contactPhone, $contactEmail, $locationID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $eventID = $conn->insert_id;
    
    // Find user's university (which must exist).
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE uid=?");
        $stmt->bind_param("i", $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $universityID = $stmt->get_result()->fetch_assoc()["university_id"];
    
    if (!$universityID) {
        returnError("User not found.");
        return;
    }
    
    try {
        $stmt = $conn->prepare("INSERT INTO private_events (event_id, rso_id, university_id) VALUES (?,?,?)");
        $stmt->bind_param("iii", $eventID, $rsoID, $universityID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $conn->commit();



    // Return successful result
    $result = '{"result": "Private event added successfully.", "event_id": ' . $eventID . '}';
    returnObject($result);
    return;
?>