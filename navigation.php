<?php if( isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ) : ?>

<div class="dark-overlay" id="darkSideNav" onclick="closeNav()"></div>
    <div class="dark-overlay" id="darkEventList" onclick="toggleEventList()"></div>
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div id="description">
            <!--             <p>This is <strong>branja</strong>.</p>
            <p> Plan them here with friends, nice and simple. </p> -->
            <h2> menu </h2>
            </br>

            <a href="homepage.php">homepage</a></br>
            <a href="<?php echo "profilePage.php?userId=" . $_SESSION['userId']; ?>" >profile</a></br>
            <a href="createEvent.php">create event</a></br>
            <!-- <a href="#">search</a></br> -->
            <!-- <a href="#">settings</a></br> -->
            <!-- <a href="#">about</a></br> -->
            </br>
            <p><a href="logout.php">logout</a></p>
        </div>
    </div>

<?php endif; ?>

    <div id="main">
        <nav class="navbar fixed-top navbar-dark bg-light main-nav d-flex">

<?php if( isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ) : ?>

            <div id="menu-btn" onclick="openNav()">&#9776;</div>

<?php endif; ?>

            <ul class="nav navbar-nav mx-auto">
                <li class="nav-item">
                    <a href="homepage.php" id="no-underline">
                        <div id="logo">
                            <div><img src="images/house.png" id="branjaLogoHouse"></div>
                            <div id="branjaLogoText">branja</div>
                        </div>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- end of navigation bar -->