<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $firstName = $inData["firstName"] ?? null;
    $lastName = $inData["lastName"] ?? null;
    $email = strtolower($inData["email"]) ?? null;
    $username = $inData["username"] ?? null;

    if (!$firstName || !$lastName || !$username) {
        returnError("All fields must be filled.");
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        returnError("Email is not in the proper format.");
        return;
    }

    $emailDomain = explode("@", $email, "2")[1];

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Find if new user's domain has an attached university
    $stmt = $conn->prepare("SELECT * FROM universities WHERE university_domain=?");
    $stmt->bind_param("s", $emailDomain);

    if (!attemptExecute($stmt, $conn))
        return;

    $universityRow = $stmt->get_result()->fetch_assoc();

    if (!$universityRow) {
        returnErrorAndClose("New user's email domain has no associated university.", $stmt, $conn);
        return;
    }

    // Prepare, bind and execute
    $stmt = $conn->prepare("SELECT * FROM users WHERE uid=?");
    $stmt->bind_param("i", $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    $oldUserData = $stmt->get_result()->fetch_assoc();

    if (!$oldUserData) {
        returnErrorAndClose("User not found.", $stmt, $conn);
        return;
    }



    /* If you're transferring between universities AND you're the admin of any RSO 
       OR a super-admin of your old university, you can't change universities until
       you pass off your duties.
    */
    if ($oldUserData["university_id"] != $universityRow["university_id"]) {
        $stmt = $conn->prepare("SELECT * FROM universities WHERE super_admin_id=?");
        $stmt->bind_param("i", $currentUser);

        if (!attemptExecute($stmt, $conn))
            return;

        if ($stmt->get_result()->fetch_assoc()) {
            returnErrorAndClose("You cannot change universities as the super-admin of the old university.", $stmt, $conn);
            return;
        }

        $stmt = $conn->prepare("SELECT * FROM rsos WHERE admin_id=?");
        $stmt->bind_param("i", $currentUser);

        if (!attemptExecute($stmt, $conn))
            return;

        if ($stmt->get_result()->fetch_assoc()) {
            returnErrorAndClose("You cannot change universities while still owning an RSO.", $stmt, $conn);
            return;
        }
    }

    


    // Update user
    $stmt = $conn->prepare("UPDATE users SET university_id=?, firstName=?, lastName=?, email=?, username=? WHERE uid=?");
    $stmt->bind_param("issssi", $universityRow["university_id"], $firstName, $lastName, $email, $username, $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    // Return successful result
    $result = '{"result": {"uid":' . $currentUser . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","email":"' . $email . '","username":"' . $username . '"} }';
    returnObject($result);
    return;
?>