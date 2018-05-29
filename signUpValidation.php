<?php 
	//first get the variables
	if ( !isset($_POST['username']) || 
	empty($_POST['username']) || 
	!isset($_POST['password']) || 
	empty($_POST['password']) || 
	!isset($_POST['email']) || 
	empty($_POST['email']) || 
	!isset($_POST['fullName']) || 
	empty($_POST['fullName']) || 
	!isset($_POST['genderId']) || 
	empty($_POST['genderId']) ) {

	echo "Error with form input";
}
else {

		require 'config/config.php';

		// DB Connection
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->connect_errno ) {
			echo $mysqli->connect_error;
			exit();
		}

		$mysqli->set_charset('utf8');


		//Store all the values from the form in variables
		if ( isset($_POST['username']) && !empty($_POST['username']) ){
			$username = $_POST['username'];	
		}
		else {
			$username = "null";
		}

		if ( isset($_POST['password']) && !empty($_POST['password']) ){
			$password = $_POST['password'];	
		}
		else {
			$password = "null";
		}

		if ( isset($_POST['email']) && !empty($_POST['email']) ){
			$email = $_POST['email'];	
		}
		else {
			$email = "null";
		}

		if ( isset($_POST['fullName']) && !empty($_POST['fullName']) ){
			$fullName = $_POST['fullName'];	
		}
		else {
			$fullName = "null";
		}

		if ( isset($_POST['genderId']) && !empty($_POST['genderId']) ){
			$genderId = $_POST['genderId'];	
		}
		else {
			$genderId = "null";
		}


		//Password adjustment

		$password = "branja" . $password;

		$password = hash('sha256', $password);

		echo $password;



		//generate an INSERT statement for the database
		$sql = " INSERT INTO users(username, password, primaryEmail, fullName, genderId)
		    		VALUES('"

		    			. $username
		    			. "',"
			    		. "'" . $password
			    		. "',"
			    		. "'" . $email
			    		. "',"
			    		. "'" . $fullName
			    		. "',"
			    		. $genderId
		    		. ");";


		//print out the INSERT statement
		echo "<hr>" . $sql . "<hr>";

		//send the statement to the database

		$results = $mysqli->query($sql);
    	if(!$results) {
    		echo $mysqli->error;
    		exit();
    	}

    	$newUrl = "homepage.php";
    	//forward to homepage
    	header('Location: ' . $newUrl);

		//close database
		$mysqli->close();
}
	
?>