<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $universityID = $inData["university_id"] ?? null;

    if (!$universityID || !$currentUser) {
        returnError("University ID and current user must be given.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Check if university is accessible
    $stmt = $conn->prepare("SELECT * FROM universities WHERE university_id=? AND super_admin_id=?");
    $stmt->bind_param("ii", $universityID, $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    $targetRow = $stmt->get_result()->fetch_assoc();

    if (!$targetRow) {
        returnErrorAndClose("University not found.", $stmt, $conn);
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