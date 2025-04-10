<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $universityID = $inData["university_id"] ?? null;
    $universityDomain = $inData["university_domain"] ?? null;

    if (!$universityID && !$universityDomain) {
        returnError("University domain or ID must be given.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Get the university data from the university's ID.
    try {
        if ($universityID) {
            $stmt = $conn->prepare("SELECT * FROM universities WHERE university_id=?");
            $stmt->bind_param("i", $universityID);
        } else {
            // Get the university data from the university's email domain.
            $stmt = $conn->prepare("SELECT * FROM universities WHERE university_domain=?");
            $stmt->bind_param("s", $universityDomain);
        }
    } catch (Exception $error){
        returnMYSQLErrorAndClose($stmt, $conn);
        return;
    }

    if (!attemptExecute($stmt, $conn))
        return;

    $universityRow = $stmt->get_result()->fetch_assoc();

    if (!$universityRow) {
        returnErrorAndClose("University not found.", $stmt, $conn);
        return;
    }



    // Return successful result
    returnObject('{"result": ' . json_encode(expandLocationId($universityRow, $stmt, $conn)) . '}');
    return;
?>