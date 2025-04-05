<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $universityDomain = $inData["university_domain"] ?? null;

    if (!$universityDomain) {
        returnError("University domain must be given.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Get the university data.
    $stmt = $conn->prepare("SELECT * FROM universities WHERE university_domain=?");
    $stmt->bind_param("s", $universityDomain);

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