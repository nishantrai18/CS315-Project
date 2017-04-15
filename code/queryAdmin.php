<?php

@session_start();
include_once("header.html");
include_once("check.php");
require("sql_conn.php");

//Find out whether the admin has the right to modify this department
$adminname = $_SESSION['id'];
$query = "SELECT name, dname FROM admin WHERE uname = '$adminname'";
$result = mysql_query($query, $connect);

if(! $result)
    die("Some error in queryAdmin.php!\n" . mysql_error());

$row = mysql_fetch_array($result);
$aname = $row['name'];
$depname = $row['dname'];

// Extract the student details
$query = "SELECT sname, name FROM student";
$result = mysql_query($query, $connect);

if(! $result)
    die("Some error in queryAdmin.php!\n" . mysql_error());

// Query for a normal admin
echo "
	<html>
	<body>

		<br>

		<form action='queryAdmin.php' method='post'>
			<fieldset>
				<legend><h2 align='center'> Hi $aname, Query Dues for $depname Department </h2> </legend>
                <table>
                <tr>
                <tr><td>Student Name (ID) : </b>
                <td><select multiple name='studList[]' size='3' required>
    ";

// Get the number of rows in the result
$num_rows = mysql_num_rows($result);

for ($i = 0; $i < $num_rows; $i++) {
    $tmpDetails = mysql_fetch_array($result);
    echo ("<option value=" . $tmpDetails['sname'] . ">" . $tmpDetails['name'] . " (" . $tmpDetails['sname'] . ") " . "</option>\n");
}

echo "
				<option value='##all##'> All Students </option>
                </select></td></tr>
				<tr><td>Initial Date : </td><td><input type='date'
						name='startDate' placeholder='dd-mm-yyyy' required/></td></tr>
				<tr><td>End Date : </td><td><input type='date'
						name='endDate' placeholder='dd-mm-yyyy' required/></td></tr>
				<tr><td>Status : </td>
				<td><select name='stat'>
					<option value='due'>Uncleared Dues</option>
					<option value='clear'>Cleared Dues</option>
					<option value='all'>All Dues</option>
				</select></td></tr>
				<tr><td><input type='submit'></td></tr>
                </table>
			</fieldset>
		</form>

		<br>

	</body>
	</html>
	";

if (isset($_POST['startDate'])){
    $startDate = $_POST['startDate'];
    $endDate   = $_POST['endDate'];
    $stat      = $_POST['stat'];
    $statVal   = 0;

    // echo $stat;

    if (strcmp($stat, 'due') == 0)
        $statVal = 1;
    elseif (strcmp($stat, 'clear') == 0)
        $statVal = 0;
    elseif (strcmp($stat, 'all') == 0)
        $statVal = 2;
    else
        echo ("CRITICAL ERROR\n");

    //Choose the required database
    mysql_select_db($dbname);

    $query = "SELECT *
              FROM transactions as T
              ";

    // Adding date range query, guaranteed to be included
    $query = $query . "WHERE (T.date BETWEEN '$startDate' AND '$endDate')\n";
    $query = $query . "AND T.dname = '$depname'\n";

    $studArr = $_POST['studList'];
    $numStud = count($studArr);
    $flag = 0;

    # Finding the all department flag
    for($i = 0; $i < $numStud; $i++) {
        if (strcmp($studArr[$i], "##all##") == 0) {
            $flag = 1;
            break;
        }
    }

    # Check if all departments were queried else select the queried ones
    if ($flag == 0) {
        $query = $query . "AND T.sname IN (";
        for($i = 0; $i < $numStud; $i++) {
            if ($i > 0)
                $query = $query . ", '$studArr[$i]'";
            else
                $query = $query . "'$studArr[$i]'";
        }
        $query = $query . ")\n";
    }

    // echo $query;

    # Checking the transaction status
    if ($statVal != 2) {
        $query = $query . "AND ";
        $query = $query . "T.due = $statVal";
    }

    // echo $query;

    $result = mysql_query($query, $connect);

    if(! $result) {
        die("Some error in queryAdmin.php!\n" . mysql_error());
    }

    // Get the number of rows in the result
    // Not really required here
    $num_rows = mysql_num_rows($result);

    echo "<center><h2>Relevant Transactions</h2><table border='2'>\n<tr>\n<th> Student's Name </th>\n" .
        "<th> Value </th>\n<th> Date </th>\n<th> Remarks </th>\n<th> Status </th>\n\n";

    $i = 0;

    while($row = mysql_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . $row['sname'] . "</td>";
        echo "<td>" . $row['value'] . "</td>" . "<td>" . $row['date'] . "</td>";
        echo "<td>" . $row['remarks'] . "</td>" . "<td>" . $row['due'] . "</td>";
        echo "</tr>";
        $i = $i +1;
    }

    echo "</table></center>";

    if ($i == 0) {
        echo "<center><br><br><h3>No Data to show</h3><br><br><br></center>";
    }

    mysql_close($connect);
}
?>
