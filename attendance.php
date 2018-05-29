<?php 

	require 'config/config.php';

    // DB Connection
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ( $mysqli->connect_errno ) {
        echo $mysqli->connect_error;
        exit();
    }

    $mysqli->set_charset('utf8');

    $userId = $_SESSION['userId'];
    $eventId = $_GET['eventId'];
    $action = $_GET['action'];


    if ( $action == "confirm" ) {

        $sql = "INSERT INTO eventAttendance (eventId, attendeeId) VALUES"
        . "(" . $eventId . ", " . $userId . ");";

        echo $sql;

        $results = $mysqli->query($sql);
        if(!$results) {
            echo $mysqli->error;
            exit();
        }

    }

    else if ( $action == "cancel" ) {

        $sql = "SELECT * FROM eventAttendance WHERE eventId = " . $eventId . " AND attendeeId = " . $userId . ";";

        echo $sql;

        $results = $mysqli->query($sql);
        if(!$results) {
            echo $mysqli->error;
            exit();
        }

        $row = $results->fetch_assoc();

        $attendanceId = $row['attendanceId'];

        $sql = "DELETE FROM eventAttendance WHERE attendanceId = " . $attendanceId . ";";

       $results = $mysqli->query($sql);
        if(!$results) {
            echo $mysqli->error;
            exit();
        }  
    }


$mysqli->close()
?>
