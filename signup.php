<?php
    
require 'config/config.php';

//if someone is logged in, they shouldn't be able to see this page
if (!empty($_SESSION)){
    if ( $_SESSION['logged_in'] == true ) {
        header('Location: homepage.php');
   }
}

//if a POST form is not empty (i.e., it has been submitted) you can get errors
if (!empty($_POST)){

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

            $error = "Inputs cannot be blank.";
        
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
                    $username = $mysqli->real_escape_string($_POST['username']); 


                    //check if the username is taken

                    $sql = "SELECT * FROM users WHERE username = '" . $username . "';";

                    $results = $mysqli->query($sql);
                    if(!$results) {
                        echo $mysqli->error;
                        exit();
                    }

                    $numresults = $results->num_rows;
                    
                    if( $numresults != 0 ) {
                        $error = "Username taken";
                    }
                }
                else {
                    $username = "null";
                }

                if ( isset($_POST['password']) && !empty($_POST['password']) ){
                    $password = $mysqli->real_escape_string($_POST['password']); 
                }
                else {
                    $password = "null";
                }

                if ( isset($_POST['email']) && !empty($_POST['email']) ){
                    $email = $mysqli->real_escape_string($_POST['email']); 

                     //check if the email is taken

                    $sql = "SELECT * FROM users WHERE primaryEmail = '" . $email . "';";

                    $results = $mysqli->query($sql);
                    if(!$results) {
                        echo $mysqli->error;
                        exit();
                    }

                    $numresults = $results->num_rows;
                    
                    if( $numresults != 0 ) {
                        $error = "There is already an account associated with that email.";
                    }  
                }
                else {
                    $email = "null";
                }

                if ( isset($_POST['fullName']) && !empty($_POST['fullName']) ){
                    $fullName = $mysqli->real_escape_string($_POST['fullName']); 
                }
                else {
                    $fullName = "null";
                }

                if ( isset($_POST['genderId']) && !empty($_POST['genderId']) ){
                    $mysqli->real_escape_string($genderId = $_POST['genderId']); 
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


                $results = $mysqli->query($sql);
                if(!$results) {
                    echo $mysqli->error;
                    exit();
                }


                //get the userId for this user and store in session variable

                $sql = "SELECT userId FROM users
                    WHERE 
                        username = '" . $username
                        . "' AND
                        password = '" . $password
                        . "';";

                $results = $mysqli->query($sql);
                if(!$results) {
                    echo $mysqli->error;
                    exit();
                }

                $row = $results->fetch_assoc();

                $mysqli->close();
                //Set session variables
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['userId'] = $row['userId'];



                $newUrl = "welcome.php";
                //forward to homepage
                header('Location: ' . $newUrl);
    }


}
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
                <h2> sign up </h2>
                <br/>
                <div class="small font-italic text-danger center">
                <?php if (isset($error) && !empty($error)) {
                        echo $error;
                    } ?>
                </div>
                <form action="signup.php" method="POST">
                    <!-- .form-group -->
                    <div class="form-group">
                        <!-- <label for="email-id" class="col-sm-3 col-form-label text-sm-right">email:<span class="text-danger">*</span></label> -->
                        <input type="email" class="form-control" id="email-id" name="email" placeholder="email *">
                        <small id="email-error" class="invalid-feedback">email is required.</small>
                    </div>
                    <!-- .form-group -->
                    <div class="form-group">
                        <!-- <label for="username-id" class="col-sm-3 col-form-label text-sm-right">name:<span class="text-danger">*</span></label> -->
                        <input type="text" class="form-control" id="fullName-id" name="fullName" placeholder="full name *">
                        <small id="fullName-error" class="invalid-feedback">name is required.</small>
                    </div>
                    <!-- .form-group -->
                    <div class="form-group">
                        <!-- <label for="username-id" class="col-sm-3 col-form-label text-sm-right">name:<span class="text-danger">*</span></label> -->
                        <input type="text" class="form-control" id="username-id" name="username" placeholder="username *">
                        <small id="username-error" class="invalid-feedback">username is required.</small>
                    </div>
                    <!-- .form-group -->
                    <div class="form-group">
                        <!-- <label for="password-id" class="col-sm-3 col-form-label text-sm-right">password:<span class="text-danger">*</span></label> -->
                        <input type="password" class="form-control" id="password-id" name="password" placeholder="password *">
                        <small id="password-error" class="invalid-feedback">password is required.</small>
                    </div>
                    <!-- .form-group -->
                    <div class="form-group">
                        <!-- <label for="gender-id" class="col-sm-3 col-form-label text-sm-right">gender:<span class="text-danger">*</span></label> -->
                        <select name="genderId" id="gender-id" class="form-control">
                            <option value="" selected>(select gender)</option>
                            <!-- Rating dropdown options here -->
                            <option value="1" >male</option>
                            <option value="2">female</option>
                            <option value="3">not specified</option>
                        </select>
                        <small id="gender-error" class="invalid-feedback">Gender is required.</small>
                    </div>
                    <div id="error-messages"></div>
                    <!-- .form-group -->
                    <button type="submit" id="login" class="btn btn-default btn-block form-btn-hover col-sm-12 ">create user</button>
                    <button type="reset" id="reset" class="btn btn-default btn-block form-btn-hover col-sm-12 ">reset values</button>
                </form>
                <hr>
                <div class="center">
                    <a href="login.php">&larr; back to log in</a>
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