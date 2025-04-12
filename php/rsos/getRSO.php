<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;
    $rsoID = $inData["rso_id"] ?? null;

    if (!$currentUser) {
        returnError("You must be signed in to view events.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;

    

    // Find user's university (which must exist)
    $stmt = $conn->prepare("SELECT * FROM users WHERE uid=?");
    $stmt->bind_param("i", $currentUser);

    if (!attemptExecute($stmt, $conn))
        return;

    $universityID = $stmt->get_result()->fetch_assoc()["university_id"];

    


    try {
        $stmt = $conn->prepare("SELECT * FROM rsos R WHERE R.rso_id=? AND R.university_id=?");
        $stmt->bind_param("ii", $rsoID, $universityID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $row = $stmt->get_result()->fetch_assoc();


    // Return events as result
    $result = '{"result": ' . json_encode($row) . '}';
    returnObject($result);
    $stmt->close();
    $conn->close();
    return;
?>