<?php 

	require 'config/config.php';

    // DB Connection
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ( $mysqli->connect_errno ) {
        echo $mysqli->connect_error;
        exit();
    }

    $mysqli->set_charset('utf8');

    $eventId = $_GET['eventId'];

    $sql = "UPDATE events
		SET eventActive = '0' WHERE eventId = " . $eventId . ";";

    echo $sql;

	$results = $mysqli->query($sql);
    if(!$results) {
        echo $mysqli->error;
        exit();
    }


    $newUrl = "homepage.php";
    header('Location: ' . $newUrl);



    $mysqli->close();


?>