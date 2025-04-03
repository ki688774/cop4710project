<?php
    $inData = json_decode(file_get_contents('php://input'), true);

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
    $conn = mysqli_connect("localhost", "root", "", "cop4710project");

    if (!$conn) {
        returnError("Could not connect to the server.");
        return;
    }

    // Add location
    $stmt = $conn->prepare("INSERT INTO locations (location_name, address, longitude, latitude) VALUES (?,?,?,?)");
    $stmt->bind_param("ssdd", $locationName, $address, $longitude, $latitude);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    $result = '{"result": "Location added successfully."}';
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