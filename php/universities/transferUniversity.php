<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../locations/updateLocation.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $newSuperAdminEmail = strtolower($inData["new_super_admin_email"]) ?? null;
    $password = $inData["password"] ?? null;

    if (!$newSuperAdminEmail) {
        returnError("The new super-admin's email must be provided.");
        return;
    }

    if (!filter_var($newSuperAdminEmail, FILTER_VALIDATE_EMAIL)) {
        returnError("The new super-admin's email is in the wrong format.");
        return;
    }

    $emailDomain = explode("@", $newSuperAdminEmail, "2")[1];

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
        returnErrorAndClose("The password is incorrect.", $stmt, $conn);
        return;
    }

    // Check if university belongs to the user.
    try {
        $stmt = $conn->prepare("SELECT * FROM universities WHERE super_admin_id=?");
        $stmt->bind_param("i", $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $universityData = $stmt->get_result()->fetch_assoc();

    if (!$universityData) {
        returnErrorAndClose("The university does not belong to the current user.", $stmt, $conn);
        return;
    }

    $universityID = $universityData["university_id"];

    // Check if the new super-admin belongs to the university.
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND university_id=?");
        $stmt->bind_param("si", $newSuperAdminEmail, $universityID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $newSuperAdminData = $stmt->get_result()->fetch_assoc();

    if (!$newSuperAdminData) {
        returnErrorAndClose("The new super-admin was either not found or belongs to another university.", $stmt, $conn);
        return;
    }



    // Update university
    try {
        $stmt = $conn->prepare("UPDATE universities SET super_admin_id=? WHERE university_id=?");
        $stmt->bind_param("ii", $newSuperAdminData["uid"], $universityID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "University updated successfully."}';
    returnObject($result);
    return;
?>