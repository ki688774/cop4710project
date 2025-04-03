<?php
    $inData = json_decode(file_get_contents('php://input'), true);

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
    $conn = mysqli_connect("localhost", "root", "", "cop4710project");

    if (!$conn) {
        returnError("Could not connect to the server.");
        return;
    }

    // Check if university is accessible
    $stmt = $conn->prepare("SELECT * FROM universities WHERE university_id=? AND super_admin_id=?");
    $stmt->bind_param("si", $universityID, $currentUser);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    if (!$stmt->get_result()->fetch_assoc()) {
        returnError("University not found.");
        $stmt->close();
        $conn->close();
        return;
    }



    // Check if the new super-admin actually exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE uid=?");
    $stmt->bind_param("i", $superAdminID);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    if (!$stmt->get_result()->fetch_assoc()) {
        returnError("New super-admin not found.");
        $stmt->close();
        $conn->close();
        return;
    }



    // Update university
    $stmt = $conn->prepare("UPDATE universities SET university_domain=?, university_name=?, location_id=?, super_admin_id=? WHERE university_id=? AND super_admin_id=?");
    $stmt->bind_param("ssiiii", $universityDomain, $universityName, $locationID, $superAdminID, $universityID, $currentUser);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }



    $result = '{"result": "University updated successfully."}';
    returnObject($result);
    return;

    function returnError ($error) {
        returnObject('{"error": "' . $error . '"}');
    }

    function returnObject ($target) {
        header('Content-type: application/json');
        echo $target;
    }
?>