<?php

    require 'config/config.php';

    // DB Connection
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ( $mysqli->connect_errno ) {
        echo $mysqli->connect_error;
        exit();
    }

    $mysqli->set_charset('utf8');


    //If there is no eventId in the URL, then we don't have an event to look up
        if ( !isset($_GET['eventId']) && !empty($_GET['eventId']) ){
            $error = "No event here...";
        }

        //we do have an eventId in the URL
        else {

            $eventId = $_GET['eventId']; 
            //first check to see if the URL is valid
            $sql = "SELECT * FROM events WHERE eventId = " . $eventId . ";";

            $results = $mysqli->query($sql);
            if(!$results) {
                echo $mysqli->error;
                exit();
            }
            //if no results are returned, we have an invalid event Id
            if ( $results->num_rows == 0 ) {
                echo $mysqli->error;
                $error = "Event doesn't exist";
                $mysqli->close();
            }
            else{
                //event does exist, get all the details for the event
                $event = $results->fetch_assoc();

                $eventName = $event['eventName'];

                $hostId = $event['hostId'];
                //get the host's name
                $sql = "SELECT fullName FROM users WHERE userId = " . $hostId . ";";

                $results = $mysqli->query($sql);
                if(!$results) {
                    echo $mysqli->error;
                    exit();
                }

                $row = $results->fetch_assoc();
                $hostName = $row['fullName'];

                $eventType = $event['eventType'];
                
                //TIME ==========
                // reference: https://www.w3schools.com/php/func_date_date_format.asp
                $eventTimeDate = date_create($event['eventTimeDate']);
                $eventStartTime = date_format($eventTimeDate, "g:ia");
                $eventStartDate = date_format($eventTimeDate, "l F jS, Y");


                //TIME ==========
                
                $eventAddress = $event['eventAddress'];
                $eventCoordinates = $event['eventCoordinates'];
                $description = $event['description'];

                $eventImage = $event['eventImage'];

                //get the number of invited guests to this event
                //get the total number of attendees
                $sql = "SELECT COUNT(*) as attendeesNumber FROM eventAttendance WHERE eventId = " . $event['eventId'] . ";";

                $numbers = $mysqli->query($sql);
                if(!$numbers) {
                    echo $mysqli->error;
                    exit();
                }

                $row = $numbers->fetch_assoc();

                $totalAttendees = $row['attendeesNumber'];

                //get the status of whether or not the user has RSVP'd to the event


                //check if the current user is going to the event
                $sql = "SELECT * FROM eventAttendance WHERE eventId = " . $event['eventId'] . " AND attendeeId = " . $_SESSION['userId'] . ";";

                // echo "<br><br><br><br><br><br><br>";
                // echo $sql;

                $results = $mysqli->query($sql);
                if(!$results) {
                    echo $mysqli->error;
                    exit();
                }
                //if no results are returned, we have an invalid event Id
                if ( $results->num_rows != 0 ) {
                    $attending = true;
                }
                else {
                    $attending = false;
                }

            }
        }

    $mysqli->close();
?>


<!DOCTYPE html>
<html>

<head>
    <link rel="manifest" href="/manifest.json">
    <!-- <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="default_styles.css">
    <link rel="stylesheet" type="text/css" href="sidebar.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="navigationbar.css">
    <link rel="stylesheet" type="text/css" href="eventPage.css">
    <meta charset='utf-8'>
    <title>Branja - <?php echo $eventName ?></title>
</head>
<style type="text/css">
.sidenav a {
    text-decoration: none;
}
#cancelEvent {
    cursor: pointer;
    margin: auto auto;
}
</style>

<body>

<?php require "navigation.php"; ?>

        <!-- end of navigation bar -->
        <div class="body">
            <div class="container">
                <div class="eventBody">
                    <input type="button" onclick="location.href='homepage.php';" class="btn btn-back" value="&larr; back to map" />
                    <br>
                    <div class="eventImageHolder"><img src="<?php echo $event['eventImage']; ?>" alt="" class="eventImage"></div>
                    <div class="eventHeader">
                        <span class="eventTitle"><?php echo strtoupper($eventName);?></span>
                        <br>
                        <span class="eventHost">
                            Hosted by <a href="profilePage.php?userId=<?php echo $hostId; ?>" class="">
                                <?php if ( $_SESSION['userId'] == $hostId ){ echo "you";}
                                else {echo $hostName;}?></a> • <span style="color:#53c653"><?php echo $eventType; ?> event</span></span>
                    </div>

                <?php if( $_SESSION['userId'] != $hostId  ) : ?>

                    <div class="invitation">
                        <div id="attendance">
                            
                        <?php if( !$attending ) : ?>

                            <button id="attendanceButton" class="btn btn-success">
                                <span id="unconfirmed" style="display:inline">RSVP</span>
                                <span id="confirmed" style="display:none">&times; cancel RSVP</span>
                            </button>
                        
                        <?php else : ?>
                            
                            <button id="attendanceButton" class="btn btn-warning">
                                <span id="unconfirmed" style="display:none">RSVP</span>
                                <span id="confirmed" style="display:inline">&times; cancel RSVP</span>
                            </button>

                        <?php endif; ?>

                        </div>
                    </div>
                
                <?php else : ?>

                            <button id="attendanceButton" class="btn btn-success" style="display:none">
                                <span id="unconfirmed" style="display:inline">RSVP</span>
                                <span id="confirmed" style="display:none">&times; cancel RSVP</span>
                            </button>

                <?php endif; ?>
                    <hr>
                    <div class="eventDetails">
                        📍<?php echo $eventAddress; ?>
                        <br>🗓<?php echo $eventStartDate; ?>
                        <br> ⏰<?php echo $eventStartTime; ?>
                        <hr> ✅<?php echo $totalAttendees; ?> going
                        <br> 📩320 invited
                        <hr>
                        <?php echo $description; ?>
                    </div>

            <?php if ( $_SESSION['userId'] == $event['hostId'] ) : ?>
                <br>


                <div class="row">
                    <button class="btn btn-outline-danger text-center" id="cancelEvent">cancel event</button>    
                </div>
                
            <?php endif; ?>

                </div>
            </div>
        </div>
</body>
<script>

document.getElementById("attendanceButton").onclick = function() {

    //if the user is confirming attendance
    if( document.getElementById("confirmed").style.display == "none" ) {
        document.getElementById("confirmed").style.display = "inline";
        document.getElementById("unconfirmed").style.display = "none";
        document.getElementById("attendanceButton").classList.remove("btn-success");
        document.getElementById("attendanceButton").classList.add("btn-warning");

        ajaxGet( ('attendance.php?eventId=' + '<?php echo $event['eventId']; ?>' + '&action=confirm' ), function(results) {
        console.log(results);
        });

    }
    else if( document.getElementById("confirmed").style.display == "inline" ) {
        document.getElementById("confirmed").style.display = "none";
        document.getElementById("unconfirmed").style.display = "inline";
        document.getElementById("attendanceButton").classList.remove("btn-warning");
        document.getElementById("attendanceButton").classList.add("btn-success");
      
        ajaxGet( ('attendance.php?eventId=' + '<?php echo $event['eventId']; ?>' + '&action=cancel' ), function(results) {
        console.log(results);
        });

    }
}

 <?php if ( $_SESSION['userId'] == $event['hostId'] ) : ?>
document.getElementById("cancelEvent").onclick = function() {
    var confirmDelete = confirm("You are sure you want cancel this event?");

    if( confirmDelete ){
            
        var url = 'deleteEvent.php/eventId=' + <?php echo $event['eventId']; ?>;

        console.log(url);

        window.location.href = 'deleteEvent.php?eventId=' + <?php echo $event['eventId']; ?>;
    }
}
<?php endif; ?>

//============= AJAX calls for initiating event attendance =============


    function ajaxGet(endpointUrl, returnFunction) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', endpointUrl, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                if (xhr.status == 200) {
                    // returnFunction( xhr.responseText );

                    //results is actually coming back as a string. now we have to convert the strong to
                    // a json style object (or javascript object)

                    // Convert JSON string into JS objects
                    returnFunction(xhr.responseText);
                } else {
                    alert('AJAX Error.');
                    console.log(xhr.status);
                }
            }
        }
        xhr.send();
    };


</script>
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
function openNav() {
    console.log(document.getElementById("darkSideNav").style.visibility);
    document.getElementById("mySidenav").style.width = "250px";
    document.getElementById("darkSideNav").style.visibility = "visible";
    document.getElementById("darkSideNav").style.opacity = "0.5";
}

function closeNav() {
    console.log(document.getElementById("darkSideNav").style.visibility);
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("darkSideNav").style.opacity = "0";
    document.getElementById("darkSideNav").style.visibility = "hidden";
}
</script>

</html>