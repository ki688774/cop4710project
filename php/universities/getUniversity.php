<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    $universityDomain = $inData["university_domain"] ?? null;

    if (!$universityDomain) {
        returnError("University domain must be given.");
        return;
    }

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Get university
    $stmt = $conn->prepare("SELECT * FROM universities WHERE university_domain=?");
    $stmt->bind_param("s", $universityDomain);

    if (!attemptExecute($stmt, $conn))
        return;

    $targetRow = $stmt->get_result()->fetch_assoc();

    if (!$targetRow) {
        returnErrorAndClose("University not found.", $stmt, $conn);
        return;
    }



    // Return successful result
    returnObject(json_encode($targetRow));
    return;
?>