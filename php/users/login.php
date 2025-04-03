<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $username = $inData["username"] ?? null;
    $password = $inData["password"] ?? null;

    if (!$username || !$password) {
        returnError("All fields must be filled.");
        return;
    }

    // Create and check connection
    // Create and check connection
    try {
        $conn = mysqli_connect("localhost", "root", "", "cop4710project");
    } catch (Exception $e) {
        returnError($e);
        $conn->close();
        return;
    }



    // Prepare, bind and execute
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    $loginRow = $stmt->get_result()->fetch_assoc();

    if (!$loginRow || !password_verify($password, $loginRow["password"])) {
        returnError("User and password combination not found.");
        $stmt->close();
        $conn->close();
        return;
    }

    $result = '{"currentUser":' . $loginRow["uid"] . '}';
    returnObject($result);
    return;
?>