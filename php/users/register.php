<?php
    $inData = json_decode(file_get_contents('php://input'), true);

    // Proccess input and pre-hash password
    $firstName = $inData["firstName"] ?? null;
    $lastName = $inData["lastName"] ?? null;
    $email = $inData["email"] ?? null;
    $username = $inData["username"] ?? null;
    $password = $inData["password"] ?? null;

    if (!$firstName || !$lastName || !$username || !$password) {
        returnError("All fields must be filled.");
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
    $stmt = $conn->prepare("SELECT * FROM universities WHERE university_domain=?");
    $stmt->bind_param("s", $emailDomain);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    $targetRow = $stmt->get_result()->fetch_assoc();

    if (!$targetRow) {
        returnError("New user's email domain has no associated university.");
        $stmt->close();
        $conn->close();
        return;
    }



    // Prepare, bind and execute
    $stmt = $conn->prepare("INSERT INTO users (university_id, firstName, lastName, email, username, password) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("isssss", $targetRow["university_id"], $firstName, $lastName, $email, $username, $hashedPass);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    $result = '{"result": "New user added successfully."}';
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