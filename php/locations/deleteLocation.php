<?php
    $inData = json_decode(file_get_contents('php://input'), true);

    // Proccess input
    $locationID = $inData["location_id"] ?? null;

    // Create and check connection
    try {
        $conn = mysqli_connect("localhost", "root", "", "cop4710project");
    } catch (Exception $e) {
        returnError($e);
        $conn->close();
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



    // Delete location
    $stmt = $conn->prepare("DELETE FROM locations WHERE location_id=?");
    $stmt->bind_param("i", $locationID);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }



    $result = '{"result": "Location deleted successfully."}';
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