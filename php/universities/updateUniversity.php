<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../locations/updateLocation.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $universityDomain = strtolower($inData["university_domain"]) ?? null;
    $universityName = $inData["university_name"] ?? null;
    $password = $inData["password"] ?? null;

    $locationName = $inData["location_name"] ?? null;
    $address = $inData["address"] ?? null;
    $longitude = $inData["longitude"] ?? null; 
    $latitude = $inData["latitude"] ?? null;

    if (!$currentUser || !$universityDomain || !$universityName) {
        returnError("All university fields must be filled.");
        return;
    }

    if (!$locationName || !$address || is_null($longitude) || is_null($latitude)) {
        returnError("All location fields must be filled.");
        return;
    }

    if (!filter_var("a@". $universityDomain, FILTER_VALIDATE_EMAIL)) {
        returnError("Email domain is not in the proper format.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Check if user exists
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE uid=?");
        $stmt->bind_param("i", $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $oldUserData = $stmt->get_result()->fetch_assoc();

    if (!$oldUserData || !password_verify($password, $oldUserData["password"])) {
        returnErrorAndClose("The password is incorrect.", $stmt, $conn);
        return;
    }

    // Check if university belongs to the user
    try {
        $stmt = $conn->prepare("SELECT * FROM universities WHERE super_admin_id=?");
        $stmt->bind_param("i", $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $universityData = $stmt->get_result()->fetch_assoc();

    if (!$universityData) {
        returnErrorAndClose("University not found.", $stmt, $conn);
        return;
    }

    $locationID = $universityData["location_id"];



    // Update university
    try {
        $stmt = $conn->prepare("UPDATE universities SET university_domain=?, university_name=? WHERE university_id=?");
        $stmt->bind_param("ssi", $universityDomain, $universityName, $universityID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    // Update location
    if (!updateLocation($locationID, $locationName, $address, $longitude, $latitude, $stmt, $conn))
        return;

    

    // Return successful result
    $result = '{"result": "University updated successfully."}';
    returnObject($result);
    return;
?>