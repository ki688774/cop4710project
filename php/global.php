<?php
    define("MYSQLHOST", 'localhost');
    define("MYSQLUSER", 'root');
    define("MYSQLPASSWORD", '');
    define("MYSQLDATABASE", 'cop4710project');

    function attemptConnect (&$conn) {
        try {
            $conn = mysqli_connect(MYSQLHOST, MYSQLUSER, MYSQLPASSWORD, MYSQLDATABASE);
            return true;
        } catch (Exception $e) {
            returnError($e);
            return false;
        }
    
    }

    // Returns json array size, and stores the json array corresponding to what's in $stmt in $rows.
    function assembleJsonArrayFromQuery (&$stmt, &$rows) {
        $searchCount = 0;
        $rows = "[";
        $tempResult = $stmt->get_result();

        while ($row = $tempResult->fetch_assoc()) {
            if ($searchCount > 0)
                $rows .= ", ";

            $rows .= json_encode($row);
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
            returnErrorAndClose($error, $stmt, $conn);
            return false;
        }
    }

    function returnError ($error) {
        returnObject('{"error": "' . $error . '"}');
    }

    function returnErrorAndClose ($error, &$stmt, &$conn) {
        returnError($error);
        $stmt->close();
        $conn->close();
    }

    function returnObject ($target) {
        header('Content-type: application/json');
        echo $target;
    }
?>