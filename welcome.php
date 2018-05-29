<?php
    
    require 'config/config.php';

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
            <div class="container">
                
                Welcome to branja <?php echo $_SESSION['username']; ?>! <br>

                <a href="homepage.php">Go to homepage</a>
                
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