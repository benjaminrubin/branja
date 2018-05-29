<?php

<?php
	//verify that all required fields for creating a user were filled out


	if( !isset($_POST['title']) || empty($_POST['title']) ){
	
		$error = "Please fill out all required fields";

} else {
	//open connection with the database
	require 'config/config.php';

	//connect to database
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}

	$mysqli->set_charset('utf8');


//Initialize all the variables for the database
//remember to do SQL injection!!

// TITLE
	if( isset($_POST['title']) && !empty($_POST['title']) ){
		$title = $_POST['title'];
	} else{
		$title = "null";
	}
// DATE
	if( isset($_POST['date']) && !empty($_POST['date']) ){
		$date = $_POST['date'];
	} else {
		$date = "null";
	}
// AWARD
	if( isset($_POST['award']) && !empty($_POST['award']) ){
		$award = $_POST['award'];
	} else{
		$award = "null";
	}
// LABEL
	if( isset($_POST['label']) && !empty($_POST['label']) ){
		$label_id = $_POST['label'];
	} else {
		$label_id = "null";
	}
// SOUND
	if( isset($_POST['sound']) && !empty($_POST['sound']) ){
		$sound_id = $_POST['sound'];
	} else {
		$sound_id = "null";
	}

// GENRE
	if( isset($_POST['genre']) && !empty($_POST['genre']) ){
		$genre_id = $_POST['genre'];
	} else {
		$genre_id = "null";
	}
// RATING
	if( isset($_POST['rating']) && !empty($_POST['rating']) ){
		$rating_id = $_POST['rating'];
	} else {
		$rating_id = "null";
	}
// FORMAT
	if( isset($_POST['format']) && !empty($_POST['format']) ){
		$format_id = $_POST['format'];
	} else {
		$format_id = "null";
	}


	//date is in the format MM-DD-YYYY


	//generate SQL statement to insert a new user into the database
	$sql = "INSERT INTO dvd_titles (title, release_date, award, label_id, sound_id, genre_id, rating_id, format_id)
			VALUES ("
			. "'" . $title . "',"
			. "'" . $date . "',"
			. "'" . $award . "',"
			. $label_id . ","
			. $sound_id . ","
			. $genre_id . ","
			. $rating_id . ","
			. $format_id
			. ");";

	$results = $mysqli->query($sql);

	//check for errors with the sql statement
	if( $results == false ){
		echo $mysqli->error;
		exit();
	}

	//Clost the database connection
	$mysqli->close();


?>