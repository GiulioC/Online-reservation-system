<?php 

require_once 'configFile.php';
require_once 'utility.php';

function addUser($conn){
	$query = "INSERT INTO `users`(`Name`, `Surname`, `Email`, `Password`) VALUES ('".sanitizeMySQL($conn, $_POST['name'])."','".sanitizeMySQL($conn, $_POST['surname'])."','".sanitizeMySQL($conn, $_POST['email'])."','".sanitizeMySQL($conn, strenghtenPassword($_POST['password1']))."')";
	$result=mysqli_query($conn, $query);
	return $result; //true on success
}

function connectToDB(){
	global $config;
	$connection = new mysqli($config['mysql']['host'] , $config['mysql']['user'] , $config['mysql']['password'] , $config['mysql']['database']);
	if ($connection->connect_error) {
		die("Connection failed: " . $connection->connect_error);
		exit();
	}
	else {
		return $connection;
	}
}

function autenticate($conn){
	$email = sanitizeMySQL($conn, $_POST['username']);
	$pass = strenghtenPassword(sanitizeMySQL($conn, $_POST['password']));
	$query="SELECT * FROM `users` WHERE `Email`='".$email."' AND `Password`= '".$pass."'";
	return fetchResult(mysqli_query($conn, $query));
}

function getAllReservations($conn){
	$query="SELECT * FROM `reservations` ORDER BY StartTime";
	return fetchResult(mysqli_query($conn, $query));
}

function getUserReservations($conn, $username){
	$username = sanitizeMySQL($conn, $username);
	$query = "SELECT * FROM `reservations` WHERE `Username`='".$username."' ORDER BY StartTime";
	return fetchResult(mysqli_query($conn, $query));
}

function createNewReservation($conn, $start, $duration, $end, $machine){
	$query = "INSERT INTO `reservations`(`Id`, `Username`, `StartTime`, `EndTime`, `Duration`, `Machine`) VALUES (NULL,'".$_SESSION['username']."','".$start."','".$end."','".$duration."','".$machine."')";
	return mysqli_query($conn, $query);
}

function deleteReservations($conn, $IDarray){
	$valueString = "";
	for ($i = 0; $i < sizeof($IDarray); $i++){
		$valueString = $valueString.sanitizeMySQL($conn, $IDarray[$i][0]).",";
	}
	$valueString = rtrim($valueString, ",");

	$query = "DELETE FROM `reservations` WHERE `Id` IN (".$valueString.")";
	return mysqli_query($conn, $query);
}

function getOverlappedReservations($conn, $start, $duration){
	$start = sanitizeMySQL($conn, $start);
	$duration = sanitizeMySQL($conn, $duration);
	$query = "SELECT * FROM `reservations` WHERE (`StartTime`>='".$start."' AND `StartTime` < '".computeEndTime($start, $duration)."') OR (`StartTime` < '".$start."' AND `EndTime` <= '".computeEndTime($start, $duration)."' AND `EndTime` > '".$start."') OR (`StartTime` >= '".$start."' AND `EndTime` > '".computeEndTime($start, $duration)."' AND `StartTime` < '".computeEndTime($start, $duration)."') OR (`StartTime` <= '".$start."' AND `EndTime` >= '".computeEndTime($start, $duration)."')";
	$result=mysqli_query($conn, $query);
	return fetchResult($result);
}

function fetchResult($queryResult){
	if ($queryResult != false) { //on query success
		if(mysqli_num_rows($queryResult) > 0) {// if there's at least one row result.
			while ($row = mysqli_fetch_array($queryResult, MYSQL_ASSOC)) {
				$returnArray[] = $row; 
			}
		}
		else {// if the query returned an empty result
			$returnArray = array(); // creates an empty array and returns it.
		}
	}
	else {// on query failure
		$returnArray = false; 
	}
	return $returnArray;
}

function checkEmailAlreadyInDB($conn, $email){
	$email = sanitizeMySQL($conn, $email);
	$query = "SELECT * FROM `users` WHERE `Email` = '".$email."'";
	if (sizeof(fetchResult(mysqli_query($conn, $query))) == 1) {
		return true;
	}
	else {
		return false;
	}
}

function getMaxReservationID($conn){
	$query = "SELECT MAX(Id) AS maxID FROM `reservations`";
	return fetchResult(mysqli_query($conn, $query));
}