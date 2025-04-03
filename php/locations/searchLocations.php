<?php
    $inData = json_decode(file_get_contents('php://input'), true);

    // Proccess input
    $searchTerm = "%" . $inData["search"] . "%";

    // Create and check connection
    try {
        $conn = mysqli_connect("localhost", "root", "", "cop4710project");
    } catch (Exception $e) {
        returnError($e);
        $conn->close();
        return;
    }



    // Add location
    $stmt = $conn->prepare("SELECT * FROM locations WHERE location_name LIKE ? OR address LIKE ?");
    $stmt->bind_param("ss", $searchTerm, $searchTerm);

    if (!$stmt->execute()) {
        returnError($stmt->error);
        $stmt->close();
        $conn->close();
        return;
    }

    $searchCount = 0;
    $rows = "[";
    $tempResult = $stmt->get_result();

    while ($row = $tempResult->fetch_assoc()) {
        if ($searchCount > 0)
            $rows .= ", ";

        $rows .= json_encode($row);
        $searchCount++;
    }

    $rows .= "]";

    if ($searchCount == 0) {
        returnError("No locations found.");
        $stmt->close();
        $conn->close();
        return;
    }

    // Output locations
    $result = '{"result": ' . $rows . '}';
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