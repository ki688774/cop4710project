<?php
    $mySQLHost = 'localhost';
    $mySQLUser = 'root';
    $mySQLPassword = '';
    $mySQLDatabase = 'cop4710project';

    // Create and check connection
    function attemptConnect () {
        try {
            return mysqli_connect($mySQLHost, $mySQLUser, $mySQLPassword, $mySQLDatabase);
        } catch (Exception $e) {
            returnError($e);
            return null;
        }
    }

    function returnError ($error) {
        returnObject('{"error": "' . $error . '"}');
    }

    function returnObject ($target) {
        header('Content-type: application/json');
        echo $target;
    }
?>