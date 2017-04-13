<?php

if(!isset($_SESSION['id'])) {
	echo"<script type='text/javascript'>alert('User not logged in!');</script>";
	die();
}

?>