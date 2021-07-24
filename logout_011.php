<?php
	session_start(); //Initialize the session
	$_SESSION = array(); //Unset the session variables
	if (ini_get("session.use_cookies")) { //Delete the session cookie:
		$params = session_get_cookie_params(); // This is required to delete the
		setcookie(session_name(), '', time() - 42000, // session data and destroy the session
		$params["path"], $params["domain"],
		$params["secure"], $params["httponly"]);
	}
	session_destroy(); //Destroy the session -- well, it destroys all data registered to a session
	header("location: role_011.html");
?>