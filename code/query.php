<?php

@session_start();
include_once("header.html");

if(!isset($_SESSION['id'])) {
	echo"<script type='text/javascript'>alert('User not logged in!');</script>";
	die();
}

$sname = "127.0.0.1";
$uname = "root";
$pwd = "db.123";
$dbname = "nodues";

$connect = mysql_connect($sname, $uname, $pwd);

if (! $connect){
    die("NOT CONNECTED!\n" . mysql_error());
}

//Choose the required database 
mysql_select_db($dbname);

// Extract the department names
$query = "SELECT dname, name FROM department";
$result = mysql_query($query, $connect);

if(! $result) {
    die("Some error in query.php!\n" . mysql_error());
}

echo "
	<html>
	<body; font-family:'Dosis',serif;'>

		<br>

		<form action='queryDues.php' method='post'>
			<fieldset>
				<legend><h2 align='center'> Query Dues for a Department </h2> </legend>
				<br>Department Name : </b>
				<select style = 'margin-left: 1.6cm;width:30%;' multiple name='depList[]' size='3'>
	";

// Get the number of rows in the result
$num_rows = mysql_num_rows($result);

for ($i = 0; $i < $num_rows; $i++) {
	$tmpDetails = mysql_fetch_array($result);
	echo ("<option value=" . $tmpDetails['dname'] . ">" . $tmpDetails['name'] . "</option>\n");
}

echo "
				<option value='all'> All Departments </option>
				</select><br>
				<br>Initial Date : <input style = 'margin-left: 3.15cm;width:30%;' type='date'
						name='startDate' placeholder='dd-mm-yyyy' required/><br>
				<br>End Date : <input style = 'margin-left: 3.55cm;width:30%;' type='date'
						name='endDate' placeholder='dd-mm-yyyy' required/><br>
				<br><input type='submit'>
			</fieldset>
		</form>

		<br>

	</body>
	</html>
	";

?>