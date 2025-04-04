<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $locationName = $inData["location_name"] ?? null;
    $address = $inData["address"] ?? null;
    $longitude = $inData["longitude"];
    $latitude = $inData["latitude"];

    if (!$locationName || !$address) {
        returnError("All fields must be filled.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Add location
    $stmt = $conn->prepare("INSERT INTO locations (location_name, address, longitude, latitude) VALUES (?,?,?,?)");
    $stmt->bind_param("ssdd", $locationName, $address, $longitude, $latitude);

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "Location added successfully."}';
    returnObject($result);
    return;
?>