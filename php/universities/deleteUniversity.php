<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $universityDomain = $inData["university_domain"] ?? null;
    $password = $inData["password"] ?? null;

    if (!$universityDomain || !$password || !$currentUser) {
        returnError("University domain and super-admin password.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE uid=?");
    $stmt->bind_param("i", $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    $oldUserData = $stmt->get_result()->fetch_assoc();

    if (!$oldUserData || !password_verify($password, $oldUserData["password"])) {
        returnErrorAndClose("Password is incorrect.", $stmt, $conn);
        return;
    }



    // Check if university is accessible
    $stmt = $conn->prepare("SELECT * FROM universities WHERE university_domain=? AND super_admin_id=?");
    $stmt->bind_param("si", $universityDomain, $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    $targetRow = $stmt->get_result()->fetch_assoc();

    if (!$targetRow) {
        returnErrorAndClose("University domain is incorrect.", $stmt, $conn);
        return;
    }

    // Check if the super-admin is also an admin of an RSO.
    $stmt = $conn->prepare("SELECT * FROM rsos WHERE admin_id=?");
    $stmt->bind_param("i", $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    if ($stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("You cannot delete your account as the admin of an RSO.", $stmt, $conn);
        return;
    }



    // Set the super-admin's university domain to null.
    $stmt = $conn->prepare("UPDATE users SET university_id=NULL where uid=?");
    $stmt->bind_param("i", $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    // Delete the university.
    $stmt = $conn->prepare("DELETE FROM universities WHERE university_id=? AND super_admin_id=?");
    $stmt->bind_param("ii", $universityID, $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;


    // Delete the former super-admin.
    $stmt = $conn->prepare("DELETE FROM users WHERE uid=?");
    $stmt->bind_param("i", $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "University deleted successfully."}';
    returnObject($result);
    return;
?>