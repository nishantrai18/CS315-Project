<?php
$sname = "localhost";
$uname = "root";
$pwd = "mysql";

$connect = mysql_connect($sname,$uname,$pwd);

if (! $connect){
	die("NOT CONNECTED\n" . mysql_error());
}

echo ("CONNECTED\n");

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

$comm = "INSERT INTO `student` (`name`,`dob`,`roll`,`rnum`,`cpi`,`grade`) VALUES ('$name','$dob','$roll','$rnum','$cpi','$grade')";

mysql_select_db('tester');
$retval = mysql_query( $comm, $connect );

if(! $retval)
{
	die("DATA ENTRY FAILURE\n" . mysql_error());
}

echo "ENTERED DATA\n";

echo "<br><center><form action='test.html' method='post'> <input type='submit' value='Go Back to Main Page'></form></center>";
echo "<br><center><form action='showdata.php' method='post'> <input type='submit' value='See the Updated Data'></form></center>";

mysql_close($connect);
?>
 
