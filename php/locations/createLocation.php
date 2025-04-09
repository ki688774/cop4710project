<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Returns true on successful location creation, false on failure.
    function createLocation ($locationName, $address, $longitude, $latitude, &$stmt, &$conn) {
        // Create and check connection
        if (!attemptConnect($conn))
            return false;
    
    
    
        // Add location
        try {
            $stmt = $conn->prepare("INSERT INTO locations (location_name, address, longitude, latitude) VALUES (?,?,?,?)");
            $stmt->bind_param("ssdd", $locationName, $address, $longitude, $latitude);
        } catch (Exception $error){
            returnMYSQLErrorAndClose($stmt, $conn);
            return;
        }
    
        if (!attemptExecute($stmt, $conn))
            return false;
    
    
    
        // Return successful result
        return true;
    }
?>