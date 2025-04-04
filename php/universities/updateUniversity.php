<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $universityID = $inData["university_id"] ?? null;
    $universityDomain = $inData["university_domain"] ?? null;
    $locationID = $inData["location_id"] ?? null;
    $superAdminID = $inData["super_admin_id"] ?? null;
    $universityName = $inData["university_name"] ?? null;

    if (!$currentUser || !$universityID || !$universityDomain || !$locationID || !$superAdminID || !$universityName) {
        returnError("All university address fields must be filled.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Check if university belongs to the user
    $stmt = $conn->prepare("SELECT * FROM universities WHERE university_id=? AND super_admin_id=?");
    $stmt->bind_param("si", $universityID, $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    if (!$stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("University not found or does not belong to user.", $stmt, $conn);
        return;
    }



    // Check if the new super-admin actually exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE uid=? AND university_id=?");
    $stmt->bind_param("ii", $superAdminID, $universityID);

    if (!attemptExecute($stmt, $conn))
        return;

    if (!$stmt->get_result()->fetch_assoc()) {
        returnErrorAndClose("New super-admin not found or belongs to another university.", $stmt, $conn);
        return;
    }



    // Update university
    $stmt = $conn->prepare("UPDATE universities SET university_domain=?, university_name=?, location_id=?, super_admin_id=? WHERE university_id=?");
    $stmt->bind_param("ssiii", $universityDomain, $universityName, $locationID, $superAdminID, $universityID);

    if (!attemptExecute($stmt, $conn))
        return;



    // Return successful result
    $result = '{"result": "University updated successfully."}';
    returnObject($result);
    return;
?>