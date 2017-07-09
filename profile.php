<?php
	require_once 'session.php';
	require_once 'database.php';
	require_once 'utility.php';
	
	startSession();
	checkCookies();

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
	
	$infoMsg = $errorMsg = $noLogin = "";
	$loginErr = "";

	if (!checkLoggedIn()){
		$noLogin = "You must log in to see this section.";
	}
	else {
		//placed here so that when a new reservation is added it can be shown together with the notfication message
		if (isset($_POST['hoursStart']) || isset($_POST['minutesStart']) || isset($_POST['hoursEnd']) || isset($_POST['minutesEnd'])){
			if (isset($_POST['hoursStart']) && isset($_POST['minutesStart']) && isset($_POST['hoursEnd']) && isset($_POST['minutesEnd'])){
				$startH = sanitizeString($_POST['hoursStart']);
				$startM = sanitizeString($_POST['minutesStart']);
				$endH = sanitizeString($_POST['hoursEnd']);
				$endM = sanitizeString($_POST['minutesEnd']);

				if (!ctype_digit($startH) || !ctype_digit($startM) || !ctype_digit($endH) || !ctype_digit($endM) ||
					strlen($startH) != 2 || strlen($startM) != 2 || strlen($endH) != 2 || strlen($endM) != 2) {
					$errorMsg = "Error in data inserted";
				}
				else {
					if ($endH < $startH || ($startH == $endH && $endM <= $startM)){
						$errorMsg = "End time must be greater than start time";
					}
					else {
						$duration = computeDuration($startH, $startM, $endH, $endM);
						$start = $startH.":".$startM.":00";
						$end = $endH.":".$endM.":00";

						$overlappedReservations = getOverlappedReservations($connection, $start, $duration);
						$machineID = checkForAvailableMachines($start, $end, $overlappedReservations); //false if no machines are available
						if (!$machineID){
							$errorMsg = 'No machines available in the chosen time interval';
						}
						else {
							if(createNewReservation($connection, $start, $duration, $end, $machineID)){
								$result = getMaxReservationID($connection);
								$maxID = $result[0]["maxID"]; //highest reservation ID currently in the DB
								setcookie($maxID, "true", time()+60);
								$infoMsg = 'Reservation created successfully';
							}
							else {
								echo 'something went wrong<br>';
							}
						}
					}
				}
			}
			else {
				$errorMsg = "Please fill in all the fields";
			}
		}
		$arrayReserv = getUserReservations($connection, $_SESSION['username']);
	}

	if(isset($_POST['delete']) || isset($_POST['deleteAll'])){
		//retrieve reservations to be deleted
		$delArray = array();
		if (isset($_POST['deleteAll'])){
			for ($i = 0; $i < sizeof($arrayReserv); $i++){
				$delArray[] = array($arrayReserv[$i]["Id"], $arrayReserv[$i]["StartTime"]);
			}
		}
		else {
			foreach($_POST as $id => $value){
				//scan arrayReserv to find reservation Id and startTime
				for ($i = 0; $i < sizeof($arrayReserv); $i++){
					if ($value == 'on' && $arrayReserv[$i]["Id"] == $id){
						$delArray[] = array($id, $arrayReserv[$i]["StartTime"]);
					}
				}
			}
		}
		if (sizeof($delArray) > 0){
			$result = checkDeleteCondition($delArray);
			if ($result === ""){
				if (deleteReservations($connection, $delArray)){
					$infoMsg =  "reservation(s) deleted successfully";
				}
				else {
					$errorMsg =  'something went wrong';
				}
			}
			else {
				$errorMsg = 'One or more reservations cannot be canceled.'.$result;
			}
		}
		else {
			$errorMsg = 'Select at least one reservation you want to delete';
		}
	}
?>

<?php include 'header.php'; ?>

			<div id="contentDiv">
				<?php
					if ($noLogin != ""){
						echo '<div class="errormsg">';
						echo $noLogin;
						echo '</div>';
					}
					else {
						if ($infoMsg != ""){
							echo '<div class="infomsg">';
							echo $infoMsg;
							echo '<br><a href="profile.php"><button class="controlButton" type="submit">Close</button></a>';
							echo '</div>';
						}
						if ($errorMsg != ""){
							echo '<div class="errormsg">';
							echo $errorMsg;
							echo '<br><a href="profile.php"><button class="controlButton" type="submit">Close</button></a>';
							echo '</div>';
						}
						if (sizeof($arrayReserv) == 0){
							echo '<div class="errormsg">';
							echo 'You don\'t have any active reservation';
							echo '</div>';
						}
						else {
					?>
						<form method="POST" action="profile.php" id="myReservations">
							<table id="tableAll">
								<caption><b>Your Reservations</b></caption>
							<tr>
								<!--<td>Id</td>
								<td>username</td>-->
								<td>Start time</td>
								<td>End time</td>
								<td class="TDsmall">Duration (minutes)</td>
								<td>Machine ID</td>	
								<td>Delete</td>
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
										echo '  <td><input class="reservCheck" type="checkbox" name="'.$arrayReserv[$i]["Id"].'"/></td>';
										echo '</tr>';
								}
							?>
							</table>
							<div class="buttonDiv">
								<p>
									<input type="submit" name="delete" value="Delete Selected Reservations" class="controlButton buttonBig disabled" id="delSelected" disabled>
									<input type="submit" name="deleteAll" value="Delete All Reservations" class="controlButton buttonBig" id="delAll">
								</p>
							</div>
						</form>
						<?php }// 0 Reservations else ?>

					<div id="addReservation">
						<h3>Create a new reservation</h3>
						
					        <form method="post" action="profile.php" class="center my-form">
					        	<table id="reservTable">
							        <tr><td>Start Time </td><td>
							        <select name="hoursStart" class="timeField">
							        	<option value="" disabled selected>Hours</option>
							        	<?php for ($i=0; $i<10; $i++){
							        		echo '<option value="0'.$i.'">0'.$i.'</option>';
							        	}
							        	for ($i=10; $i<24; $i++){
							        		echo '<option value="'.$i.'">'.$i.'</option>';
							        	} ?>
									</select>
									<b>:</b>
									<select name="minutesStart" class="timeField">
										<option value="" disabled selected>Minutes</option>
							        	<?php for ($i=0; $i<10; $i++){
							        		echo '<option value="0'.$i.'">0'.$i.'</option>';
							        	}
							        	for ($i=10; $i<60; $i++){
							        		echo '<option value="'.$i.'">'.$i.'</option>';
							        	} ?>
									</select></td></tr>

									<tr><td>End Time</td><td> 
							        <select name="hoursEnd" class="timeField">
							        	<option value="" disabled selected>Hours</option>
							        	<?php for ($i=0; $i<10; $i++){
							        		echo '<option value="0'.$i.'">0'.$i.'</option>';
							        	}
							        	for ($i=10; $i<24; $i++){
							        		echo '<option value="'.$i.'">'.$i.'</option>';
							        	} ?>
									</select>
									<b>:</b>
									<select name="minutesEnd" class="timeField">
										<option value="" disabled selected>Minutes</option>
							        	<?php for ($i=0; $i<10; $i++){
							        		echo '<option value="0'.$i.'">0'.$i.'</option>';
							        	}
							        	for ($i=10; $i<60; $i++){
							        		echo '<option value="'.$i.'">'.$i.'</option>';
							        	} ?>
									</select></td></tr>
									<tr id="showDuration" hidden>
										<td></td><td><div class="reduceWidth" id="durationTR"></div></td>
									</tr>
								</table>
								<div class ="submitButton">
									<input class="controlButton" type="submit" value="send">
									<input class="controlButton" id="resetTime" type="reset" value="reset">
								</div>
							</form>
					</div>
					<?php } //noLogin ?>
			</div>
		</div>
	</body>
	<?php
		mysqli_close($connection);
	?>
</html>