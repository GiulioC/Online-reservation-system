<!DOCTYPE HTML>
<html>
	<head>
		<title>3D printers</title>
		<link href="./css/mystyle.css" rel="stylesheet" type="text/css"  media="all" />
		<link href="./lib/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"  media="all" />
		<link rel="icon" href="images/favicon.png" type="image/x-icon">
		<script src="./lib/js/jquery-2.1.3.min.js"></script>
		<script src="./lib/js/jquery-ui/jquery-ui.min.js"></script>
		<script src="./lib/js/jquery.cookie.js"></script>
        <script src="./script/cookies.js"></script>
        <script src="./script/registration.js"></script>
        <script src="./script/utility.js"></script>

		<noscript>
            <div id="script-disabled" class="errormsg">
            	<noscript>
 					For full functionality of this site it is necessary to enable JavaScript.
					Here are the <a href="http://www.enable-javascript.com/" target="_blank">
					instructions how to enable JavaScript in your web browser</a>.
				</noscript>
            </div>
        </noscript>

        <div id="cookie-disabled" class="errormsg" hidden>
        	Cookies are disabled. To work properly, this application needs cookies. You can learn more <a href="https://support.google.com/accounts/answer/61416?hl=en" target="_blank">here</a>.
        </div>
        
        <div id="cookie-bar" hidden>
            <p>This website uses cookies to improve your experience with its services. By continuing to use this website, you are consenting to this use<a href="" id="cookie-accepted" class="cb-enable">Got it</a></p>
        </div>

	</head>

	<body>
		<div id="pageTitle">
  			<b>3D printers Reservation System</b>
		</div>

		<div id="mainDiv">
			<div id="leftDiv">
				<div id="navBar">
					<ul>
				        <li><a class="topnav" href="home.php">Home</a></li>
			            <li><a class="topnav" href="registration.php">Registration</a></li>
			            <?php if (isset($_SESSION['username'])){ ?>
			                <li><a class="topnav" href="profile.php">Personal Page</a></li>
			            <?php } ?>
		            </ul>
				</div>

				<div id="loginDiv">
						<?php 
							if (isset($_SESSION['username'])){
								echo '<form method="POST" action="home.php">';	
								echo "Hello, ".$_SESSION['username']."<br><br>";
								echo '<input class="controlButton" type="submit" value="Logout" name="logout">';
								echo '</form>';
							}
							else {
						?>
						<b>--------  Autenticate  --------</b>
						<form id="loginForm" method="post" action="home.php">
							<p>username <input class="input-field-login" type="text" name="username" placeholder="username" autocomplete="off"> </p>
							<p>password <input class="input-field-login" type="password" name="password" placeholder="password" autocomplete="off"> </p>

							<input class="controlButton" type="submit" value="Login">
						</form>
						<?php 
							}
							if ($loginErr != ""){
								echo '<div class="errormsg" id="wrongLogin">';
								echo $loginErr;
								echo '</div>';
							}
						?>
				</div>
			</div>