<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $locationID = $inData["location_id"] ?? null;
    $locationName = $inData["location_name"] ?? null;
    $address = $inData["address"] ?? null;
    $longitude = $inData["longitude"];
    $latitude = $inData["latitude"];

    if (!$locationID || !$locationName || !$address) {
        returnError("All fields must be filled.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Check if location exists
    $stmt = $conn->prepare("SELECT * FROM locations WHERE location_id=?");
    $stmt->bind_param("i", $locationID);

    if (!attemptExecute($stmt, $conn))
        return;

    if (!$stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("Location not found.", $stmt, $conn);
        return;
    }



    // Update location
    $stmt = $conn->prepare("UPDATE locations SET location_name=?, address=?, longitude=?, latitude=? WHERE location_id=?");
    $stmt->bind_param("ssddi", $locationName, $address, $longitude, $latitude, $locationID);

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "Location updated successfully."}';
    returnObject($result);
    return;
?>