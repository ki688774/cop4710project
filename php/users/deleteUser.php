<?php
    $inData = json_decode(file_get_contents('php://input'), true);

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;

    // Create and check connection
    $conn = mysqli_connect("localhost", "root", "", "cop4710project");

    if (!$conn) {
        returnError("Could not connect to the server.");
        return;
    }


    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE uid=?");
    $stmt->bind_param("i", $currentUser);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    if (!$stmt->get_result()->fetch_assoc()) {
        returnError("User not found.");
        $stmt->close();
        $conn->close();
        return;
    }



    // Delete user
    $stmt = $conn->prepare("DELETE FROM users WHERE uid=?");
    $stmt->bind_param("i", $currentUser);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }



    $result = '{"result": "User deleted successfully."}';
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