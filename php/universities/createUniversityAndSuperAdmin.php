<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../locations/createLocation.php';

    // Proccess input and pre-hash password
    $firstName = $inData["firstName"] ?? null;
    $lastName = $inData["lastName"] ?? null;
    $email = strtolower($inData["email"]) ?? null;
    $username = $inData["username"] ?? null;
    $password = $inData["password"] ?? null;

    $locationName = $inData["location_name"] ?? null;
    $address = $inData["address"] ?? null;
    $longitude = $inData["longitude"] ?? null;
    $latitude = $inData["latitude"] ?? null;

    $universityName = $inData["university_name"] ?? null;

    if (!$firstName || !$lastName || !$username || !$password) {
        returnError("All super-admin fields must be filled.");
        return;
    }

    if (!$locationName || !$address || is_null($longitude) || is_null($latitude)) {
        returnError("All location fields must be filled.");
        return;
    }

    if (!$universityName) {
        returnError("A university name must be given.");
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        returnError("Email is not in the proper format.");
        return;
    }

    $hashedPass = password_hash($password, PASSWORD_DEFAULT);
    $emailDomain = explode("@", $email, "2")[1];

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Find if new user's domain has an attached university
    try {
        $stmt = $conn->prepare("select count(*) as count from universities where university_domain=?");
        $stmt->bind_param("s", $emailDomain);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    if ($stmt->get_result()->fetch_assoc()['count'] > 0) {
        returnErrorAndClose("University domain is already in use.", $stmt, $conn);
        return;
    }

    // Find if the new user's email is in use
    try {
        $stmt = $conn->prepare("select count(*) as count from users where email=?");
        $stmt->bind_param("s", $email);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    if ($stmt->get_result()->fetch_assoc()['count'] > 0) {
        returnErrorAndClose("Email already belongs to a user.", $stmt, $conn);
        return;
    }



    $conn->begin_transaction();

    // Create location
    if (!createLocation($locationName, $address, $longitude, $latitude, $stmt, $conn))
        return;

    $locationID = $conn->insert_id;

    // Add super-admin
    try {
        $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, username, password) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $firstName, $lastName, $email, $username, $hashedPass);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $superAdminID = $conn->insert_id;

    // Add university
    try {
        $stmt = $conn->prepare("INSERT INTO universities (university_domain, university_name, location_id, super_admin_id) VALUES (?,?,?,?)");
        $stmt->bind_param("ssii", $emailDomain, $universityName, $locationID, $superAdminID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $universityID = $conn->insert_id;

    // Set super-admin's university
    try {
        $stmt = $conn->prepare("UPDATE users SET university_id=? where uid=?");
        $stmt->bind_param("ii", $universityID, $superAdminID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $conn->commit();



    // Return successful result
    $result = '{"result": "University, location and super-admin added successfully."}';
    returnObject($result);
    return;
?>