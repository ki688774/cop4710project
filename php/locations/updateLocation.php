<?php
    $inData = json_decode(file_get_contents('php://input'), true);

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
    $conn = mysqli_connect("localhost", "root", "", "cop4710project");

    if (!$conn) {
        returnError("Could not connect to the server.");
        return;
    }

    // Check if location exists
    $stmt = $conn->prepare("SELECT * FROM locations WHERE location_id=?");
    $stmt->bind_param("i", $locationID);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    if (!$stmt->get_result()->fetch_assoc()) {
        returnError("Location not found.");
        $stmt->close();
        $conn->close();
        return;
    }



    // Update location
    $stmt = $conn->prepare("UPDATE locations SET location_name=?, address=?, longitude=?, latitude=? WHERE location_id=?");
    $stmt->bind_param("ssddi", $locationName, $address, $longitude, $latitude, $locationID);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }



    $result = '{"result": "Location updated successfully."}';
    returnObject($result);
    return;


    function returnError ($error) {
        returnObject('{"error": "' . $error . '"}');
    }

    function returnObject ($target) {
        header('Content-type: application/json');
        echo $target;
    }
?>