<?php

@session_start();
include_once("header.html");
include_once("check.php");
require("sql_conn.php");

unset($_SESSION['insertDep']);
unset($_SESSION['complain']);
unset($_SESSION['insertAdmin']);
unset($_SESSION['insertTransaction']);
// In case user goes back without logging in

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

		<form action='query.php' method='post'>
			<fieldset>
				<legend><h2 align='center'> Query Dues for a Department </h2> </legend>
                <table>
                <tr>
        		<td>Department Name : </b></td>
				<td><select multiple name='depList[]' size='3' required>
	";

// Get the number of rows in the result
$num_rows = mysql_num_rows($result);

for ($i = 0; $i < $num_rows; $i++) {
	$tmpDetails = mysql_fetch_array($result);
	echo ("<option value=" . $tmpDetails['dname'] . ">" . $tmpDetails['name'] . "</option>\n");
}

echo "
				<option value='##all##'> All Departments </option>
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

if ($_SESSION['mode'] != 'Student'){
    echo "<h2>Super Powers</h2>";

    if ($_SESSION['mode'] == 'Super_Admin'){
    echo "
    <form action='profileLogin.php' method='post'>
    <input type='hidden' name='insertDep' value='none'>
    <input type='submit' value='Add New Department'></form>
    ";
    echo "
    <form action='profileLogin.php' method='post'>
    <input type='hidden' name='insertAdmin' value='none'>
    <input type='submit' value='Add New Admin'></form>
    ";
    echo "
    <form action='profileLogin.php' method='post'>
    <input type='hidden' name='modifyAdmin' value='none'>
    <input type='submit' value='Modify Admin rights'></form>
    ";
    }

    echo "
    <form action='profileLogin.php' method='post'>
    <input type='hidden' name='insertTransaction' value='none'>
    <input type='submit' value='Record New Transaction'></form>
    ";


}

if (isset($_POST['startDate'])){
    $startDate = $_POST['startDate'];
    $endDate   = $_POST['endDate'];
    $stat      = $_POST['stat'];
    $statVal   = 0;

    $username = $_SESSION['id'];
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
    $query = $query . "AND T.sname = '$username'\n";

    $depArr = $_POST['depList'];
    $numDep = count($depArr);
    $flag = 0;

    # Finding the all department flag
    for($i = 0; $i < $numDep; $i++) {
        if (strcmp($depArr[$i], "##all##") == 0) {
            $flag = 1;
            break;
        }
    }

    # Check if all departments were queried else select the queried ones
    if ($flag == 0) {
        $query = $query . "AND T.dname IN (";
        for($i = 0; $i < $numDep; $i++) {
            if ($i > 0)
                $query = $query . ", '$depArr[$i]'";
            else
                $query = $query . "'$depArr[$i]'";
        }
        $query = $query . ")\n";
    }

    // echo $query;

    # Checking the transcation status
    if ($statVal != 2) {
        $query = $query . "AND ";
        $query = $query . "T.due = $statVal";
    }

    // echo $query;

    $result = mysql_query($query, $connect);

    if(! $result) {
        die("Some error in queryDues.php!\n" . mysql_error());
    }

    // Get the number of rows in the result
    // Not really required here
    $num_rows = mysql_num_rows($result);

    echo "<center><h2>Relevant Transactions</h2><table border='2'>\n<tr>\n<th> Student Name </th>\n" .
        "<th> Department Name </th>\n<th> Value </th>\n<th> Date </th>\n" .
        "<th> Remarks </th>\n<th> Status </th>\n<th> Complain </th>\n";

    $i = 0;

    while($row = mysql_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . $row['sname'] . "</td>" . "<td>" . $row['dname'] . "</td>";
        echo "<td>" . $row['value'] . "</td>" . "<td>" . $row['date'] . "</td>";
        echo "<td>" . $row['remarks'] . "</td>" . "<td>" . $row['due'] . "</td>";
        echo "<td>" . "<form action='profileLogin.php' method='post'>" .
                "<input type='hidden' name='complain' value=" . $row['tid'] . ">" .
                "<input type='submit' value='Complain'></form>" . "</td>";
        echo "</tr>";
        $i = $i +1;
    }

    echo "</table></center>";

    if($i == 0) {
        echo "<center><br><br><h3>No Data to show</h3><br><br><br></center>";
    }

    mysql_close($connect);
}
?>
