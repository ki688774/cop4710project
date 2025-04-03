<?php
    $inData = json_decode(file_get_contents('php://input'), true);

    // Proccess input and pre-hash password
    $firstName = $inData["firstName"] ?? null;
    $lastName = $inData["lastName"] ?? null;
    $email = $inData["email"] ?? null;
    $username = $inData["username"] ?? null;
    $password = $inData["password"] ?? null;

    $locationName = $inData["location_name"] ?? null;
    $address = $inData["address"] ?? null;
    $longitude = $inData["longitude"];
    $latitude = $inData["latitude"];

    $universityName = $inData["university_name"] ?? null;

    if (!$firstName || !$lastName || !$username || !$password) {
        returnError("All super-admin fields must be filled.");
        return;
    }

    if (!$locationName || !$address) {
        returnError("All university address fields must be filled.");
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
    $conn = mysqli_connect("localhost", "root", "", "cop4710project");

    if (!$conn) {
        returnError("Could not connect to the server.");
        return;
    }

    // Find if new user's domain has an attached university
    $stmt = $conn->prepare("select count(*) as count from universities where university_domain=?");
    $stmt->bind_param("s", $emailDomain);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    if ($stmt->get_result()->fetch_assoc()['count'] > 0) {
        returnError("University domain is already in use.");
        $stmt->close();
        $conn->close();
        return;
    }



    // Find if the new user's email is in use
    $stmt = $conn->prepare("select count(*) as count from users where email=?");
    $stmt->bind_param("s", $email);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    if ($stmt->get_result()->fetch_assoc()['count'] > 0) {
        returnError("Email already belongs to a user.");
        $stmt->close();
        $conn->close();
        return;
    }



    // Add super-admin
    $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, username, password) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $username, $hashedPass);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    $superAdminID = $conn->insert_id;

    // Add location
    $stmt = $conn->prepare("INSERT INTO locations (location_name, address, longitude, latitude) VALUES (?,?,?,?)");
    $stmt->bind_param("ssdd", $locationName, $address, $longitude, $latitude);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    $locationID = $conn->insert_id;

    // Add university
    $stmt = $conn->prepare("INSERT INTO universities (university_domain, university_name, location_id, super_admin_id) VALUES (?,?,?,?)");
    $stmt->bind_param("ssii", $emailDomain, $universityName, $locationID, $superAdminID);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    $universityID = $conn->insert_id;

    // Set super-admin's university domain
    $stmt = $conn->prepare("UPDATE users SET university_id=? where uid=?");
    $stmt->bind_param("si", $universityID, $superAdminID);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    $result = '{"result": "University, location and super-admin added successfully."}';
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