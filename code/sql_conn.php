<?php
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
?>
