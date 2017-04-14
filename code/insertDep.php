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

        <form action='insertDep.php' method='post'>
            <fieldset>
                <legend><h2 align='center'> Insert Department </h2> </legend>
                <table>
                <tr>
                <tr><td>Department Unique Name : </td><td><input type='text' pattern = '[ a-zA-Z0-9]*'
                        name='dname' placeholder='Username' required/></td></tr>
                <tr><td>Department's Name : </td><td><input type='text' pattern = '[ a-zA-Z]*'
                        name='name' placeholder='Administrator Name' required/></td></tr>
                <tr><td>Department Type : </td>
                <td><select name='miscFlag'>
                    <option value=0>Academic Department</option>
                    <option value=1>Miscellaneous Department</option>
                </select></td></tr>
                <tr><td><input type='submit'></td></tr>
                </table>
            </fieldset>
        </form>

        <br>

    </body>
    </html>
    ";

if (isset($_POST['name'])){
    $dname = $_POST['dname'];
    $name  = $_POST['name'];
    $miscFlag = $_POST['miscFlag'];

    $query = "SELECT dname FROM department";
    $depList = mysql_query($query, $connect);

    $flag = 1;
    while ($row = mysql_fetch_array($depList)) {
        if (strcmp($row['dname'], $dname) == 0) {
            $flag = 0;
            break;
        }
    }

    if ($flag) {
        $query = "INSERT INTO department (dname, name, miscFlag) VALUES ('$dname', '$name', '$miscFlag')";
        $result = mysql_query($query, $connect);

        if(! $result) {
            die("Error during inserting in insertDep.php!\n" . mysql_error());
        }

        echo "<center><h2>New Department created!</h2>";
    }
    else {
        echo "<center><h2>Department Unique Name has already been taken!</h2>";
    }

    echo "<br><br><br><center><form action='index.html' method='post'> <input type='submit' value='Go Back to Main Page'></form></center>";

    mysql_close($connect);
}
?>
