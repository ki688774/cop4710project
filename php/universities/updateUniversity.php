<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../locations/updateLocation.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $universityID = $inData["university_id"] ?? null;
    $universityDomain = $inData["university_domain"] ?? null;
    $superAdminID = $inData["super_admin_id"] ?? null;
    $universityName = $inData["university_name"] ?? null;

    $locationName = $inData["location_name"] ?? null;
    $address = $inData["address"] ?? null;
    $longitude = $inData["longitude"] ?? null; 
    $latitude = $inData["latitude"] ?? null;

    if (!$currentUser || !$universityID || !$universityDomain || !$superAdminID || !$universityName) {
        returnError("All university fields must be filled.");
        return;
    }

    if (!$locationName || !$address || is_null($longitude) || is_null($latitude)) {
        returnError("All location fields must be filled.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Check if university belongs to the user
    $stmt = $conn->prepare("SELECT * FROM universities WHERE university_id=? AND super_admin_id=?");
    $stmt->bind_param("si", $universityID, $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    $universityData = $stmt->get_result()->fetch_assoc();

    if (!$universityData) {
        returnErrorAndClose("University not found or does not belong to user.", $stmt, $conn);
        return;
    }

    $locationID = $universityData["location_id"];



    // Check if the new super-admin actually exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE uid=? AND university_id=?");
    $stmt->bind_param("ii", $superAdminID, $universityID);

    if (!attemptExecute($stmt, $conn))
        return;

    if (!$stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("New super-admin not found or belongs to another university.", $stmt, $conn);
        return;
    }



    // Update location
    if (!updateLocation($locationID, $locationName, $address, $longitude, $latitude, $stmt, $conn))
        return;

    // Update university
    $stmt = $conn->prepare("UPDATE universities SET university_domain=?, university_name=?, super_admin_id=? WHERE university_id=?");
    $stmt->bind_param("ssiii", $universityDomain, $universityName, $superAdminID, $universityID);

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "University updated successfully."}';
    returnObject($result);
    return;
?>