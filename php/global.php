<?php
    define("MYSQLHOST", 'localhost');
    define("MYSQLUSER", 'root');
    define("MYSQLPASSWORD", '');
    define("MYSQLDATABASE", 'cop4710project');

    // Tries to connect to the mySQL server with the above credentials. Returns true on success, false on failure.
    function attemptConnect (&$conn) {
        try {
            $conn = mysqli_connect(MYSQLHOST, MYSQLUSER, MYSQLPASSWORD, MYSQLDATABASE);
            return true;
        } catch (Exception $e) {
            returnMYSQLError($conn);
            return false;
        }
    
    }

    // Returns json array size, and stores the json array corresponding to what's in $stmt in $rows.
    function assembleJsonArrayFromQuery (&$stmt, &$conn, &$rows) {
        $searchCount = 0;
        $rows = "[";
        $tempResult = $stmt->get_result();

        while ($row = $tempResult->fetch_assoc()) {
            if ($searchCount > 0)
                $rows .= ", ";

            $rows .= json_encode(expandLocationID($row, $stmt, $conn));
            $searchCount++;
        }

        $rows .= "]";
        return $searchCount;
    }

    // Tries to execute the given statement. Returns true on success, false on failure.
    function attemptExecute (&$stmt, &$conn) {
        try {
            $stmt->execute();
            return true;
        } catch (Exception $error) {
            returnMYSQLErrorAndClose($stmt, $conn);
            return false;
        }
    }

    // Given an array that contains a location_id field, it returns an array where the field with the actual location data.
    function expandLocationId ($targetArray, &$stmt, &$conn) {
        if (!array_key_exists("location_id", $targetArray))
            return $targetArray;

        // Get the location data.
        $stmt = $conn->prepare("SELECT * FROM locations WHERE location_id=?");
        $stmt->bind_param("i", $targetArray["location_id"]);

        $backupConn = $conn;

        if (!attemptExecute($stmt, $conn)) {
            $conn = $backupConn;
            return $targetArray;
        }

        $locationRow = $stmt->get_result()->fetch_assoc();



        // Return the combined array.
        return array_diff_key(array_merge($targetArray, $locationRow), ["location_id" => "a"]);
    }



    function returnError ($error) {
        returnObject('{"error": "' . $error . '"}');
    }

    function returnErrorAndClose ($error, &$stmt, &$conn) {
        returnError($error);
        $stmt->close();
        $conn->close();
    }

    function returnMYSQLError (&$conn) {
        returnObject('{"error": "' . $conn->error . '"}');
    }

    function returnMYSQLErrorAndClose (&$stmt, &$conn) {
        returnMYSQLError($conn);
        $stmt->close();
        $conn->close();
    }

    function returnObject ($target) {
        header('Content-type: application/json');
        echo $target;
    }
?>