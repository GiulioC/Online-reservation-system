<?php

require_once 'session.php';
require_once 'database.php';

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

	$loginErr = "";
	$dataSent = false;
	$errorMsg = $infoMsg = "";
	if (isset($_POST['name'])){
		$dataSent = true;
		if (($_POST['name']) == "" || ($_POST['surname']) == "" || ($_POST['email']) == "" || ($_POST['password1']) == "" || ($_POST['password2']) == ""){
			$errorMsg = "Please fill in all the fields";
		}
		else {
			if (!checkEmailAlreadyInDB($connection, sanitizeString($_POST['email']))) {
				$result = validateForm(); //return false if form is valid
				if(!$result){
					if(addUser($connection)){
						$infoMsg = 'Registration completed';
					}
					else {
						$errorMsg = "Something went wrong during registration, please try again";
					}
				}
				else {
					$errorMsg = 'Data inserted is not valid:<br>'.$result.'<br><a href="registration.php"><button id="errorBack" class="controlButton" type="submit">Back</button></a>';
				}
			}
			else {
				$errorMsg = "Email address already in use<br>";
			}
		}
	}
?>

<?php include 'header.php'; ?>

			<div id="contentDiv">
				<?php 
				if (checkLoggedIn()){
					echo '<div class="errormsg">';
					echo "please logout before registering a new user";
					echo "</div>";
				}else{
					if ($infoMsg != ""){
						echo '<div class="infomsg">';
						echo $infoMsg;
						echo '<br><a href="home.php"><button class="controlButton" type="submit">Home</button></a>';
						echo '</div>';
					}
					if ($errorMsg != ""){
						echo '<div class="errormsg">';
						echo $errorMsg;
						echo '<a href="registration.php"><button class="controlButton" type="submit">Close</button></a>';
						echo '</div>';
					}
					if(!$dataSent) { ?>
					<div id="regForm">
					<h3 style="text-align:center">Add a new user</h3>
				<form id="registration-form" method="POST" action="registration.php">
				    <div class="row">
				        <div class="row-element">
				            <p class="label"><label>First Name</label></p>
				            <p><input class="input-field" id="nameReg" type="text" name="name" placeholder="Name"><em id="name-err" class="no-input" hidden></em></p>
				        </div>
				    </div>
				    
				    <div class="row">
				        <div class="row-element">
				            <p class="label"><label>Last Name</label></p>
				            <p><input class="input-field" id="surnameReg" type="text" name="surname" placeholder="Surname"/><em id="surname-err" class="no-input" hidden></em></p>
				        </div>
				    </div>
				    
				    <div class="row">
				        <div class="row-element">
				            <p class="label"><label>Email Address</label></p>
				            <p><input class="input-field" id="email" type="text" name="email" placeholder="Email"/><em id="email-err" class="no-input" hidden></em></p>
				        </div>
				    </div>
				    
				    <div class="row">
				        <div class="row-element">
				            <p class="label"><label>Password</label></p>
				            <p><input class="input-field" id="password" type="password" name="password1" placeholder="Password"><em id="pass-strength" hidden></em></p>
				        </div>
				    </div>
				    
				    <div class="row">
				        <div class="row-element">
				            <p class="label"><label>Confirm Password</label></p>
				            <p><input class="input-field" id="password-repeat" type="password" name="password2" placeholder="Password"><em id="pass-match" hidden></em></p>
				        </div>
				    </div>
				    
				    <div class="row-buttons">
				        <p> <button class="controlButton disabled" id="registration-button" type="submit" disabled/>Sign up</button>
				            <button class="controlButton" id="reset" type="reset"/>Reset</button>
				        </p>
				    </div>
				</form>
			</div>
				<?php
				 		} //dataSent
				 	} //else
				 ?>
			</div>			
		</div>
	</body>
	<?php
		mysqli_close($connection);
	?>
</html>