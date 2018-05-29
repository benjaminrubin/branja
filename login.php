<?php
    
    require 'config/config.php';

    if (!empty($_SESSION)){
        if ( $_SESSION['logged_in'] == true ) {
            header('Location: homepage.php');
       }
   }

    // DB Connection
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ( $mysqli->connect_errno ) {
        echo $mysqli->connect_error;
        exit();
    }

    $mysqli->set_charset('utf8');

    // One edge case - if user is NOT logged in, then do all the things below

    if( !isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] ){
        //After the login form is submitted, make sure the username and password are set

        if( isset($_POST['username']) && isset($_POST['password']) ){



            //handling cases

            //Case 1: handle if user left anything blank
            if( empty($_POST['username']) ){
                $error = "Please enter username";
            }
            elseif( empty($_POST['password']) ){
                $error = "Please enter password";
            }
            elseif( empty($_POST['username']) && empty($_POST['password']) ){
                $error = "Please enter username and password";
            }

            //Case 2: nothing is left blank, check if they the password matches the username
            $username = $_POST['username'];
            $password = $_POST['password'];
            $password = "branja" . $password;
            $password = hash('sha256', $password);

            $sql = "SELECT userId FROM users
            WHERE 
                username = '" . $username
                . "' AND
                password = '" . $password
                . "';";


            // echo $sql;

            $results = $mysqli->query($sql);
            if(!$results) {
                echo $mysqli->error;
                exit();
            }

            $numresults = $results->num_rows;

            $row = $results->fetch_assoc();

            echo $row['userId'];
            
            if( $numresults == 0 ) {
                $error = "Invalid username or password";
            }
            else {

                var_dump($row);

                //Case 3: user typed in correct credentials
                // start setting the session variables
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['userId'] = $row['userId'];

                header('Location: homepage.php');
            }
        }
    }
    else {
        // header('Location: homepage.php');
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
    <link rel="stylesheet" type="text/css" href="login.css">
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
                <h2>log in</h2>
                <br>
                <div class="small font-italic text-danger center">
                <?php if (isset($error) && !empty($error)) {
                        echo $error;
                    } ?>
                </div>
                    <form action="login.php" method="POST">
                        <!-- .form-group -->
                        <div class="form-group">
                            <input type="text" name="username" class="form-control" id="username-id" placeholder="username">
                        </div>
                        <!-- .form-group -->
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" id="password-id" placeholder="password">
                        </div>
                        <!--         <div class="checkbox">
                    <label>
                        <input type="checkbox"> Remember me</label>
                </div> -->
                        <button type="submit" id="login" class="btn btn-default btn-block">log in</button>
                    </form>
                <div class="center">
                    <p></p>
                    <hr>
                    <p>first time on branja?</br>
                        <a href="signup.php">sign up here</a></p>
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