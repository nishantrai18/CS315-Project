<?php

$sname = 'localhost';
$uname = 'root';
$pwd = 'db.123';

$con = mysql_connect($sname,$uname,$pwd);

if(! $con)
	die("COULD NOT CONNECT\n" . mysql_error());

$roll =  $_POST['roll'];

$query = "DELETE FROM `student` WHERE `roll` = '$roll' ";

mysql_select_db('cs252test');

$retval = mysql_query($query, $con);

if(! $retval)
	die("NOT DELETED\n" . mysql_error());

echo "DELETED\n";

echo "<br><center><form action='index.html' method='post'> <input type='submit' value='Go Back to Main Page'></form></center>";
echo "<br><center><form action='showdata.php' method='post'> <input type='submit' value='See the Updated Data'></form></center>";

mysql_close($con);
?>
