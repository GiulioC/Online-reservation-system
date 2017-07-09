<?php

function startSession(){
	session_start();
	if (isset($_SESSION['time']) && !checkTime() && isset($_SESSION['username'])){
		destroySession();
		session_start();
		header("location: errorPage.php?err=sessionExp");
	}
	updateTime();
	/*if (!isset($_COOKIE['myCookie'])){
		createCookie();
	}*/
}

function createCookie(){
	setcookie("myCookie", md5(rand()), 0, "/", null, false, true);
}

function login($user, $pass){
	updateTime();
	$_SESSION['username'] = $user;
	$_SESSION['password'] = $pass;
	$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	$_SESSION['id'] = session_id();
}

function checkLoggedIn(){
	if (isset($_SESSION['username']) && isset($_SESSION['password'])){
		return true;
	}
	else {
		return false;
	}
}

function updateTime(){
	$_SESSION['time'] = time();
}

function logout(){
    unset($_SESSION["username"]);
    unset($_SESSION["password"]);
    unset($_SESSION["ip"]);
    unset($_SESSION["id"]);
    destroySession();
    $redirect = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	header("Location: ".$redirect);
}

function destroySession(){
	session_unset();
	session_destroy();
}

//returns true if session has not expired, false otherwise
function checkTime(){
	global $config;
	if(time() - $_SESSION['time'] > $config['session']['duration']){
		return false;
	}
	else {
		return true;
	}
}

function checkSessionValidity(){
	//prevents session hijacking
	if ($_SERVER['REMOTE_ADDR'] != $_SESSION['ip']){
		header("Location: errorPage.php?err");
	}
	//prevents session fixation
	if ($id = session_ID() != $_SESSION['id']){
		header("Location: errorPage.php?err");
	}
}