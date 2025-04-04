<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $locationID = $inData["location_id"] ?? null;

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



    // Delete location
    $stmt = $conn->prepare("DELETE FROM locations WHERE location_id=?");
    $stmt->bind_param("i", $locationID);

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "Location deleted successfully."}';
    returnObject($result);
    return;
?>