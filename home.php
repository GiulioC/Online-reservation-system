<?php
	require_once 'session.php';
	require_once 'database.php';
	require_once 'utility.php';

	global $config;

	startSession();

	checkCookies();
	if (isset($_SESSION['cookie']) && $_SESSION['cookie'] == false) {
	    //setcookie("cookie", "yes", null, "/");
	    $_SESSION['cookie'] = true;
	}

	if (isset($_SESSION['username'])){
		checkSessionValidity();
	}

	if ((!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') && isset($_SESSION['username'])) {
		$redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	    header("Location: ".$redirect);
	}

	$connection = connectToDB();
	if (mysqli_connect_error()) {
		header("Location: errorPage.php?err=DBerr");
	}

	if (isset($_POST['logout'])) {
		logout();
	}

	$loginMsg = $loginErr = "";
	$infoMsg = $errorMsg = "";

	if (isset($_POST['username']) && isset($_POST['password'])){
		if (strlen($_POST['username']) < $config['mysql']['maxFieldLength'] && strlen($_POST['password']) < $config['mysql']['maxFieldLength']) {
			if($result = autenticate($connection)) {
				login($result[0]['Email'], $result[0]['Password']);
				//createCookie();
				$loginMsg = "Hello ".sanitizeString($_SESSION['username']);
			}
			else {
				$loginErr = "wrong username/password";
			}
		}
		else {
			$loginErr = "wrong username/password";
		}
	}
	$arrayReserv = getAllReservations($connection);
	if (!$arrayReserv || sizeof($arrayReserv) == 0){
		$errorMsg = "There are no current reservations";
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
				<div id="tableDiv">
					<table id="tableAll">
						<caption><b>Reservations from all users</b></caption>
						<tr>
							<!--<td>Id</td>
							<td>username</td>-->
							<td>Start time</td>
							<td>End time</td>
							<td class="TDsmall">Duration (minutes)</td>
							<td>Machine ID</td>
						</tr>
						<?php
							for ($i = 0; $i < sizeof($arrayReserv); $i++){
								echo '<tr>';
								//echo '	<td>'.$arrayReserv[$i]["Id"].'</td>';
								//echo '  <td>'.$arrayReserv[$i]["Username"].'</td>';
								echo '  <td>'.$arrayReserv[$i]["StartTime"].'</td>';
								echo '  <td>'.$arrayReserv[$i]["EndTime"].'</td>';
								echo '  <td>'.$arrayReserv[$i]["Duration"].'</td>';
								echo '  <td>'.$arrayReserv[$i]["Machine"].'</td>';
								echo '</tr>';
							}
						?>
					</table>
				</div>
			</div>
		</div>
	</body>
	<?php
		mysqli_close($connection);
	?>
</html>