<?php
    $inData = json_decode(file_get_contents('php://input'), true);

    $universityDomain = $inData["university_domain"] ?? null;

    if (!$universityDomain) {
        returnError("University domain must be given.");
        return;
    }



    // Create and check connection
    $conn = mysqli_connect("localhost", "root", "", "cop4710project");

    if (!$conn) {
        returnError("Could not connect to the server.");
        return;
    }

    // Get university
    $stmt = $conn->prepare("SELECT * FROM universities WHERE university_domain=?");
    $stmt->bind_param("s", $universityDomain);

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





    returnObject(json_encode($targetRow));
    return;

    function returnError ($error) {
        returnObject('{"error": "' . $error . '"}');
    }

    function returnObject ($target) {
        header('Content-type: application/json');
        echo $target;
    }
?>