<?php

$sname = "localhost";
$uname = "root";
$pwd = "mysql";

$con = mysql_connect($sname,$uname,$pwd);

if (! $con){
	die(mysql_error());
}

mysql_select_db('tester');

$roll =  $_POST['roll'];

$query = "SELECT * FROM `student` WHERE `roll` = '$roll' ";
$result = mysql_query($query, $con);

$i = "0";

while($row = mysql_fetch_array($result))
{
	echo "<form action='update.php' method='post'>" . 
		"<fieldset> <legend><h2 align='center'> Form Submit </h2> </legend>";
	echo "<br><h3>Roll number : " . $row['roll'] . " </h3>";
	echo "<input type='hidden' name='roll' value=" . $row['roll'] . ">"; 
	echo "<br>Name : <input style = 'margin-left: 2.8cm;width:30%;' pattern = '[ a-zA-Z]*' " . 
			 			"type='text' name='name' placeholder=" . $row['name'] . " value=" . $row['name'] . "><br>";
	echo "<br>Date of Birth : <input style = 'margin-left: 1.6cm;width:30%;' type='date' " . 
						"name='dob' placeholder=" . $row['dob'] . " value=" . $row['dob'] . "><br>";
	echo "<br>Registration Number : <input style = 'margin-left: 0.3cm;width:30%;' pattern = '[0-9a-zA-Z]{5,5}' " . 
			 			"type='text' name='rnum' placeholder=" . $row['rnum'] . " value=" . $row['rnum'] . "><br>";
	echo "<br>CPI : <input type='number' style = 'margin-left: 3.2cm;width:30%;' step = '0.01' min='0.00' max='10.0' " .  							"name='cpi' placeholder=" . $row['cpi'] . " value=" . $row['cpi'] . "><br>";
	echo "<br><input type='submit'></fieldset></form>";
	$i = $i +1;
}

if($i=="0")
{
	echo "<center><br><br><h3>No Data to show</h3><br><br><br></center>";
}

echo "<center><form action='test.html' method='post'> <input type='submit' value='Go Back to Main Page'></form></center>";

?>
