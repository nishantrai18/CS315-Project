<?php

$sname = 'localhost';
$uname = 'root';
$pwd = 'mysql';

$con = mysql_connect($sname,$uname,$pwd);

if(! $con)
	die("COULD NOT CONNECT\n" . mysql_error());

$name = $_POST['name'];
$dob = $_POST['dob'];
$roll = $_POST['roll'];
$rnum = $_POST['rnum'];
$cpi = $_POST['cpi'];
$grade = "A";

if ($cpi < "2" )	$grade = "F";
elseif ($cpi < "4" )	$grade = "E";
elseif ($cpi < "6" )	$grade = "D";
elseif ($cpi < "8" )	$grade = "C";
elseif ($cpi < "10" )	$grade = "B";

mysql_select_db('tester');

$retval = mysql_query($query, $con);

$query = "DELETE FROM `student` WHERE `roll` = '$roll' ";
$retval = mysql_query($query, $con);

if(! $retval)
	die("ENTRY NOT FOUND\n" . mysql_error());

$comm = "INSERT INTO `student` (`name`,`dob`,`roll`,`rnum`,`cpi`,`grade`) VALUES ('$name','$dob','$roll','$rnum','$cpi','$grade')";

$retval = mysql_query($comm, $con);

if(! $retval)
	die("NOT UPDATED\n" . mysql_error());

echo "UPDATED NICELY\n";

echo "<br><center><form action='test.html' method='post'> <input type='submit' value='Go to Main Page'></form></center>";

echo "<br><center><form action='showdata.php' method='post'> <input type='submit' value='See the Updated Data'></form></center>";

mysql_close($con);
?>
