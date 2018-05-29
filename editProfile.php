<?php
    

    require 'config/config.php';

    // DB Connection
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ( $mysqli->connect_errno ) {
        echo $mysqli->connect_error;
        exit();
    }

    $mysqli->set_charset('utf8');

if (!empty($_POST)){

    if ( !isset($_POST['username']) || 
    empty($_POST['username']) || 
    !isset($_POST['fullName']) || 
    empty($_POST['fullName']) || 
    !isset($_POST['genderId']) || 
    empty($_POST['genderId']) ) {

        $error = "Required inputs cannot be empty.";
    }


//verifying the new details the user put in their profile
//1. make sure the username is not taken
    //if it is taken, fill all the fields again with what was received from the database
    //and put an error message saying that the username is taken right about the username slot


    //if the username is set and not empty, check if the username is taken, if it's taken then 
    elseif ( isset($_POST['username']) && !empty($_POST['username']) ){
        $username = $mysqli->real_escape_string($_POST['username']); 


        //check if the username is taken
        $sql = "SELECT * FROM users WHERE username = '" . $_POST['username']
            . "AND userId <> " . $_SESSION['userId']
            . "';";

        $results = $mysqli->query($sql);
        if(!$results) {
            echo $mysqli->error;
            exit();
        }

        $numresults = $results->num_rows;
        
        if( $numresults != 0 ) {
            $error = "Username " . $_SESSION['username'] . " taken";
        }

       //username is fine, store the variables and insert new info into database
        else {
         
            if ( isset($_POST['username']) && !empty($_POST['username']) ){
                $username = $mysqli->real_escape_string($_POST['username']); 
            }
            else {
                $username = "";
            }

            if ( isset($_POST['fullName']) && !empty($_POST['fullName']) ){
                $fullName = $mysqli->real_escape_string($_POST['fullName']); 
            }
            else {
                $fullName = "";
            }

            if ( isset($_POST['genderId']) && !empty($_POST['genderId']) ){
                $genderId = $mysqli->real_escape_string($_POST['genderId']); 
            }
            else {
                $genderId = "";
            }

            if ( isset($_POST['profileImage']) && !empty($_POST['profileImage']) ){
                $profileImage = $mysqli->real_escape_string($_POST['profileImage']); 
            }
            else {
                $profileImage = "";
            }

            if ( isset($_POST['currentCity']) && !empty($_POST['currentCity']) ){
                $currentCity = $mysqli->real_escape_string($_POST['currentCity']); 
            }
            else {
                $currentCity = "";
            }

            if ( isset($_POST['profileUrl']) && !empty($_POST['profileUrl']) ){
                $profileUrl = $mysqli->real_escape_string($_POST['profileUrl']); 
            }
            else {
                $profileUrl = "";
            }

            if ( isset($_POST['biography']) && !empty($_POST['biography']) ){
                $biography = $mysqli->real_escape_string($_POST['biography']); 
            }
            else {
                $biography = "";
            }


             //generate an UPDATE statement for the database
                $sql = "UPDATE users
                    SET username = '" . trim($username) . "', "
                    . "fullName = '" . trim($fullName) . "', "
                    . "genderId = " . trim($genderId) . ", "
                    . "profileImage = '" . trim($profileImage) . "', "
                    . "currentCity = '" . trim($currentCity) . "', "
                    . "profileUrl = '" . trim($profileUrl) . "', "
                    . "biography = '" . trim($biography) . "' "
                    . "WHERE userId = " . $_SESSION['userId'] . ";";

                $results = $mysqli->query($sql);
                if(!$results) {
                    echo $mysqli->error;
                    exit();
                }

                $success = "profile updated";
        }
    }
}

    //get all the updated info back from the database, and populate the form

    $sql = "SELECT * FROM users WHERE userId = " . $_SESSION['userId'] . ";";

    

    $results = $mysqli->query($sql);
    if(!$results) {
        echo $mysqli->error;
        exit();
    }

    $row = $results->fetch_assoc();


    $username = $row['username'];
    $fullName = $row['fullName'];
    $genderId = $row['genderId'];
    $profileImage = $row['profileImage'];
    $currentCity = $row['currentCity'];
    $profileUrl = $row['profileUrl'];
    $biography = $row['biography'];



$mysqli->close();

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
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet'>
    <title>Branja - Login</title>
</head>
<style type="text/css">
.sidenav a {
    text-decoration: none;
}

</style>

<body>

<?php require "navigation.php"; ?>

        <div class="body">
            <div class="container" id="loginForm">
                <h2> edit profile </h2>
                <br/>
                
                <?php if (isset($error) && !empty($error)) : ?>
                    <div class="small font-italic text-danger center">
                    <?php echo $error; ?>
                    </div>
                    <br>
                <?php elseif (isset($success) && !empty($success)) : ?> 
                    <div class="small font-italic text-success center">
                    <?php echo $success; ?>
                    </div>
                    <br>
                <?php endif; ?>

                <form action="editProfile.php" method="POST">
                    <div class="form-group">
                        <label for="username-id" class="">username:</label>
                        <input type="text" class="form-control" id="username-id" name="username" placeholder="username *" value = "<?php echo $username; ?>" >
                        <small id="username-error" class="invalid-feedback">username is required.</small>
                    </div>
                    <!-- .form-group -->
                    <div class="form-group">
                        <label for="fullName-id" class="">full name:</label>
                        <input type="text" class="form-control" id="fullName-id" name="fullName" placeholder="full name *" value = "<?php echo $fullName; ?>" >
                        <small id="fullName-error" class="invalid-feedback">name is required.</small>
                    </div>
                    <div class="form-group">
                        <select name="genderId" id="gender-id" class="form-control">
                            <option value="" >(select gender)</option>

                            <?php if ( $genderId == '1' ) : ?>
                                <option selected value="1" >male</option>
                                <option value="2">female</option>
                                <option value="3">not specified</option>
                            <?php elseif ($genderId == '2' ) : ?>
                                <option value="1" >male</option>
                                <option selected value="2">female</option>
                                <option value="3">not specified</option>
                            <?php elseif ($genderId == '3' ) : ?>
                                <option value="1" >male</option>
                                <option value="2">female</option>
                                <option selected value="3">not specified</option>
                             <?php endif; ?>   
                        </select>
                        <small id="gender-error" class="invalid-feedback">Gender is required.</small>
                    </div>
                    <!-- .form-group -->
                    <div class="form-group">
                        <input type="text" class="form-control" id="profileImage-id" name="profileImage" placeholder="profile image url" value = "<?php echo $profileImage; ?>" >
                        <small id="profileImage-error" class="invalid-feedback">profileImage is required.</small>
                    </div>
                    <!-- .form-group -->
                    <div class="form-group">
                        <input type="text" class="form-control" id="currentCity-id" name="currentCity" placeholder="current city" value = "<?php echo $currentCity; ?>" >
                        <small id="currentCity-error" class="invalid-feedback">currentCity is required.</small>
                    </div>
                    <!-- .form-group -->
                    <div class="form-group">
                        <input type="text" class="form-control" id="profileUrl-id" name="profileUrl" placeholder="url" value = "<?php echo $profileUrl; ?>" >
                        <small id="profileUrl-error" class="invalid-feedback">profileUrl is required.</small>
                    </div>
                    <!-- .form-group -->
                    <div class="form-group">
                        <textarea class="form-control" rows="5" id="biography-id" name="biography" placeholder="bio"><?php echo $biography; ?></textarea>
                        <small id="biography-error" class="invalid-feedback">biography is required.</small>
                    </div>


                    <div id="error-messages"></div>
                    <!-- .form-group -->
                    <button type="submit" id="login" class="btn btn-default btn-block form-btn-hover col-sm-12 ">update profile</button>
                    <!-- <button type="reset" id="reset" class="btn btn-default btn-block form-btn-hover col-sm-12 ">reset values</button> -->
                </form>
                <hr>
                <div class="center">
                    <a href="profilePage.php">&larr; back to profile</a>
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