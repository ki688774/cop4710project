<?php
    $inData = json_decode(file_get_contents('php://input'), true);

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $searchTerm = "%" . $inData["search"] . "%";

    if (!$currentUser) {
        returnError("You must be signed in to search events.");
        $stmt->close();
        $conn->close();
        return;
    }

    // Create and check connection
    try {
        $conn = mysqli_connect("localhost", "root", "", "cop4710project");
    } catch (Exception $e) {
        returnError($e);
        $stmt->close();
        $conn->close();
        return;
    }
    

    if (mysqli_connect_error()) {
        returnError("Could not connect to the server.");
        return;
    }



    // Find user's university (which must exist)
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
    $stmt->bind_param("i", $currentUser);

    try {
        $stmt->execute();
    } catch (Exception $e) {
        returnError($e);
        $stmt->close();
        $conn->close();
        return;
    }

    $universityID = $stmt->get_result()->fetch_assoc()["university_id"];





    // Search events
    $stmt = $conn->prepare("SELECT * FROM public_events WHERE event_name LIKE ?
        UNION SELECT * FROM P private_events WHERE event_name LIKE ? AND university_id = ?
        UNION SELECT * FROM E rso_events WHERE event_name LIKE ? AND EXISTS (SELECT * FROM R rso_joins WHERE E.rso_id=R.rso_id AND R.uid = ?)");
    $stmt->bind_param("ssisi", $searchTerm, $searchTerm, $universityID, $searchTerm, $currentUser);

    try {
        $stmt->execute();
    } catch (Exception $e) {
        returnError($e);
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
        returnError("No events found.");
        $stmt->close();
        $conn->close();
        return;
    }

    // Output locations
    $result = '{"result": ' . $rows . '}';
    returnObject($result);
    $stmt->close();
    $conn->close();
    return;


    function returnError ($error) {
        returnObject('{"error": "' . $error . '"}');
    }

    function returnObject ($target) {
        header('Content-type: application/json');
        echo $target;
    }
?>