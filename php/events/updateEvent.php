<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../locations/updateLocation.php';
    require __DIR__ . '/canEditEvent.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;

    $locationName = $inData["location_name"] ?? null;
    $address = $inData["address"] ?? null;
    $longitude = $inData["longitude"] ?? null; 
    $latitude = $inData["latitude"] ?? null;

    $eventID = $inData["event_id"] ?? null;
    $startTime = $inData["start_time"] ?? null;
    $endTime = $inData["end_time"] ?? null;
    $eventName = $inData["event_name"] ?? null;
    $eventDescription = $inData["event_description"] ?? null;
    $contactPhone = $inData["contact_phone"] ?? null;
    $contactEmail = strtolower($inData["contact_email"]) ?? null;

    if (!$currentUser || !$eventID || !$startTime || !$endTime || !$eventName || !$eventDescription || !$contactPhone || !$locationName || !$address || is_null($longitude) || is_null($latitude)) {
        returnError("All fields must be filled.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;

    // Check if the event can be updated.
    if (canEditEvent($currentUser, $eventID, $stmt, $conn))
        postCheckUpdateEvent($stmt, $conn);




    // Update the event.
    function postCheckUpdateEvent (&$stmt, &$conn) {
        global $eventID, $startTime, $endTime, $eventName, $eventDescription, $contactPhone, $contactEmail, $locationName, $address, $longitude, $latitude;

        try {
            $stmt = $conn->prepare("SELECT location_id FROM events WHERE event_id=?");
            $stmt->bind_param("i", $eventID);
        } catch (Exception $error){
            returnMYSQLErrorAndClose($stmt, $conn);
            return;
        }
    

        if (!attemptExecute($stmt, $conn))
            return;

        if (!updateLocation($stmt->get_result()->fetch_assoc()["location_id"], $locationName, $address, $longitude, $latitude, $stmt, $conn))
            return;


        try {
            $stmt = $conn->prepare("UPDATE events SET start_time=?, end_time=?, event_name=?, event_description=?, contact_phone=?, contact_email=?, total_rating=NULL WHERE event_id=?");
            $stmt->bind_param("ssssssi", $startTime, $endTime, $eventName, $eventDescription, $contactPhone, $contactEmail, $eventID);
        } catch (Exception $error){
            returnMYSQLErrorAndClose($stmt, $conn);
            return;
        }

        if (!attemptExecute($stmt, $conn))
            return;

        try {
            $stmt = $conn->prepare("DELETE FROM comments WHERE event_id=?");
            $stmt->bind_param("i", $eventID);
        } catch (Exception $error){
            returnMYSQLErrorAndClose($stmt, $conn);
            return;
        }

        if (!attemptExecute($stmt, $conn))
            return;



        // Return successful result
        $result = '{"result": "Event updated successfully."}';
        returnObject($result);
    }
?>