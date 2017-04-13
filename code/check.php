<?php

if(!isset($_SESSION['id'])) {
	echo"<script type='text/javascript'>alert('User not logged in!');
    window.location.href='login.php';</script>;";
}

?>
