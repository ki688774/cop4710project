<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;

    // Create and check connection
    if (!attemptConnect($conn))
        return;


    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE uid=?");
    $stmt->bind_param("i", $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    if (!$stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("User not found.", $stmt, $conn);
        return;
    }



    // Delete user
    $stmt = $conn->prepare("DELETE FROM users WHERE uid=?");
    $stmt->bind_param("i", $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "User deleted successfully."}';
    returnObject($result);
    return;
?>