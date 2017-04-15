<?php

@session_start();
include_once("header.html");
include_once("check.php");
require("sql_conn.php");

unset($_SESSION['modifyAdmin']);

// Extract the Admin details
$query = "SELECT uname, name FROM admin as A";
$result = mysql_query($query, $connect);

if(! $result) {
    die("Some error in modifyAdmin.php!\n" . mysql_error());
}

echo "
    <html>
    <body>

        <br>

        <form action='modifyAdmin.php' method='post'>
            <fieldset>
                <legend><h2 align='center'> Modify Administrator Rights </h2> </legend>
                <table>
                <tr>
                <tr><td>Admin Name (ID) : </b>
                <td><select name='uname' size='3' required>
    ";

// Get the number of rows in the result
$num_rows = mysql_num_rows($result);

for ($i = 0; $i < $num_rows; $i++) {
    $tmpDetails = mysql_fetch_array($result);
    echo ("<option value=" . $tmpDetails['uname'] . ">" . $tmpDetails['name'] . " (" . $tmpDetails['uname'] . ") " . "</option>\n");
}

echo "
                </select></td></tr>
                <td>New Department Name : </b>
                <td><select name='dname' size='1' required>
    ";

// Extract the student details
$query = "SELECT dname, name FROM department";
$result = mysql_query($query, $connect);

if(! $result) {
    die("Some error in modifyAdmin.php!\n" . mysql_error());
}

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

if (isset($_POST['dname'])){
    $uname = $_POST['uname'];
    $dname = $_POST['dname'];

    $query = "UPDATE admin as A SET A.dname = '$dname' WHERE A.uname = '$uname'";
    $flag = mysql_query($query, $connect);

    if ($flag) {
        echo "<center><h2>Administrator rights for $uname changed. Now holds rights for $dname.</h2>";
    }
    else {
        echo "<center><h2>Administrator rights could not be changed!</h2>";
    }
    // echo $query;

    mysql_close($connect);
}
?>
