<?php
  require 'config/config.php';

  //if the session is not in play, take back to login page
  if( !isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] ){
    header('Location: login.php');
  }

  //Get all the events that are relevant to the user (just get all of the events)
    // DB Connection
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ( $mysqli->connect_errno ) {
        echo $mysqli->connect_error;
        exit();
    }


    $sql = "SELECT * FROM events WHERE eventActive = 1;";

    $results = $mysqli->query($sql);
    if(!$results) {
        echo $mysqli->error;
        exit();
    }

    // $event = $results->fetch_assoc();

    // echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";

    // var_dump($event);


?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="default_styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0;">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="sidebar.css">
    <link rel="stylesheet" type="text/css" href="navigationbar.css">
    <link rel="stylesheet" type="text/css" href="map.css">
    <link rel="stylesheet" type="text/css" href="eventCard.css">
    <link rel="stylesheet" type="text/css" href="homepage.css">
    <!-- <link rel="stylesheet" type="text/css" href="arrow_styles.css"> -->
    <title>Branja - Homepage</title>
</head>
<style type="text/css">


</style>

<body>
    
    <?php require "navigation.php"; ?>

        <div id="mapContainer">
            <div id="map">
            </div>
            <div id="infoWindow"></div>
        </div>
    </div>
    <div id="myBottomNav" class="bottomNav">
        <div id="eventListTop" onclick="toggleEventList()">
            <div id="eventlistdimple"></div>
            <div id="eventListHeader">upcoming events &#8607;</div>
            <!-- <a href="javascript:void(0)" class="closebtn" onclick="closeEventList()">&times;</a> -->
        </div>
        <div id="eventListBottom">
        </div>
        <!-- end of event list items -->
    </div>
    <!-- Map Scripts -->
    <script>
    var map;
    var marker;
    var markers = [];
    var validQuery = "";
    var geocoder;
    var infoWindow;
    var bounds;
    var labels = '123456789';
    var labelIndex = 0;

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
        map = new google.maps.Map(document.getElementById('map'), mapOptions);
        bounds = new google.maps.LatLngBounds();


        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ?
                'Error: The Geolocation service failed.' :
                'Error: Your browser doesn\'t support geolocation.');
            infoWindow.open(map);
        }

        google.maps.event.addListener(map, 'click', function(event) {
            // placeMarker(event.latLng);
            // geocodeLatLng(geocoder, map, infoWindow, event.latLng, "click");
            clearInfoWindows();
        });


        // this function places the marker and populates the search input 
        function placeMarker(location) {
            if (typeof marker !== 'undefined') {
                marker.setMap(null);
            }
            marker = new google.maps.Marker({
                position: location,
                map: map,
                // icon: {
                //     url: 'images/currentLocationMarker2.png',
                //     scaledSize: new google.maps.Size(24,24)
                // }
            });

            //this is where I should add the function of changing the pins
        }





        // this function converts geocode (latitude and longitude) to a recognizable address
        function geocodeLatLng(geocoder, map, infoWindow, latlng, requestType) {
            geocoder.geocode({ 'location': latlng }, function(results, status) {
                if (status === 'OK') {
                    if (results[0]) {
                        infoWindow.setContent(results[0].formatted_address);
                        infoWindow.open(map, marker);
                        if (requestType == "click") {
                            document.getElementById("address").value = results[0].formatted_address;
                        }
                        validQuery = results[0].formatted_address;
                        document.getElementById('meetingAddress').value = validQuery;
                        console.log(document.getElementById('meetingAddress').value);
                        enableCreateMeetingButton();
                    } else {
                        window.alert('No results found');
                    }
                } else {
                    window.alert('Geocoder failed due to: ' + status);
                }
            });
        }



        // addMarker('private', {lat: 34.0224, lng: -118.2851});

        // //adding a marker for my home
        // addMarker('private', { lat: 34.025721, lng: -118.280009 });

        // //adding a marker for the shrine
        // addMarker('special', { lat: 34.023697, lng: -118.281359 });

        // //adding a marker for downtown convention center
        // addMarker('public', { lat: 34.041266, lng: -118.269530 });

        // addMarker('special', { lat: 34.0224, lng: -118.2851 });


        // Add Marker Function -> a function to add a marker
        function addMarker(type, coords) {

            var markerLabel = {
                text: labels[labelIndex++ % labels.length],
                color: "white"
            }

            var marker = new google.maps.Marker({
                position: coords,
                map: map,
                label: markerLabel,
                // animation: google.maps.Animation.DROP,
            });

            if (type == "open") {
                var markerIcon = {
                    url: "images/mapMarkers/open.png",
                    scaledSize: new google.maps.Size(48, 48),
                    labelOrigin: new google.maps.Point(24, 15)
                }
                marker.setIcon(markerIcon);
                // var marker = new google.maps.Marker({
                //     position: coords,
                //     // In which map is the marker set
                //     map: map,
                //     animation: google.maps.Animation.DROP,
                //     label: markerLabel,
                //     //you can put a custom icon in here
                //     icon: {
                //         // url: "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png",
                //         url: "images/green.png",
                //         // label: labels[labelIndex++ % labels.length],
                //         animation: google.maps.Animation.DROP,
                //         // size: new google.maps.Size(36, 36),
                //         // anchor: new google.maps.Point(48,48),
                //         scaledSize: new google.maps.Size(42, 42)
                //     }
                // });
            } else if (type == "private") {

                var markerIcon = {
                    url: "images/mapMarkers/private.png",
                    scaledSize: new google.maps.Size(48, 48),
                    labelOrigin: new google.maps.Point(24, 15)
                }
                marker.setIcon(markerIcon);

            } else if (type == "special") {

                var markerIcon = {
                    url: "images/mapMarkers/special.png",
                    scaledSize: new google.maps.Size(48, 48),
                    labelOrigin: new google.maps.Point(24, 15)
                }
                marker.setIcon(markerIcon);
            } else if (type == "organization") {

                var markerIcon = {
                    url: "images/mapMarkers/organization.png",
                    scaledSize: new google.maps.Size(48, 48),
                    labelOrigin: new google.maps.Point(24, 15)
                }
                marker.setIcon(markerIcon);

            }

            //add the marker to the markers array
            markers.push(marker);

            //make sure to get all the bounds to fit the map to all the pins
            for (var i = 0; i < markers.length; i++) {
                bounds.extend(markers[i].getPosition());
            }
            map.fitBounds(bounds);

            console.log(markers.length);
        }



        //create event array from php variables

        var events = [];

        <?php while ( $row = $results->fetch_assoc() ) : ?>

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


        for (var i = 0; i < events.length; i++) {

            //Step 1: add the event's marker to the map
            var eventLoc = { lat: parseFloat(events[i].latitude), lng: parseFloat(events[i].longitude) };
            addMarker(events[i].eventType, eventLoc);

            //Step 2: add the event to the eventList
            createEventCard(events[i], (i), "eventList");

        }




        //when clicking on a marker show the event info 
        for (var i = 0; i < markers.length; i++) {

            markers[i].addListener('click', function() {

                clearInfoWindows();

                //get event number
                var eventNumber = this.label.text;
                eventNumber -= 1;
                console.log("event number is: " + eventNumber);
                //get the text associated with that number

                createEventCard(events[eventNumber], eventNumber, "map");


                //center the map to the marker
                map.panTo({ lat: parseFloat(events[eventNumber].latitude), lng: parseFloat(events[eventNumber].longitude) });



                // //create the event's image
                // var eventSectionImage = document.createElement("div");
                // eventSectionImage.classList.add("eventSection");

                // var eventImg = document.createElement("img");
                // eventImg.classList.add("eventListItemImage");
                // eventImg.src = events[eventNumber].eventImage;

                // eventSectionImage.appendChild(eventImg);

                // //create the event's description description
                // var eventSectionDes = document.createElement("div");
                // eventSectionDes.classList.add("eventDescription");
                // eventSectionDes.innerHTML = "<strong>" + events[eventNumber].eventName + "</strong> <p>" + events[eventNumber].eventAddress + "<br>" + events[eventNumber].eventDate + "<br>" + events[eventNumber].eventAttendees + " people going" + "</p>";


                // //create the event's marker
                // var eventSectionMarker = document.createElement("div");
                // eventSectionMarker.classList.add("eventSection");

                // var markerImg = document.createElement("img");
                // markerImg.classList.add("eventListMarker");
                // var imgsrc = "images/eventListMarkers/eventListMarker_";
                // if (events[eventNumber].eventType === "private") {
                //     imgsrc += "private.png";
                // } else if (events[eventNumber].eventType === "open") {
                //     imgsrc += "open.png"
                // }

                // markerImg.src = imgsrc;

                // //create the event list marker

                // //start off with the number
                // var eventListMarkerNumber = document.createElement("div");
                // eventListMarkerNumber.classList.add("eventListMarkerNumber");
                // eventListMarkerNumber.innerHTML = (eventNumber + 1);

                // //create event type
                // var eventType = document.createElement("span");
                // eventType.innerHTML = events[eventNumber].eventType;

                // eventSectionMarker.appendChild(markerImg);
                // eventSectionMarker.appendChild(eventListMarkerNumber);
                // eventSectionMarker.appendChild(eventType);



                // //append all three sections to the element
                // var eventListItem = document.createElement("div");
                // eventListItem.classList.add("eventListItem");

                // eventListItem.appendChild(eventSectionImage);
                // eventListItem.appendChild(eventSectionDes);
                // eventListItem.appendChild(eventSectionMarker);


                // //add the element to the eventList

                // // console.log("event list item:");
                // // console.log(eventListItem);

                // document.getElementById("infoWindow").appendChild(eventListItem);

            });
        }


        //This function generates an Event Card, whether in the eventList or as an info window on the map
        function createEventCard(event, eventNumber, destination) {
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
            eventSectionDes.innerHTML = "<strong>" + event.eventName.toUpperCase() + "</strong> <p>📍" + event.eventAddress + "<br>⏰" + event.eventDate + "<br>✅" + event.eventAttendees + " people going" + "</p>";


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
            eventSectionMarker.appendChild(eventListMarkerNumber);
            eventSectionMarker.appendChild(eventType);


            var linkWrapper = document.createElement("a");
            linkWrapper.href = "eventPage.php?eventID=" + event.eventID;


            //append all three sections to the element
            var eventListItem = document.createElement("div");
            eventListItem.classList.add("eventListItem");

            eventListItem.appendChild(eventSectionImage);
            eventListItem.appendChild(eventSectionDes);
            eventListItem.appendChild(eventSectionMarker);


            //add event listener

            eventListItem.addEventListener('click', function(){
              var url = event.eventLink;
              window.location = url;
            });

            if (destination === "map") {
                document.getElementById("infoWindow").appendChild(eventListItem);
            } else if (destination === "eventList") {
                document.getElementById("eventListBottom").appendChild(eventListItem);
            }
        }


        // var marker = new google.maps.Marker({ map: map });

        //This function generates 
        // document.querySelector("#google-form").onsubmit = function() {

        //     var addressInput = document.querySelector("#address").value.trim();

        //     var geotest = new google.maps.Geocoder();

        //     geotest.geocode({ address: addressInput },
        //         function(results) { // This anonymous function runs when geocode() is done 
        //             //(aka it is done converting the address into a latlng obj)
        //             // console.log("LatLng: ");
        //             // console.log(results[0].geometry.location.lat());
        //             // console.log(results[0].geometry.location.lng());

        //             map.setCenter(results[0].geometry.location);
        //             marker.setPosition(results[0].geometry.location);
        //             geocodeLatLng(geotest, map, infoWindow, results[0].geometry.location, "search");
        //             //don't update the address bar in this case
        //             console.log("The address is: " + results[0].geometry.location);
        //         }
        //     );
        //     //If a valid address has been found, activate the create meeting button
        //     if (!document.querySelector("#address").value.trim() == "") {

        //         document.getElementById("createmeeting_button").disabled = false;
        //     }
        //     return false;
        // }

    }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDMq8as6Z4xmPfIl3HhLkngsd_PUmzL6wc&callback=initMap"></script>
    <!-- Sidebar Navigation Scripts -->
    <script>
    //clears any windows shown on the map
    function clearInfoWindows() {
        //remove any info windows on the map
        var remove = document.getElementById("infoWindow");
        while (remove.hasChildNodes()) {
            remove.removeChild(remove.firstChild);
        }

    }

    //=========Side navigation============

    function openNav() {
        console.log(document.getElementById("darkSideNav").style.visibility);
        document.getElementById("mySidenav").style.width = "250px";
        document.getElementById("darkSideNav").style.visibility = "visible";
        document.getElementById("darkSideNav").style.opacity = "0.5";
        // document.getElementById("main").style.marginLeft = "250px";
        // document.getElementById("myBottomNav").style.marginLeft = "250px";
        // document.querySelector("nav").style.width = "250px";
    }

    function closeNav() {
        console.log(document.getElementById("darkSideNav").style.visibility);
        document.getElementById("mySidenav").style.width = "0";
        document.getElementById("darkSideNav").style.opacity = "0";
        document.getElementById("darkSideNav").style.visibility = "hidden";
        // document.getElementById("main").style.marginLeft = "0";
        // document.getElementById("myBottomNav").style.marginLeft = "0px";
        // document.querySelector("nav").style.left = "0px";
    }


    //=========Event List============

    function toggleEventList() {
        //If it is closed, open the event list
        if (document.getElementById("myBottomNav").offsetHeight == "50") {
            document.getElementById("myBottomNav").style.height = "85%";
            document.getElementById("myBottomNav").style.overflow = "auto";
            document.getElementById("darkEventList").style.visibility = "visible";
            document.getElementById("darkEventList").style.opacity = "0.5";

            //change the arrow to a downward arrow
            document.getElementById("eventListHeader").innerHTML = "upcoming events &#8609;";

            //clear any infowindows
            clearInfoWindows();


        }
        //hide the event list
        else {
            document.getElementById("myBottomNav").style.height = "50px";
            document.getElementById("myBottomNav").style.overflow = "hidden";
            document.getElementById("darkEventList").style.opacity = "0";
            document.getElementById("darkEventList").style.visibility = "hidden";
            // overflow-y: hidden; // hide vertical

            //change the arrow to an upward arrow
            document.getElementById("eventListHeader").innerHTML = "upcoming events &#8607;";
        }
    }
    </script>
</body>

</html>