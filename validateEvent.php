<?php 


require 'config/config.php';

    // DB Connection
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ( $mysqli->connect_errno ) {
        echo $mysqli->connect_error;
        exit();
    }


	if ( isset($_POST['eventName']) && !empty($_POST['eventName']) ){
	    $eventName = $mysqli->real_escape_string($_POST['eventName']); 
	}
	else {
	    $eventName = "null";
	}

	if ( isset($_POST['eventType']) && !empty($_POST['eventType']) ){
	    $eventType = $mysqli->real_escape_string($_POST['eventType']); 
	}
	else {
	    $eventType = "null";
	}
	
	if ( isset($_POST['eventTimeDate']) && !empty($_POST['eventTimeDate']) ){
	    $eventTimeDate = $mysqli->real_escape_string($_POST['eventTimeDate']); 
	}
	else {
	    $eventTimeDate = "null";
	}	
	
	if ( isset($_POST['description']) && !empty($_POST['description']) ){
	    $description = $mysqli->real_escape_string($_POST['description']); 
	}
	else {
	    $description = "null";
	}	
	
	if ( isset($_POST['eventImage']) && !empty($_POST['eventImage']) ){
	    $eventImage = $mysqli->real_escape_string($_POST['eventImage']); 
	}
	else {
	    $eventImage = "null";
	}
	
	if ( isset($_POST['eventAddress']) && !empty($_POST['eventAddress']) ){
	    $eventAddress = $mysqli->real_escape_string($_POST['eventAddress']); 
	}
	else {
	    $eventAddress = "null";
	}

	if ( isset($_POST['eventCoordinates']) && !empty($_POST['eventCoordinates']) ){
	    $eventCoordinates = $mysqli->real_escape_string($_POST['eventCoordinates']); 
	}
	else {
	    $eventCoordinates = "null";
	}


$sql = "INSERT into events (hostId, eventName, eventCoordinates, eventAddress, eventTimeDate, eventType, eventImage, description) VALUES ("
	. $_SESSION['userId']
	. ", "
	. "'" . $eventName . "', "
	. "'" . $eventCoordinates . "', "
	. "'" . $eventAddress . "', "
	. "'" . $eventTimeDate . "', "
	. "'" . $eventType . "', "
	. "'" .  $eventImage . "', "
	. "'" . $description
	. "');";

	echo $sql;

    $results = $mysqli->query($sql);
    if(!$results) {
        echo $mysqli->error;
        exit();
    }

    //add the current user as an attendee to this event

    $sql = "INSERT INTO eventAttendance (eventId, attendeeId) VALUES ("
    . $mysqli->insert_id . ", " . $_SESSION['userId'] . ");";


    $url = "eventPage.php?eventId=" . $mysqli->insert_id;
    header('Location: ' . $url);


?>