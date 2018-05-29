<?php 

	require 'config/config.php';

    // DB Connection
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ( $mysqli->connect_errno ) {
        echo $mysqli->connect_error;
        exit();
    }

    $mysqli->set_charset('utf8');


    $userId = $_GET['userId'];
    $userProfileId = $_GET['userProfileId'];
    $action = $_GET['action'];


    if ( $action == "addFriend" ) {

    	$sql = "INSERT INTO friendships (userId1, userId2)
    	 VALUES ("
    	 . $userId . ", "
    	 . $userProfileId . ");";

    	 echo $sql;

        $results = $mysqli->query($sql);
        if(!$results) {
            echo $mysqli->error;
            exit();
        }

        echo "submitted friend request";

    }
    else if ( $action == "removeFriend" ) {

    	//first get the ids of the friendships
    	$sql = "SELECT * FROM friendships
    	 WHERE (userId1 = " . $userId
    	 . " AND " . "userId2 = " . $userProfileId . ") OR (userId1 = " . $userProfileId
    	 . " AND " . "userId2 = " . $userId . ");";

    	// echo $sql;

        $results = $mysqli->query($sql);
        if(!$results) {
            echo $mysqli->error;
            exit();
        }

        $row = $results->fetch_assoc();

        $friendshipId1 = $row['friendshipId'];

		$row = $results->fetch_assoc();	

		$friendshipId2 = $row['friendshipId'];

   		$sql = "DELETE FROM friendships WHERE friendshipId = " . $friendshipId1
   		. " OR friendshipId = " . $friendshipId2 . ";";

   		echo $sql;

		$results = $mysqli->query($sql);
        if(!$results) {
            echo $mysqli->error;
            exit();
        }

    	echo "Removed friend";

    
    }
	else if ( $action == "cancelRequest" ) {

	   	//first get the ids of the friendships
    	$sql = "SELECT * FROM friendships
    	WHERE userId1 = " . $userId
    	. " AND " . "userId2 = " . $userProfileId . ";";



       $results = $mysqli->query($sql);
        if(!$results) {
            echo $mysqli->error;
            exit();
        }

        $row = $results->fetch_assoc();

        $friendshipId = $row['friendshipId'];

        $sql = "DELETE FROM friendships WHERE friendshipId = " . $friendshipId . ";";

        $results = $mysqli->query($sql);
        if(!$results) {
            echo $mysqli->error;
            exit();
        }

        echo "Cancelled pending friend request";
    
    }
	else if ( $action == "acceptRequest" ) {


		$sql = "INSERT INTO friendships (userId1, userId2)
    	VALUES ("
    	. $userId . ", "
    	. $userProfileId . ");";


        $results = $mysqli->query($sql);
        if(!$results) {
            echo $mysqli->error;
            exit();
        }

		echo "accepted friend request";
    
    }

	else if ( $action == "denyRequest" ) {

		//Step 1: get the id of the friendship requestfrom the user profile

    	$sql = "SELECT * FROM friendships
    	WHERE userId1 = " . $userProfileId
    	. " AND " . "userId2 = " . $userId . ";";

    	$results = $mysqli->query($sql);
        if(!$results) {
            echo $mysqli->error;
            exit();
        }

        $row = $results->fetch_assoc();

        $friendshipId = $row['friendshipId'];

		$sql = "DELETE FROM friendships WHERE friendshipId = " . $friendshipId . ";";

		echo $sql;
        // $results = $mysqli->query($sql);
        // if(!$results) {
        //     echo $mysqli->error;
        //     exit();
        // }

		echo "denied friend request";
    
    }




$mysqli->close();

?>