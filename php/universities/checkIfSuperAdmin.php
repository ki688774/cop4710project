<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $currentUser = $inData["current_user"] ?? null;

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Check if university belongs to the user.
    try {
        $stmt = $conn->prepare("SELECT * FROM universities WHERE super_admin_id=?");
        $stmt->bind_param("i", $currentUser);
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $universityData = $stmt->get_result()->fetch_assoc();

    if (!$universityData) {
        returnErrorAndClose("The user is not a super-admin.", $stmt, $conn);
        return;
    }



    // Return successful result
    $result = '{"result": ' . json_encode($universityData) . '}';
    returnObject($result);
    return;
?>