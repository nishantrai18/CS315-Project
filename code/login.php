<?php
session_start();
$id=$_POST['id'];
$pass=($_POST['pass']);

if (@ftp_login(@ftp_connect("vyom.cc.iitk.ac.in"), $id, $pass))
{
	if (array_key_exists('id', $_SESSION) && $_SESSION['id'] == $id) 
	{
		echo"<script type='text/javascript'>alert('User already logged in!');</script>";
	}

	else
	{
		session_destroy();
		session_start();
		$_SESSION['id'] = $id;
	}
	echo"Wonderful! Login successful!";	
}
else 
{
	echo"Invalid username or password";
	session_destroy();
	die();
}
		
?>