<?php
    function canEditEvent ($currentUser, $eventID, &$stmt, &$conn) {
        // Check if the event is a private event.
        try {
            $stmt = $conn->prepare("SELECT * FROM private_events WHERE event_id=?");
            $stmt->bind_param("i", $eventID);
        } catch (Exception $error){
            returnMYSQLErrorAndClose($stmt, $conn);
            return;
        }

        if (!attemptExecute($stmt, $conn))
            return false;

        $oldEntry = $stmt->get_result()->fetch_assoc();

        if ($oldEntry)
            return rsoBasedCheck($stmt, $conn);



        // Check if the event is an RSO event.
        try {
            $stmt = $conn->prepare("SELECT * FROM rso_events WHERE event_id=?");
            $stmt->bind_param("i", $eventID);
        } catch (Exception $error){
            returnMYSQLErrorAndClose($stmt, $conn);
            return;
        }

        if (!attemptExecute($stmt, $conn))
            return false;

        $oldEntry = $stmt->get_result()->fetch_assoc();

        if ($oldEntry)
            return rsoBasedCheck($stmt, $conn);



        // Check if the event is a public event.
        try {
            $stmt = $conn->prepare("SELECT * FROM public_events WHERE event_id=?");
            $stmt->bind_param("i", $eventID);
        } catch (Exception $error){
            returnMYSQLErrorAndClose($stmt, $conn);
            return;
        }

        if (!attemptExecute($stmt, $conn))
            return false;

        $oldEntry = $stmt->get_result()->fetch_assoc();

        if ($oldEntry) {
            try {
                $stmt = $conn->prepare("SELECT * FROM universities WHERE university_id=? AND super_admin_id=?");
                $stmt->bind_param("ii", $oldEntry["university_id"], $currentUser);
            } catch (Exception $error){
                returnMYSQLErrorAndClose($stmt, $conn);
                return;
            }

            if (!attemptExecute($stmt, $conn))
                return false;

            if (!$stmt->get_result()->fetch_assoc()) {
                returnErrorAndClose("User is not the super-admin of the event's university.", $stmt, $conn);
                return false;
            }

            return true;
        }    



        // If it's not in any of the specific event lists, then it doesn't exist.
        returnErrorAndClose("Event not found.", $stmt, $conn);
        return false;
    }
    



    // If the event is private or an RSO event, check if the current user is the admin.
    function rsoBasedCheck (&$stmt, &$conn) {
        global $currentUser, $oldEntry;
        
        try {
            $stmt = $conn->prepare("SELECT * FROM rso WHERE rso_id=? AND admin_id=?");
            $stmt->bind_param("ii", $oldEntry["rso_id"], $currentUser);
        } catch (Exception $error){
            returnMYSQLErrorAndClose($stmt, $conn);
            return;
        }

        if (!attemptExecute($stmt, $conn))
            return false;

        if (!$stmt->get_result()->fetch_assoc()) {
            returnErrorAndClose("User is not the admin of the RSO.", $stmt, $conn);
            return false;
        }

        return true;
    }
?>