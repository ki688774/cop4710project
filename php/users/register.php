<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input and pre-hash password
    $firstName = $inData["firstName"] ?? null;
    $lastName = $inData["lastName"] ?? null;
    $email = strtolower($inData["email"]) ?? null;
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
    if (!attemptConnect($conn))
        return;



    // Find if new user's domain has an attached university
    try {
        $stmt = $conn->prepare("SELECT * FROM universities WHERE university_domain=?");
        $stmt->bind_param("s", $emailDomain);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $targetRow = $stmt->get_result()->fetch_assoc();

    if (!$targetRow) {
        returnErrorAndClose("New user's email domain has no associated university.", $stmt, $conn);
        return;
    }



    // Prepare, bind and execute
    try {
        $stmt = $conn->prepare("INSERT INTO users (university_id, firstName, lastName, email, username, password) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("isssss", $targetRow["university_id"], $firstName, $lastName, $email, $username, $hashedPass);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "New user added successfully."}';
    returnObject($result);
    return;
?>