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
    if (!attemptConnect($conn))
        return;



    // Prepare, bind and execute
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);

    if (!attemptExecute($stmt, $conn))
        return;

    $loginRow = $stmt->get_result()->fetch_assoc();

    if (!$loginRow || !password_verify($password, $loginRow["password"])) {
        returnErrorAndClose("User and password combination not found.", $stmt, $conn);
        return;
    }

    array_splice($loginRow, 6, 1);

    // Return successful result
    $result = '{"result":' . json_encode($loginRow) . '}';
    returnObject($result);
    return;
?>