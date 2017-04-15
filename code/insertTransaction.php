<?php

@session_start();
include_once("header.html");
include_once("check.php");
require("sql_conn.php");

unset($_SESSION['insertTransaction']);

// Extract the student details
$query = "SELECT sname, name FROM student";
$result = mysql_query($query, $connect);

if(! $result) {
    die("Some error in insertTransaction.php!\n" . mysql_error());
}

echo "
	<html>
	<body>

		<br>

		<form action='insertTransaction.php' method='post'>
			<fieldset>
				<legend><h2 align='center'> Insert Transaction </h2> </legend>
                <table>
                <tr>
                <tr><td>Student Name (ID) : </b>
                <td><select name='sname' size='3' required>
    ";

// Get the number of rows in the result
$num_rows = mysql_num_rows($result);

for ($i = 0; $i < $num_rows; $i++) {
    $tmpDetails = mysql_fetch_array($result);
    echo ("<option value=" . $tmpDetails['sname'] . ">" . $tmpDetails['name'] . " (" . $tmpDetails['sname'] . ") " . "</option>\n");
}

echo "
                </select></td></tr>
        		<td>Department Name : </b>
				<td><select name='dname' size='1' required>
	";

// Extract the student details
$query = "SELECT dname, name FROM department";
$result = mysql_query($query, $connect);

if(! $result) {
    die("Some error in insertTransaction.php!\n" . mysql_error());
}

// Get the number of rows in the result
$num_rows = mysql_num_rows($result);

for ($i = 0; $i < $num_rows; $i++) {
	$tmpDetails = mysql_fetch_array($result);
	echo ("<option value=" . $tmpDetails['dname'] . ">" . $tmpDetails['name'] . "</option>\n");
}

echo "
	            </select></td></tr>
                <tr><td>Date of Transaction : <input type='date' name='date' placeholder='dd-mm-yyyy' required/></td>
                <tr><td>Transaction Amount : <input type='number' name='value' placeholder='Due/Credit Amount' required/></td>
                <tr><td>Remarks : <input type='text' name='remarks' placeholder='Any Additional Remarks'/></td>
				<tr><td><input type='submit'></td></tr>
                </table>
			</fieldset>
		</form>

		<br>

	</body>
	</html>
	";

if (isset($_POST['dname'])){
    $sname   = $_POST['sname'];
    $dname   = $_POST['dname'];
    $value   = $_POST['value'];
    $date    = $_POST['date'];
    $remarks = $_POST['remarks'];

    //Find out whether the admin has the right to modify this department
    $adminname = $_SESSION['id'];
    $query = "SELECT name, dname FROM departments WHERE uname = '$adminname'";
    $result = mysql_query($query, $connect);
    $row = mysql_fetch_array($result);
    $allow = 0;
    if ($row['dname'] == $dname) {
        $allow = 1;
    }

    if ($allow == 0) {
        echo "<center><h2>Admin does not belong to this department!</h2>";
    }

    // Find out whether the department entered is a misc one or not
    $query = "SELECT dname, miscFlag FROM departments WHERE dname = '$dname'";
    $result = mysql_query($query, $connect);
    $row = mysql_fetch_array($result);
    $miscFlag = $row['miscFlag'];

    // Find out the allowed department of the student (Done only in case the dep isn't misc)
    $flag = 1;
    if ($miscFlag == 0) {
        $query = "SELECT sname, dname FROM student WHERE sname = '$sname'";
        $result = mysql_query($query, $connect);

        $row = mysql_fetch_array($result);

        if (strcmp($row['dname'], $dname) != 0)
            $flag = 0;
    }

    if ($flag) {
        $query = "INSERT INTO transactions VALUES (0, '$sname','$dname', $value, '$date', '$remarks', 1)";
        $result = mysql_query($query, $connect);

        if(! $result) {
            die("Error during inserting in insertTransaction.php!\n" . mysql_error());
        }

        echo "<center><h2>New Transaction added!</h2>";
    }
    else {
        echo "<center><h2>Student selected does not belong to this department!</h2>";
    }
    // echo $query;


    mysql_close($connect);
}
?>
