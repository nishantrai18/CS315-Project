<?php

@session_start();
include_once("header.html");
include_once("check.php");
require("sql_conn.php");

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
				<br>Department Name : </b>
				<select style = 'margin-left: 1.6cm;width:30%;' multiple name='depList[]' size='3' required>
	";

// Get the number of rows in the result
$num_rows = mysql_num_rows($result);

for ($i = 0; $i < $num_rows; $i++) {
	$tmpDetails = mysql_fetch_array($result);
	echo ("<option value=" . $tmpDetails['dname'] . ">" . $tmpDetails['name'] . "</option>\n");
}

echo "
				<option value='##all##'> All Departments </option>
				</select><br>
				<br>Initial Date : <input style = 'margin-left: 3.15cm;width:30%;' type='date'
						name='startDate' placeholder='dd-mm-yyyy' required/><br>
				<br>End Date : <input style = 'margin-left: 3.55cm;width:30%;' type='date'
						name='endDate' placeholder='dd-mm-yyyy' required/><br>
				<br>Status : </b>
				<select style = 'margin-left: 4.15cm;width:30%;' name='stat'>
					<option value='due'>Uncleared Dues</option>
					<option value='clear'>Cleared Dues</option>
					<option value='all'>All Dues</option>
				</select><br>
				<br><input type='submit'>
			</fieldset>
		</form>

		<br>

	</body>
	</html>
	";

if (isset($_POST)){
    $startDate = $_POST['startDate'];
    $endDate   = $_POST['endDate'];
    $stat      = $_POST['stat'];
    $statVal   = 0;

    echo $stat;

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
              FROM transactions
              ";

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
        $query = $query . "WHERE dname IN (";
        for($i = 0; $i < $numDep; $i++) {
            if ($i > 0)
                $query = $query . ", '$depArr[$i]'";
            else
                $query = $query . "'$depArr[$i]'";
        }
        $query = $query . ")\n";
    }

    echo $query;

    # Checking the transcation status
    if ($statVal != 2) {
        if ($flag == 1)
            $query = $query . "WHERE ";
        else
            $query = $query . "AND ";
        $query = $query . "due = $statVal";
    }

    echo $query;

    $result = mysql_query($query, $connect);

    if(! $result) {
        die("Some error in queryDues.php!\n" . mysql_error());
    }

    // Get the number of rows in the result
    // Not really required here
    $num_rows = mysql_num_rows($result);

    echo "<center><h2>Relavant Transactions</h2><table border='2'>\n<tr>\n<th> Student Name </th>\n" .
        "<th> Department Name </th>\n<th> Value </th>\n<th> Date </th>\n" .
        "<th> Remarks </th>\n<th> Status </th>\n<th> Complain </th>\n";

    $i = 0;

    while($row = mysql_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . $row['sname'] . "</td>" . "<td>" . $row['dname'] . "</td>";
        echo "<td>" . $row['value'] . "</td>" . "<td>" . $row['date'] . "</td>";
        echo "<td>" . $row['remarks'] . "</td>" . "<td>" . $row['due'] . "</td>";
        echo "<td>" . "<form action='none.php' method='post'>" .
                "<input type='hidden' name='complain' value=" . $row['tid'] . ">" .
                "   <input type='submit' value='Complain'></form>" . "</td>";
        echo "</tr>";
        $i = $i +1;
    }

    echo "</table></center>";

    if($i == 0) {
        echo "<center><br><br><h3>No Data to show</h3><br><br><br></center>";
    }

    echo "<br><br><br><center><form action='index.html' method='post'> <input type='submit' value='Go Back to Main Page'></form></center>";

    mysql_close($connect);
}
?>
