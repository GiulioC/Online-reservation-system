<?php

session_start();
$loginErr = $errorMsg = "";

if (isset($_COOKIE['cookie']) && $_COOKIE['cookie'] == "yes" && !isset($_GET['err'])) {
    header("Location: home.php");
}
else {
	$errorMsg = "Cookies must be enabled to use this website.";
}

if (isset($_GET['err'])){
	$error = $_GET['err'];
	switch ($error){

		case "sessionExp":
			$errorMsg = "Yor session has expired, please autenticate again.";
			break;

		case "DBerr":
			$errorMsg = "Error while accessing the Database.";
			break;

		default:
			$errorMsg = "Unknow error, please try again";
			break;
	}
}

?>

			<?php include 'header.php'; ?>

			<div id="contentDiv" class="center">
				<?php
				if ($errorMsg != ""){
					echo '<div class="errormsg">';
					echo $errorMsg;
					echo '<br><a href="home.php"><button class="controlButton" type="submit">Close</button></a>';
					echo '</div>';
				}
				?>
			</div>
		</div>
	</body>
</html>