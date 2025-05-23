<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $rsoName = $inData["rso_name"] ?? null;
    $currentUser = $inData["current_user"] ?? null;

    if (!$currentUser || !$rsoName) {
        returnError("All fields must be filled.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;


    
    // Get the current user's university.
    try {
        $stmt = $conn->prepare("SELECT university_id FROM users WHERE uid=?");
        $stmt->bind_param("i", $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $universityID = $stmt->get_result()->fetch_assoc()["university_id"];

    if (!$universityID) {
        returnErrorAndClose("Could not find current user.", $stmt, $conn);
        return;
    }



    // Add RSO.
    try {
        $stmt = $conn->prepare("INSERT INTO rsos (university_id, rso_name, admin_id, active) VALUES (?,?,?,(0))");
        $stmt->bind_param("isi", $universityID, $rsoName, $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $rsoID = $conn->insert_id;

    // Make the admin join the RSO.
    try {
        $stmt = $conn->prepare("INSERT INTO rso_joins (uid, rso_id) VALUES (?,?)");
        $stmt->bind_param("ii", $currentUser, $rsoID);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;


    // Return successful result
    $result = '{"result": "RSO added successfully.", "rso_id":' . $rsoID . '}';
    returnObject($result);
    return;
?>