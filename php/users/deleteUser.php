<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $password = $inData["password"] ?? null;

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
        returnErrorAndClose("Password was incorrect.", $stmt, $conn);
        return;
    }

    // Check if user is an admin or super-admin.
    try {
        $stmt = $conn->prepare("SELECT * FROM universities WHERE super_admin_id=? AND university_id=?");
        $stmt->bind_param("ii", $currentUser, $oldUserData["university_id"]);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    if ($stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("You cannot delete your account as the super-admin of the old university without also deleting your university.", $stmt, $conn);
        return;
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM rsos WHERE admin_id=?");
        $stmt->bind_param("i", $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    if ($stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("You cannot delete your account as the admin of an RSO.", $stmt, $conn);
        return;
    }


    // Delete user
    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE uid=?");
        $stmt->bind_param("i", $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "User deleted successfully."}';
    returnObject($result);
    return;
?>