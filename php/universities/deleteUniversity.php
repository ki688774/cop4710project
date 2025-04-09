<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $universityDomain = strtolower($inData["university_domain"]) ?? null;
    $password = $inData["password"] ?? null;

    if (!$universityDomain || !$password || !$currentUser) {
        returnError("University domain and super-admin password must be given.");
        return;
    }

    if (!filter_var("a@". $universityDomain, FILTER_VALIDATE_EMAIL)) {
        returnError("Email domain is not in the proper format.");
        return;
    }

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
        returnErrorAndClose("Password is incorrect.", $stmt, $conn);
        return;
    }

    // Check if university is accessible
    try {
        $stmt = $conn->prepare("SELECT * FROM universities WHERE university_domain=? AND super_admin_id=?");
        $stmt->bind_param("si", $universityDomain, $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $targetRow = $stmt->get_result()->fetch_assoc();

    if (!$targetRow) {
        returnErrorAndClose("University domain is incorrect.", $stmt, $conn);
        return;
    }



    // Set the super-admin's university domain to null.
    try {
        $stmt = $conn->prepare("UPDATE users SET university_id=NULL where uid=?");
        $stmt->bind_param("i", $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    // Delete the university.
    try {
        $stmt = $conn->prepare("DELETE FROM universities WHERE university_id=? AND super_admin_id=?");
        $stmt->bind_param("ii", $universityID, $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;


    // Delete the former super-admin.
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
    $result = '{"result": "University deleted successfully."}';
    returnObject($result);
    return;
?>