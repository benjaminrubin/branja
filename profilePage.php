<?php
  require 'config/config.php';

  //if the session is not in play, take back to login page
  if( !isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] ){
    header('Location: login.php');
  }
  else {

    // DB Connection
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ( $mysqli->connect_errno ) {
            echo $mysqli->connect_error;
            exit();
        }

        $mysqli->set_charset('utf8');


    //Get the details of the user

        //if there is no userId, then 
        if ( isset($_GET['userId']) && !empty($_GET['userId']) ){
            $userId = $_GET['userId']; 
        }
        else {
            $userId = $_SESSION['userId'];
        }

        //although we have a userId, let's make sure it is a valid one

        $sql = "SELECT * FROM users WHERE userId = " . $userId;

        $results = $mysqli->query($sql);
        if(!$results) {
            echo $mysqli->error;
            exit();
        }
        //if no results are returned, we have an invalid user Id
        if ( $results->num_rows == 0 ) {
            echo $mysqli->error;
            $error = "Invalid user";
            $mysqli->close();
        }
        else {
            //we have a valid user Id
            // echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
                $user = $results->fetch_assoc();

                //Verify all the details from the profile

                //get the username
                $username = $user['username'];

                //get the full name
                $fullName = $user['fullName'];

                $userProfileId = $user['userId'];


                //get the image address
                if( isset($user['profileImage']) && !empty($user['profileImage']) ){
                    $profileImage = $user['profileImage'];    
                    // $profileImage = "https://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Al_Pacino_-_Hummel.jpg/220px-Al_Pacino_-_Hummel.jpg";    
                }
                else {
                    $profileImage = "http://hotchillitri.co.uk/wp-content/uploads/2016/10/empty-avatar.jpg";
                }


                //get the current city
                if( isset($user['currentCity']) && !empty($user['currentCity']) ){
                    $currentCity = $user['currentCity'];    
                }
                else {
                    $currentCity = null;
                }
                
                //get the profile URL
                if( isset($user['profileUrl']) && !empty($user['profileUrl']) ){

                    $profileUrl = $user['profileUrl'];    
                }
                else {
                    $profileUrl = null;
                }

                //get the biography
                if( isset($user['biography']) && !empty($user['biography']) ){
                    $biography = $user['biography'];    
                }
                else {
                    $biography = null;
                }



                //get the number of events that the user has attended

                $eventNumbers = "SELECT COUNT(*) as totalEvents FROM eventAttendance WHERE attendeeId = " . $userId . ";";

                $eventNumbers = $mysqli->query($eventNumbers);
                if(!$eventNumbers) {
                    echo $mysqli->error;
                    exit();
                }

                $row = $eventNumbers->fetch_assoc();

                $totalEvents = $row['totalEvents'];


                //get the number of friends the user has
                $friendships = "SELECT COUNT(*) as totalFriends FROM friendships WHERE userId1 = " . $userId . " OR userId2 = " . $userId . ";";
                
                $friendNumbers = $mysqli->query($friendships);
                if(!$friendNumbers) {
                    echo $mysqli->error;
                    exit();
                }

                $row = $friendNumbers->fetch_assoc();

                $totalFriends = floor($row['totalFriends'] / 2);

                //get all the events that the user is attending
                $sql = "SELECT * FROM events JOIN eventAttendance ON events.eventId = eventAttendance.eventId WHERE eventAttendance.attendeeId = " . $user['userId'] . " AND events.eventActive = 1;";

                $events = $mysqli->query($sql);
                if(!$results) {
                    echo $mysqli->error;
                    exit();
                }

                $myProfile = false;
                //check if this profile belongs to the user themselves
                if( $user['userId'] == $_SESSION['userId'] ) {
                    //it is the user's profile
                    $myProfile = true;
                }
                else {

                    // echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
                    //check the friend status
                    $sql = "SELECT * FROM friendships WHERE (userId1 = " . $_SESSION['userId']
                    . " AND userId2 = " . $user['userId'] . ") OR (userId1 = " . $user['userId'] . " AND userId2 = " . $_SESSION['userId'] . ");";
                    
                    $results = $mysqli->query($sql);
                    if(!$results) {
                        echo $mysqli->error;
                        exit();
                    }  

                    if ( $results->num_rows == 0 ) {
                        $friendshipStatus = "notFriends";
                    }
                    elseif ( $results->num_rows == 1 ) {
                        $row = $results->fetch_assoc();
                        if( $row['userId1'] == $_SESSION['userId'] ){
                            $friendshipStatus = "requestSent";
                        }
                        else {
                            $friendshipStatus = "acceptRequest";
                        }
                    }
                    elseif ( $results->num_rows == 2 ) {
                        $friendshipStatus = "friends";
                    }

                }

            }

            $mysqli->close();


  }

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
    <link rel="stylesheet" type="text/css" href="profilePage.css">
    <link rel="stylesheet" type="text/css" href="eventCard.css">
    <meta charset='utf-8'>
    <title>Branja - Event Page</title>
</head>
<style type="text/css">
.sidenav a {
    text-decoration: none;
}
.btn {
    cursor: pointer;
    transition: .1s;
}



</style>

<body>

<?php require "navigation.php"; ?>


        <!-- end of navigation bar -->
        <!-- start of user's profile -->
        <div class="body">
            <div class="container">
                <div class="profileBody">
                    <div class="profileTop">
                        <div class="profileTopSection">
                            <img src="<?php echo $profileImage; ?>" class="profileImage">
                        </div>
                        <div class="profileTopSection">
                            <span class="username"><?php echo $user['username']; ?></span>
                            <br>
                            <span class="fullName"><?php echo $user['fullName']; ?></span>
                            <br>
                            <div class="profileTopSectionButtons">
                                <!-- if it's the user's profile -->
                                <?php if ($myProfile) : ?>

                                    <!-- primaryActionButton -->
                                    <button class="btn btn-outline-secondary profileButton" id="mainActionButton" onclick="updateButtonText();" onmouseleave="revert();">

                                    <span id="editProfile" style="display:inline">edit profile </span>
        
                                    <span id="addFriend" style="display:none">add friend</span>
                                    <span id="friends" style="display:none">✔ friends </span>
                                    <span id="requestSent" style="display:none">&times; request sent</span>
                                    <span id="acceptRequest" style="display:none">accept request</span>

                                    </button>
                                    <!-- toggleSecondaryActionButtons -->
                                    <button class="btn btn-warning profileButton" id="toggleSecondaryActionButtons" onclick="toggleSecondary();" style="display:none"> &#9660;</button>
                                    <!-- secondaryActionButton -->
                                    <div id="dropdownContainer" style="display:none">
                                        <button class="btn btn-danger profileButton" id="secondaryActionButton"">
                                            <span id="removeFriend" style="display:none">&times; remove friend</span>
                                            <span id="denyRequest" style="display:none">deny request</span>
                                        </button>
                                    </div>

                                    <!-- if the user and the profile are friends -->
                                <?php elseif ( $friendshipStatus == "friends" ) : ?>
                                    <button class="btn btn-success profileButton" id="mainActionButton" onmouseover="updateButtonText();" onmouseleave="revert();">

                                    <span id="addFriend" style="display:none">add friend</span>
                                    <span id="friends" style="display:inline">✔ friends </span>
                                    <span id="editProfile" style="display:none">edit profile </span>
                                    <span id="requestSent" style="display:none">&times; request sent</span>
                                    <span id="acceptRequest" style="display:none">accept request</span>

                                    </button>
                                    <!-- toggleSecondaryActionButton -->
                                    <button class="btn btn-success profileButton" id="toggleSecondaryActionButtons" onclick="toggleSecondary();" style="display:inline"> &#9660;</button>
                                    <!-- secondaryActionButton -->
                                    <div id="dropdownContainer" style="display:none">
                                        <button class="btn btn-danger profileButton" id="secondaryActionButton"">
                                            <span id="removeFriend" style="display:inline">&times; remove friend</span>
                                            <span id="denyRequest" style="display:none">&times; deny request</span>
                                        </button>
                                    </div>

                                    <!-- if the user and the profile are not friends -->
                                <?php elseif ( $friendshipStatus == "notFriends" ) : ?>
                                    <button class="btn btn-primary profileButton" id="mainActionButton" onmouseover="updateButtonText();" onmouseleave="revert();">

                                    <span id="addFriend" style="display:inline">add friend</span>
                                    <span id="friends" style="display:none">✔ friends </span>
                                    <span id="editProfile" style="display:none">edit profile </span>
                                    <span id="requestSent" style="display:none">&times; request sent</span>
                                    <span id="acceptRequest" style="display:none">accept request</span>

                                    </button>
                                    <!-- toggleSecondaryActionButton -->
                                    <button class="btn btn-warning profileButton" id="toggleSecondaryActionButtons" onclick="toggleSecondary();" style="display:none"> &#9660;</button>
                                    <!-- secondaryActionButton -->
                                    <div id="dropdownContainer" style="display:none">
                                        <button class="btn btn-danger profileButton" id="secondaryActionButton"">
                                            <span id="removeFriend" style="display:none">&times; remove friend</span>
                                            <span id="denyRequest" style="display:inline">&times; deny request</span>
                                        </button>
                                    </div>
                                    
                                    <!-- the user is waiting for their friend request to be accepted -->
                                <?php elseif ( $friendshipStatus == "requestSent" ) : ?>
                                    <button class="btn btn-secondary profileButton" id="mainActionButton" onmouseover="updateButtonText();" onmouseleave="revert();">

                                    <span id="addFriend" style="display:none">add friend</span>
                                    <span id="friends" style="display:none">✔ friends </span>
                                    <span id="editProfile" style="display:none">edit profile </span>
                                    <span id="requestSent" style="display:inline">&times; request sent</span>
                                    <span id="acceptRequest" style="display:none">accept request</span>

                                    </button>
                                    <!-- toggleSecondaryActionButton -->
                                    <button class="btn btn-warning profileButton" id="toggleSecondaryActionButtons" onclick="toggleSecondary();" style="display:none"> &#9660;</button>
                                    <!-- secondaryActionButton -->
                                    <div id="dropdownContainer" style="display:none">
                                        <button class="btn btn-danger profileButton" id="secondaryActionButton"">
                                            <span id="removeFriend" style="display:none">&times; remove friend</span>
                                            <span id="denyRequest" style="display:inline">&times; deny request</span>
                                        </button>
                                    </div>

                                    <!-- the user needs to confirm a friend request -->
                                <?php elseif ( $friendshipStatus == "acceptRequest" ) : ?>
                                    <!-- primaryActionButton -->
                                    <button class="btn btn-warning profileButton" id="mainActionButton" onmouseover="updateButtonText();" onmouseleave="revert();">

                                    <span id="editProfile" style="display:none">edit profile </span>
                                    <span id="addFriend" style="display:none">add friend</span>
                                    <span id="friends" style="display:none">✔ friends </span>
                                    <span id="requestSent" style="display:none">&times; request sent</span>
                                    <span id="acceptRequest" style="display:inline">accept request</span>

                                    </button>
                                    <!-- toggleSecondaryActionButton -->
                                    <button class="btn btn-warning profileButton" id="toggleSecondaryActionButtons" onclick="toggleSecondary();" style="display:inline"> &#9660;</button>
                                    <!-- secondaryActionButton -->
                                    <div id="dropdownContainer" style="display:none">
                                        <button class="btn btn-danger profileButton" id="secondaryActionButton"">
                                            <span id="removeFriend" style="display:none">&times; remove friend</span>
                                            <span id="denyRequest" style="display:inline">&times; deny request</span>
                                        </button>
                                    </div>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="profileDetails">
                        📍<?php echo $user['currentCity']; ?>
                        <br> 🔗<a href="<?php echo $profileUrl; ?>" target="_blank"><?php echo $profileUrl; ?></a>
                        <br> 📣<?php echo $user['biography']; ?>
                    </div>
                    <hr>
                    <div class="profileNumbers">
                        <div class="numberBox"><span class="number"><?php echo $totalEvents; ?></span> events</div>
                        <div class="numberBox"><span class="number">0</span> branjas</div>
                        <div class="numberBox"><span class="number"><?php echo $totalFriends; ?></span> friends</div>
                    </div>
                    <hr>
                    <div class="upcomingEvents">
                        <span class="upcomingEventsHeader"><?php echo $user['username'];?>'s upcoming events</span>
                        <div id="eventListBottom">
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>
<script>
// =============== DUMMY OBJECTS FOR TESTING ===============
var user = {
    username: "intremelo",
    fullName: "Michael Rubin",
    currentCity: "Los Angeles",
    url: "google.com",
    bio: "Hey I'm Mike!",
    friendCount: "123",
    eventCount: "14"
};

var friends = [{
        username: "bennyruby",
        fullName: "Benjamin Rubin",
        userId: "12"
    },
    {
        username: "helen",
        fullName: "Helen Rubin",
        userId: "14"
    },
    {
        username: "joey",
        fullName: "Joey Rubin",
        userId: "15"
    }

];

var attendedEvents = [{
        eventId: "1",
        eventName: "Helen's Bday Party"
    },
    {
        eventId: "2",
        eventName: "Michael's Bday Party"
    }
];

 //event array
        // var events = [{
        //         eventId: "1",
        //         eventName: "Helen's Bday Party",
        //         eventType: "private",
        //         eventAddress: "702 W 30th Street, CA 90007",
        //         eventDate: "March 19th at 7:00 PM",
        //         eventAttendees: "100",
        //         eventImage: "https://scontent-lax3-1.xx.fbcdn.net/v/t1.0-9/30727751_10213997511185563_3766213558656827392_o.jpg?_nc_cat=0&oh=994d2510d4e73c87b0949f3a6466a196&oe=5B67CDEC",
        //         latitude: "34.025721",
        //         longitude: "-118.280009"
        //     },
        //     {
        //         eventId: "2",
        //         eventName: "Michael's Bday Party",
        //         eventType: "open",
        //         eventAddress: "702 W 34th Street, CA 90007",
        //         eventDate: "March 29th at 7:00 PM",
        //         eventAttendees: "200",
        //         eventImage: "https://vegaspartyvip.com/wp-content/uploads/2013/02/rehab_party1.jpeg",
        //         latitude: "34.041266",
        //         longitude: "-118.269530"
        //     }
        // ];

        var events = [];

        <?php while ( $row = $events->fetch_assoc() ) : ?>

        coordinates = "<?php echo $row['eventCoordinates']; ?>";
        if(coordinates != "null"){
            coordinates = coordinates.substring(1, coordinates.length-1);
            coordinates = coordinates.split(',');
            coordinates[0] = parseFloat(coordinates[0].trim());
            coordinates[1] = parseFloat(coordinates[1].trim());
        }

        event = {
            eventId: "<?php echo $row['eventId'];?>",
            eventName: "<?php echo $row['eventName'];?>",
            eventType: "<?php echo $row['eventType'];?>",
            eventAddress: "<?php echo $row['eventAddress'];?>",
            eventDate: "<?php
            
            $eventTimeDate = date_create($row['eventTimeDate']);
            $eventStartTime = date_format($eventTimeDate, "g:i A");
            $eventStartDate = date_format($eventTimeDate, "l, M jS");
            
            echo $eventStartDate . " at " . $eventStartTime;
                
            ?>",
            eventAttendees: "100",
            eventImage: "<?php echo $row['eventImage'];?>",
            latitude: coordinates[0],
            longitude: coordinates[1],
            eventLink: "eventPage.php?eventId=" + "<?php echo $row['eventId'];?>"
        };

        events.push(event);

        <?php endwhile; ?>


        console.log(events);




 for (var i = 0; i < events.length; i++) {

            //Step 1: add the event's marker to the map
            var eventLoc = { lat: parseFloat(events[i].latitude), lng: parseFloat(events[i].longitude) };

            //Step 2: add the event to the eventList
            createEventCard(events[i], (i), "eventList", "profilePage");

        }



        //This function generates an Event Card, whether in the eventList or as an info window on the map
        function createEventCard(event, eventNumber, destination, type) {
            //get event number
            console.log("event number is: " + eventNumber);
            //get the text associated with that number
            console.log(event);

            //create the event's image
            var eventSectionImage = document.createElement("div");
            eventSectionImage.classList.add("eventSection");

            var eventImg = document.createElement("img");
            eventImg.classList.add("eventListItemImage");
            eventImg.src = event.eventImage;

            eventSectionImage.appendChild(eventImg);

            //create the event's description description
            var eventSectionDes = document.createElement("div");
            eventSectionDes.classList.add("eventDescription");
            eventSectionDes.innerHTML = "<strong>" + event.eventName.toUpperCase() + "</strong> <p>" + event.eventAddress + "<br>" + event.eventDate + "<br>" + event.eventAttendees + " people going" + "</p>";


            //create the event's marker
            var eventSectionMarker = document.createElement("div");
            eventSectionMarker.classList.add("eventSectionMarker");

            var markerImg = document.createElement("img");
            markerImg.classList.add("eventListMarker");
            var imgsrc = "images/eventListMarkers/eventListMarker_";
            if (event.eventType === "private") {
                imgsrc += "private.png";
            } else if (event.eventType === "open") {
                imgsrc += "open.png"
            }

            markerImg.src = imgsrc;

            //create the event list marker

            //start off with the number
            var eventListMarkerNumber = document.createElement("div");
            eventListMarkerNumber.classList.add("eventListMarkerNumber");
            eventListMarkerNumber.innerHTML = (eventNumber + 1);

            //create event type
            var eventType = document.createElement("span");
            eventType.innerHTML = event.eventType;

            eventSectionMarker.appendChild(markerImg);
            //this needs to be adjusted for future events
            if(type != "profilePage"){
                eventSectionMarker.appendChild(eventListMarkerNumber);    
            }
            eventSectionMarker.appendChild(eventType);

            //append all three sections to the element
            var eventListItem = document.createElement("div");
            eventListItem.classList.add("eventListItem");

            eventListItem.appendChild(eventSectionImage);
            eventListItem.appendChild(eventSectionDes);
            eventListItem.appendChild(eventSectionMarker);


            //add event listener

            eventListItem.addEventListener('click', function(){
              var url = "eventPage.php?eventId=" + event.eventId;
              window.location = url;
            });

            if (destination === "map") {
                document.getElementById("infoWindow").appendChild(eventListItem);
            } else if (destination === "eventList") {
                document.getElementById("eventListBottom").appendChild(eventListItem);
            }
        }




//========================== Profile Buttons Code ==========================
document.getElementById("mainActionButton").onclick = function() {

    // Case 1: users are not friends, the user is requesting friendship from the profileUser
     if (document.getElementById("addFriend").style.display === "inline") {
        document.getElementById("addFriend").style.display = "none";
        document.getElementById("requestSent").style.display = "inline";
        document.getElementById("mainActionButton").classList.remove("btn-primary");
        document.getElementById("mainActionButton").classList.add("btn-secondary");

        ajaxGet( ('friendRequests.php?userId=' + '<?php echo $_SESSION['userId']; ?>' + '&userProfileId=' + '<?php echo $userProfileId;?>' + '&action=' + "addFriend" ), function(results) {
            console.log(results);
        });
    }
    // Case 2: user had already sent a friend request and is cancelling it
    else if (document.getElementById("requestSent").style.display === "inline") {
        document.getElementById("requestSent").style.display = "none";
         document.getElementById("addFriend").style.display = "inline";   
         document.getElementById("mainActionButton").classList.remove("btn-secondary");
        document.getElementById("mainActionButton").classList.add("btn-primary");

        ajaxGet( ('friendRequests.php?userId=' + '<?php echo $_SESSION['userId']; ?>' + '&userProfileId=' + '<?php echo $userProfileId;?>' + '&action=' + "cancelRequest" ), function(results) {
            console.log(results);
        });
        console.log("cancelled request");
    }
    // Case 3: user received a request from userProfile and is accepting it
    else if (document.getElementById("acceptRequest").style.display === "inline") {

        //Hide the accept Request
        document.getElementById("acceptRequest").style.display = "none";
        //show the friends
        document.getElementById("friends").style.display = "inline";   
        document.getElementById("mainActionButton").classList.remove("btn-warning");
        document.getElementById("mainActionButton").classList.add("btn-success");

        //change the color of the secondary button
        document.getElementById("toggleSecondaryActionButtons").classList.remove("btn-warning");
        document.getElementById("toggleSecondaryActionButtons").classList.add("btn-success");
        document.getElementById("denyRequest").style.display = "none";
        document.getElementById("removeFriend").style.display = "inline";
        document.getElementById("dropdownContainer").style.display = "none";

        ajaxGet( ('friendRequests.php?userId=' + '<?php echo $_SESSION['userId']; ?>' + '&userProfileId=' + '<?php echo $userProfileId;?>' + '&action=' + "acceptRequest" ), function(results) {
            console.log(results);
        });
    }
    // Case 4: users are friends, just toggle the menu
    else if (document.getElementById("friends").style.display === "inline"){

        toggleSecondary();
        
    }
    
    // Case 5: user sent a request to profileUse in the past, and is cancelling their request
    else if (document.getElementById("requestSent").style.display === "inline") {
        document.getElementById("requestSent").style.display = "none";
         document.getElementById("addFriend").style.display = "inline";   
         document.getElementById("mainActionButton").classList.remove("btn-secondary");
        document.getElementById("mainActionButton").classList.add("btn-primary");

        ajaxGet( ('friendRequests.php?userId=' + '<?php echo $_SESSION['userId']; ?>' + '&userProfileId=' + '<?php echo $userProfileId;?>' + '&action=' + "cancelRequest" ), function(results) {
            console.log(results);
        });
    }

    // Case 6: it is the user's profile
    else if (document.getElementById("editProfile").style.display === "inline") {
        var url = "editProfile.php";
        window.location = url;
    }
}   

//secondary button functions
document.getElementById("secondaryActionButton").onclick = function() {
    // Case 1: user received friend request from userProfile. They are now denying the request
    if (document.getElementById("denyRequest").style.display == "inline"){

        var confirmDelete = confirm("You are sure you want to cancel " + "<?php echo $user['fullName'];?>" + "'s friend request?");
        if (confirmDelete == true) {

           //first hide the toggle button for secondaryActionButtons
            document.getElementById("toggleSecondaryActionButtons").style.display = "none";
            //hide also the container
            document.getElementById("dropdownContainer").style.display = "none";

            //hide the accept request
            document.getElementById("acceptRequest").style.display = "none";
            //show the add friend button
            document.getElementById("addFriend").style.display = "inline";
            //remove the warning class from the mainaction buton and add the primary class
            document.getElementById("mainActionButton").classList.remove("btn-warning");
            document.getElementById("mainActionButton").classList.add("btn-primary");
            //
            ajaxGet( ('friendRequests.php?userId=' + '<?php echo $_SESSION['userId']; ?>' + '&userProfileId=' + '<?php echo $userProfileId;?>' + '&action=' + "denyRequest" ), function(results) {
            console.log(results);
        });

        }
    }
    // Case 2: user is removing the userProfile from their friend list
    else if (document.getElementById("removeFriend").style.display == "inline"){
        //First confirm that they want to remove the friend
        var confirmDelete = confirm("You are sure you want remove " + "<?php echo $user['fullName'];?>" + " from your friends?");
        if (confirmDelete == true) {

           //first hide the toggle button for secondaryActionButtons
            document.getElementById("toggleSecondaryActionButtons").style.display = "none";
            //hide also the container
            document.getElementById("dropdownContainer").style.display = "none";

            //hide the friends display
            document.getElementById("friends").style.display = "none";
            //show the add friend button
            document.getElementById("addFriend").style.display = "inline";
            //remove the warning class from the mainaction buton and add the primary class
            document.getElementById("mainActionButton").classList.remove("btn-success");
            document.getElementById("mainActionButton").classList.add("btn-primary");
            //
            ajaxGet( ('friendRequests.php?userId=' + '<?php echo $_SESSION['userId']; ?>' + '&userProfileId=' + '<?php echo $userProfileId;?>' + '&action=' + "removeFriend" ), function(results) {
            console.log(results);
        });
        }
    }
}


function updateButtonText(){
    // if (document.getElementById("friends").style.display === "inline"){
    //     document.getElementById("friends").style.display = "none";
    //     document.getElementById("removeFriend").style.display = "inline";
    //     document.getElementById("mainActionButton").classList.remove("btn-success");
    //     document.getElementById("mainActionButton").classList.add("btn-warning");
    // }
}

function revert() {
    // if(document.getElementById("removeFriend").style.display === "inline"){
    //     document.getElementById("removeFriend").style.display = "none";
    //     document.getElementById("friends").style.display = "inline";
    //     document.getElementById("mainActionButton").classList.remove("btn-warning");
    //     document.getElementById("mainActionButton").classList.add("btn-success");
    // }
}

function toggleSecondary() {
    if (document.getElementById('dropdownContainer').style.display == "none"){
        document.getElementById('dropdownContainer').style.display = "inline";
    }
    else {
        document.getElementById('dropdownContainer').style.display = "none";   
    }
}


//============= AJAX calls for handling friend requests, request cancellations, and request confirmations =============


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