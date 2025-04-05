<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Proccess input
    $searchTerm = "%" . $inData["search"] . "%";

    // Create and check connection
    if (!attemptConnect($conn))
        return;



    // Add location
    $stmt = $conn->prepare("SELECT * FROM locations WHERE location_name LIKE ? OR address LIKE ?");
    $stmt->bind_param("ss", $searchTerm, $searchTerm);

    if (!attemptExecute($stmt, $conn))
        return;

    assembleJsonArrayFromQuery($stmt, $rows);



    // Return locations as result
    $result = '{"result": ' . $rows . '}';
    returnObject($result);
    return;
?>