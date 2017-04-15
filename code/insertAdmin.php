<?php

@session_start();
include_once("header.html");
include_once("check.php");
require("sql_conn.php");

unset($_SESSION['insertAdmin']);
// Extract the department names
$query = "SELECT dname, name FROM department";
$result = mysql_query($query, $connect);

if(! $result) {
    die("Some error in query.php!\n" . mysql_error());
}

echo "
	<html>
	<body>

		<br>

		<form action='insertAdmin.php' method='post'>
			<fieldset>
				<legend><h2 align='center'> Insert Department Admin </h2> </legend>
                <table>
                <tr>
                <tr><td>Administrator Username : </td><td><input type='text' pattern = '[ a-zA-Z0-9]*'
                        name='uname' placeholder='Username' required/></td></tr>
                <tr><td>Administrator Name : </td><td><input type='text' pattern = '[ a-zA-Z]*'
                        name='name' placeholder='Administrator Name' required/></td></tr>
        		<td>Department Name : </b></td>
				<td><select name='dep' size='1' required>
	";

// Get the number of rows in the result
$num_rows = mysql_num_rows($result);

for ($i = 0; $i < $num_rows; $i++) {
	$tmpDetails = mysql_fetch_array($result);
	echo ("<option value=" . $tmpDetails['dname'] . ">" . $tmpDetails['name'] . "</option>\n");
}

echo "
	            </select></td></tr>
				<tr><td><input type='submit'></td></tr>
                </table>
			</fieldset>
		</form>

		<br>

	</body>
	</html>
	";

if (isset($_POST['dep'])){
    $uname = $_POST['uname'];
    $name  = $_POST['name'];
    $dname = $_POST['dname'];

    $userList = "SELECT username FROM profLogin";

    $flag = 0;
    while ($row = mysql_fetch_array($userList)) {
        if (strcmp($row['username'], $uname) == 0) {
            $flag = 1;
            break;
        }
    }

    if ($flag) {
        $query = "INSERT INTO admin (uname, name, dname, superFlag) VALUES ('$uname','$name', '$dname', 0)";
        $result = mysql_query($query, $connect);

        if(! $result) {
            die("Error during inserting in insertAdmin.php!\n" . mysql_error());
        }

        echo "<center><h2>New Administrator created!</h2>";
    }
    else {
        echo "<center><h2>User name given is not a user!</h2>";
    }
    // echo $query;

    echo "<br><br><br><center><form action='index.html' method='post'> <input type='submit' value='Go Back to Main Page'></form></center>";

    mysql_close($connect);
}
?>
