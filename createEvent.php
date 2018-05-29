<?php
    

    require 'config/config.php';

    // DB Connection
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ( $mysqli->connect_errno ) {
        echo $mysqli->connect_error;
        exit();
    }

//     $mysqli->set_charset('utf8');


// //Store all the values from the form in variables
//         if ( isset($_POST['username']) && !empty($_POST['username']) ){
//             $username = $_POST['username']; 


//             //check if the username is taken

//             $sql = "SELECT * FROM users WHERE username = '" . $username . "';";

//             $results = $mysqli->query($sql);
//             if(!$results) {
//                 echo $mysqli->error;
//                 exit();
//             }

//             $numresults = $results->num_rows;
            
//             if( $numresults != 0 ) {
//                 $error = "Username taken";
//             }
//         }
//         else {
//             $username = "null";
//         }

//         if ( isset($_POST['password']) && !empty($_POST['password']) ){
//             $password = $_POST['password']; 
//         }
//         else {
//             $password = "null";
//         }

//         if ( isset($_POST['email']) && !empty($_POST['email']) ){
//             $email = $_POST['email']; 


//              //check if the email is taken

//             $sql = "SELECT * FROM users WHERE primaryEmail = '" . $email . "';";

//             $results = $mysqli->query($sql);
//             if(!$results) {
//                 echo $mysqli->error;
//                 exit();
//             }

//             $numresults = $results->num_rows;
            
//             if( $numresults != 0 ) {
//                 $error = "There is already an account associated with that email.";
//             }  
//         }
//         else {
//             $email = "null";
//         }

//         if ( isset($_POST['fullName']) && !empty($_POST['fullName']) ){
//             $fullName = $_POST['fullName']; 
//         }
//         else {
//             $fullName = "null";
//         }

//         if ( isset($_POST['genderId']) && !empty($_POST['genderId']) ){
//             $genderId = $_POST['genderId']; 
//         }
//         else {
//             $genderId = "null";
//         }


//         //Password adjustment

//         $password = "branja" . $password;

//         $password = hash('sha256', $password);

//         echo $password;


//         //generate an INSERT statement for the database
//         $sql = " INSERT INTO users(username, password, primaryEmail, fullName, genderId)
//                     VALUES('"

//                         . $username
//                         . "',"
//                         . "'" . $password
//                         . "',"
//                         . "'" . $email
//                         . "',"
//                         . "'" . $fullName
//                         . "',"
//                         . $genderId
//                     . ");";


//         $results = $mysqli->query($sql);
//         if(!$results) {
//             echo $mysqli->error;
//             exit();
//         }


//         //get the userId for this user and store in session variable

//         $sql = "SELECT userId FROM users
//             WHERE 
//                 username = '" . $username
//                 . "' AND
//                 password = '" . $password
//                 . "';";

//         $results = $mysqli->query($sql);
//         if(!$results) {
//             echo $mysqli->error;
//             exit();
//         }

//         $row = $results->fetch_assoc();

//         //Set session variables
//         $_SESSION['logged_in'] = true;
//         $_SESSION['username'] = $username;
//         $_SESSION['userId'] = $row['userId'];



//         $newUrl = "welcome.php";
//         //forward to homepage
//         header('Location: ' . $newUrl);




    
//     $mysqli->close();

?>

<!DOCTYPE html>
<html>

<head>
    <!-- <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" /> -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">

<!-- stylesheets -->
    <link rel="stylesheet" type="text/css" href="default_styles.css">
    <link rel="stylesheet" type="text/css" href="sidebar.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="navigationbar.css">
    <link rel="stylesheet" type="text/css" href="login.css">
    <link rel="stylesheet" type="text/css" href="createEvent.css">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet'>
    <title>Branja - Create Event</title>
</head>
<style type="text/css">
.sidenav a {
    text-decoration: none;
}
</style>

<body>

<?php require "navigation.php"; ?>

        <div class="body">
            <div class="container" id="createEventForm">
                <h2> create event </h2>
                <br/>
                <div class="small font-italic text-danger center">
                <?php if (isset($error) && !empty($error)) {
                        echo $error;
                    } ?>
                </div>
                <form action="validateEvent.php" method="POST">
                    <!-- .form-group -->
                    <div class="form-group">
                        <label for="eventName-id" class="">event name:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="eventName-id" name="eventName">
                        <small id="eventName-error" class="invalid-feedback">event name is required.</small>
                    </div>
                    <!-- .form-group -->
                    <div class="form-group">
                        <label for="eventType-id" class="">event type:<span class="text-danger">*</span></label>
                        <select name="eventType" id="eventType-id" class="form-control">
                            <option value="" selected>(select type)</option>
                            <!-- Rating dropdown options here -->
                            <option value="open">open</option>
                            <option value="private">private</option>
                        </select>
                        <small id="gender-error" class="invalid-feedback">Event type is required.</small>
                    </div>
                    <!-- .form-group -->
                    <div class="form-group">
                        <label for="datetime-id" class="">event time & date:<span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="eventTimeDate-id" name="eventTimeDate" >
                        <small id="eventTimeDate-error" class="invalid-feedback">name is required.</small>
                    </div>
                    <!-- .form-group -->
                    <label for="description-id" class="">description:</label>
                    <div class="form-group">
                        <textarea class="form-control" rows="5" id="description-id" name="description" placeholder=""></textarea>
                    </div>
                  <!-- .form-group -->
                    <div class="form-group">
                        <label for="eventImage-id" class="">image url:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="eventImage-id" name="eventImage">
                    </div>
                    <!-- .form-group -->
                    <div class="form-group">
                        <label for="eventAddress-id" class="">event address:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="eventAddress" name="eventAddress" value"">
                        <input type="hidden" id="eventCoordinates" name="eventCoordinates" value="">
                        <small id="username-error" class="invalid-feedback">event address required.</small>

                        <br>
                          <div class="row centerme">
                            <input type="text" id="address" class="form-control col-8" value="">
                            <button type="button" class="btn btn-primary col-4" id="locationSearchButton">Search</button>
                          </div>
                        <br>
    
                        <div id="eventMap">
                        </div>
                    </div>

  
                    <div id="error-messages"></div>
                    <!-- .form-group -->
                    <button type="submit" id="login" class="btn btn-default btn-block form-btn-hover col-sm-12 ">create event</button>
                </form>
                <hr>
                <div class="center">
                    <a href="homepage.php">&larr; back to map</a>
                </div>
            </div>
        </div>
    </div>
</body>
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>

var map;
    var marker;
    var validQuery = "";
    var geocoder;
    var infoWindow;

    function initMap() {
        var mapOptions = {
            zoom: 15,
            //this removes the street view control
            streetViewControl: false,
            fullscreenControl: false,
            mapTypeControl: false,
            //this is where the map is initially set to
            center: {
                lat: 34.0224,
                lng: -118.2851
            }

        }
        // Creating a new map
        map = new google.maps.Map(document.getElementById('eventMap'), mapOptions);
        geocoder = new google.maps.Geocoder;
        infoWindow = new google.maps.InfoWindow;


        google.maps.event.addListener(map, 'click', function(event) {
            placeMarker(event.latLng);
            geocodeLatLng(geocoder, map, infoWindow, event.latLng, "click");
        });


        // this function places the marker and populates the search input 
        function placeMarker(location) {
            if (typeof marker !== 'undefined') {
                marker.setMap(null);
            }
            marker = new google.maps.Marker({
                position: location,
                map: map,
            });

        }

        // this function converts geocode (latitude and longitude) to a recognizable address
        function geocodeLatLng(geocoder, map, infoWindow, latlng) {

            geocoder.geocode({ 'location': latlng }, function(results, status) {
                if (status === 'OK') {
                    if (results[0]) {
                        infoWindow.setContent(results[0].formatted_address);
                        infoWindow.open(map, marker);
                        document.getElementById("eventAddress").value = results[0].formatted_address;
                        document.getElementById("eventCoordinates").value = results[0].geometry.location;
                        validQuery = results[0].formatted_address;
                        
                    } else {
                        window.alert('No results found');
                    }
                } else {
                    window.alert('Geocoder failed due to: ' + status);
                }
            });
        }



        var marker = new google.maps.Marker({ map: map });

         
        document.getElementById("locationSearchButton").onclick = function() {

            var addressInput = document.querySelector("#address").value.trim();

            var geotest = new google.maps.Geocoder();

            geotest.geocode({ address: addressInput },
                function(results) { // This anonymous function runs when geocode() is done 
                    //(aka it is done converting the address into a latlng obj)
                    // console.log("LatLng: ");
                    // console.log(results[0].geometry.location.lat());
                    // console.log(results[0].geometry.location.lng());

                    map.setCenter(results[0].geometry.location);
                    marker.setPosition(results[0].geometry.location);
                    geocodeLatLng(geotest, map, infoWindow, results[0].geometry.location);
                    //don't update the address bar in this case
                    console.log("The address is: " + results[0].geometry.location);
                }
            );

            return false;
        }
    }






//============================Navigation related ============================
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDMq8as6Z4xmPfIl3HhLkngsd_PUmzL6wc&callback=initMap"></script>
</html>