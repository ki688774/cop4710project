<?php
    $inData = json_decode(file_get_contents('php://input'), true);

    $currentUser = $inData["current_user"] ?? null;
    $universityID = $inData["university_id"] ?? null;

    if (!$universityID || !$currentUser) {
        returnError("University ID and current user must be given.");
        return;
    }

    // Create and check connection
    try {
        $conn = mysqli_connect("localhost", "root", "", "cop4710project");
    } catch (Exception $e) {
        returnError($e);
        $conn->close();
        return;
    }



    // Check if university is accessible
    $stmt = $conn->prepare("SELECT * FROM universities WHERE university_id=? AND super_admin_id=?");
    $stmt->bind_param("ii", $universityID, $currentUser);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    $targetRow = $stmt->get_result()->fetch_assoc();

    if (!$targetRow) {
        returnError("University not found.");
        $stmt->close();
        $conn->close();
        return;
    }



    // Delete university
    $stmt = $conn->prepare("DELETE FROM universities WHERE university_id=? AND super_admin_id=?");
    $stmt->bind_param("ii", $universityID, $currentUser);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }



    $result = '{"result": "University deleted successfully."}';
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