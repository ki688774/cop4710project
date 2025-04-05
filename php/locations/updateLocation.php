<?php
    $inData = json_decode(file_get_contents('php://input'), true);
    require __DIR__ . '/../global.php';

    // Returns true on successful location update, false on failure.
    function updateLocation ($locationID, $locationName, $address, $longitude, $latitude, &$stmt, &$conn) {
        // Create and check connection
        if (!attemptConnect($conn))
            return false;
    
    
    
        // Check if location exists
        $stmt = $conn->prepare("SELECT * FROM locations WHERE location_id=?");
        $stmt->bind_param("i", $locationID);
    
        if (!attemptExecute($stmt, $conn))
            return false;
    
        if (!$stmt->get_result()->fetch_assoc()) {
            returnErrorAndClose("Location not found.", $stmt, $conn);
            return false;
        }
    
    
    
        // Update location
        $stmt = $conn->prepare("UPDATE locations SET location_name=?, address=?, longitude=?, latitude=? WHERE location_id=?");
        $stmt->bind_param("ssddi", $locationName, $address, $longitude, $latitude, $locationID);
    
        if (!attemptExecute($stmt, $conn))
            return false;
    
    
    
        // Return successful result
        return true;
    }
?>