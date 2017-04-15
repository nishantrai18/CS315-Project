<?php

@session_start();
include_once("header.html");
include_once("check.php");
require("sql_conn.php");

unset($_SESSION['insertDep']);
unset($_SESSION['complain']);
unset($_SESSION['insertAdmin']);
unset($_SESSION['insertTransaction']);
unset($_SESSION['queryStudent']);
unset($_SESSION['queryAdmin']);
// In case user goes back without logging in

// Extract the department names
$query = "SELECT dname, name FROM department";
$result = mysql_query($query, $connect);

if(! $result) {
    die("Some error in dash.php!\n" . mysql_error());
}

$uid  = $_SESSION['id'];
$mode = $_SESSION['mode'];

echo "
    <html>
    <body>

        <br>
        <h2 align='center'> Hi $uid, Access Level: $mode</h2>
        <br>
        <center>
        You have the following actions available to you. Please click one of them to proceed.
        <br><br>
	";

echo "<h2>Viewing Actions</h2>";

if ($_SESSION['mode'] == 'Student'){
    echo "
    <form action='queryStud.php' method='post'>
    <input type='hidden' name='queryStud' value='none'>
    <input type='submit' value='View your Due Records'></form>
    ";
}
else {
    echo "
    <form action='queryAdmin.php' method='post'>
    <input type='hidden' name='queryStud' value='none'>
    <input type='submit' value='View Due Records'></form>
    ";
}

echo "<br><legend><h2>High Level Actions</h2></legend>
      <h4>(Requires Profile Password)</h4>";

if ($_SESSION['mode'] == 'Student'){
    echo "
    <form action='profileLogin.php' method='post'>
    <input type='hidden' name='complain' value='none'>
    <input type='submit' value='Raise Complaints regarding Dues'></form>
    ";
}
else {

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

    echo "
    <form action='queryAdmin.php' method='post'>
    <input type='hidden' name='clearDues' value='none'>
    <input type='submit' value='Clear Student Dues'></form>
    ";
}

echo "</center>
      </body>
      </html>"

?>