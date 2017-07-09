<?php

require_once 'configFile.php';

function computeEndTime($start, $duration){
	if ($duration >= 60){
		$hours = ($duration- $duration%60)/60;
	}
	else {
		$hours = 0;
	}
	$minutes = $duration - $hours*60;
	$newArr = explode(":", $start);
	$newArr[0] += $hours;
	$newArr[1] += $minutes;
	if ($newArr[1] > 59){
		$newArr[0]++;
		$newArr[1] -= 60;
	}
	if (strlen($newArr[0]) == 1){
		$newArr[0] = "0".$newArr[0];
	}
	if (strlen($newArr[1]) == 1){
		$newArr[1] = "0".$newArr[1];
	}
	$string = $newArr[0].':'.$newArr[1].':'.$newArr[2];
	return $string;
}

function computeDuration($h1, $m1, $h2, $m2){
	$duration = 0;
	if ($h2 > $h1){
		$duration += ($h2-$h1)*60;
	}
	$duration += ($m2-$m1);
	return $duration;
}

function checkForAvailableMachines($startTime, $endTime, $reservations){
  global $config;

  $machinesArray;
  for ($i = 1; $i <= $config['machines']['Nmachines']; $i++){
    $machinesArray[$i] = 0; //initialize all machines as available
  }
  for ($i = 0; $i < sizeof($reservations); $i++){
    $machineID = $reservations[$i]['Machine'];
    $machinesArray[$machineID] = 1;
  }
  //if at least one value of machinesArray is 0, a machine is available
  $assignedMachine = false;
  for ($i = 1; $i <= sizeof($machinesArray); $i++){
    if ($machinesArray[$i] == 0){
      $assignedMachine = $i;
      break;
    }
  }
  return $assignedMachine;
}

function strenghtenPassword($passwordWeak){
	$headerString = "xXx_420_xXx";
	$trailerString = "q(o.o)p";

	$passwordStrong = $headerString.$passwordWeak.$trailerString;
	return md5($passwordStrong);
}

function sanitizeString($string) {
	if (get_magic_quotes_gpc()) 
	   $string = stripslashes($string);
	$string = htmlentities($string);
	$string = strip_tags($string);
	return $string;
}

function sanitizeMySQL($conn, $string) {
	$string = mysqli_real_escape_string($conn, $string);
	$string = sanitizeString($string);
	return $string;
}

function validateForm(){
  global $config;
	$name = $surname = $email = $pass1 = $pass2 = "";
	$nameErr = $surnameErr = $emailErr = $pass1Err = $pass2Err = $finalError = "";

	if (!isset($_POST["name"])) {
    	$nameErr = "Name is required";
    }
    else {
    	$name = sanitizeString($_POST["name"]);
    	$name = ucfirst($name);
    	if (strlen($name) > $config['mysql']['maxFieldLength']) {
      		$nameErr = "Name exceeded max length (".$config['mysql']['maxFieldLength'].")";
    	}
    	elseif (!preg_match("/^[a-zA-Z ]*$/",$name)) {
  			$nameErr = "Only letters and white space allowed"; 
	    }
    	else {
      		//name is correct do nothing
    	}
  	}
  	if ($nameErr != ""){
  		$finalError .= "Name: ".$nameErr.'<br>';
  	}

  	if (!isset($_POST["surname"])) {
    	$surnameErr = "Surname is required";
    }
    else {
    	$surname = sanitizeString($_POST["surname"]);
    	$surname = ucfirst($surname);
    	if (strlen($surname) > $config['mysql']['maxFieldLength']) {
      		$surnameErr = "Surname exceeded max length (".$config['mysql']['maxFieldLength'].")";
    	}
    	elseif (!preg_match("/^[a-zA-Z ]*$/",$surname)) {
  			$surnameErr = "Only letters and white space allowed"; 
	    }
    	else {
      		//surname is correct do nothing
    	}
  	}
  	if ($surnameErr != ""){
  		$finalError .= "Surname: ".$surnameErr.'<br>';
  	}

   	if (!isset($_POST["email"])) {
    	$emailErr = "Email is required";
    }
    else {
    	$email = sanitizeString($_POST["email"]);
    	$email = ucfirst($email);
    	if (strlen($email) > $config['mysql']['maxFieldLength']) {
      		$emailErr = "Email exceeded max length (".$config['mysql']['maxFieldLength'].")";
    	}
    	elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  			$emailErr = "Invalid email format"; 
	    }
    	else {
      		//email is correct do nothing
    	}
  	}
  	if ($emailErr != ""){
  		$finalError .= "Email: ".$emailErr.'<br>';
  	}

   	if (!isset($_POST["password1"])) {
    	$pass1Err = "Password is required";
    }
    else {
    	$pass1 = sanitizeString($_POST["password1"]);
    	if (strlen($pass1) > $config['mysql']['maxFieldLength']) {
      		$pass1Err = "Password exceeded max length (".$config['mysql']['maxFieldLength'].")";
    	}
    	elseif (strlen($pass1) < 5) {
  			$pass1Err = "Password is too short"; 
	    }
    	else {
      		//password is correct do nothing
    	}
  	}
  	if ($pass1Err != ""){
  		$finalError .= "Password: ".$pass1Err.'<br>';
  	}

   	if (!isset($_POST["password2"])) {
    	$pass2Err = "You must confirm your password";
    }
    else {
    	$pass2 = sanitizeString($_POST["password2"]);
    	if ($pass2 != $pass1){
    		$pass2Err = "Passwords do not match";
    	}
    	else {
      		//password is correct do nothing
    	}
  	}
  	if ($pass2Err != ""){
  		$finalError .= "Password: ".$pass2Err.'<br>';
  	}

  	if ($finalError != ""){
  		return $finalError;
  	}
  	else {
  		return false;
  	}
}


/*
  The user shall be able to remove her reservation 
  from the site, but not before that 1 minute has 
  elapsed since the reservation start time.
*/
function checkDeleteCondition($reservations){
  $error = "";
  for ($i = 0; $i < sizeof($reservations); $i++){
    if (isset($_COOKIE[$reservations[$i][0]])){
      $error .= '<br>ID = '.$reservations[$i][0];
    }
  }
  return $error;
}

function checkCookies(){
  if (!isset($_SESSION['cookie']) && !isset($_COOKIE['cookie'])) {
        $_SESSION['cookie'] = false;
        setcookie("cookie", "yes", null, "/");
        header("Location: errorPage.php");
  }
}