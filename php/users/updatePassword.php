<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input and pre-hash newPassword
    $currentUser = $inData["current_user"] ?? null;
    $password = $inData["password"] ?? null;
    $newPassword = $inData["new_password"] ?? null;

    if (!$password || !$newPassword) {
        returnError("All fields must be filled.");
        return;
    }

    $hashedPass = password_hash($newPassword, PASSWORD_DEFAULT);

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Check if the provided old password was correct.
    $stmt = $conn->prepare("SELECT * FROM users WHERE uid=?");
    $stmt->bind_param("i", $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    $oldUserData = $stmt->get_result()->fetch_assoc();

    if (!$oldUserData || !password_verify($password, $oldUserData["password"])) {
        returnErrorAndClose("Old password was incorrect.", $stmt, $conn);
        return;
    }



    // Update user
    $stmt = $conn->prepare("UPDATE users SET password=? WHERE uid=?");
    $stmt->bind_param("si", $hashedPass, $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "Password updated successfully."}';
    returnObject($result);
    return;
?>