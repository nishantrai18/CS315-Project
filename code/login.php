<?php
session_start();
$id=$_POST['id'];
$pass=($_POST['pass']);

if (@ftp_login(@ftp_connect("vyom.cc.iitk.ac.in"), $id, $pass))
{
    if (array_key_exists('id', $_SESSION))
    {
        if($_SESSION['id'] == $id){ 
        echo"<script type='text/javascript'>alert('User already logged in!'); 
             window.location.href='dash.html';</script>";
        }

        else{
        echo"<script type='text/javascript'>alert('Some other user is logged in!');
             window.location.href='login.html';</script>";
        die();
        }
    }

    else
    {
        session_destroy();
        session_start();
        $_SESSION['id'] = $id;
    }
}

else 
{
	echo"Invalid username or password";
	session_destroy();
	die();
}
		
?>
