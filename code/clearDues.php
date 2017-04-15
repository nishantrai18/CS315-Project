<?php

@session_start();
include_once("header.html");
include_once("check.php");
require("sql_conn.php");

$tid = $_SESSION['clearDues'];
$_POST['tid'] = $tid;
unset($_SESSION['clearDues']);

if (isset($_POST['tid'])) {    

    $query = "SELECT T.sname as tsname, T.dname as tdname, T.value as value, T.due as due, S.name as sfullname,
              D.name as dfullname FROM department as D, student as S, transactions as T
              WHERE T.sname = S.sname AND T.dname = D.dname AND T.tid = $tid";

    $result = mysql_query($query, $connect);

    if (!$result) {
        die("Error in clearDues.php!\n" . mysql_error());
    }

    $row = mysql_fetch_array($result);
    $studname = $row['sfullname'];
    $depname = $row['dfullname'];
    $val = $row['value'];
    $due = $row['due'];

    echo "<h2>Clearing Dues, Details of the transaction:</h2>";
    echo "<h2>Student Name: $studname</h2>";
    echo "<h2>Department Name: $depname</h2>";
    echo "<h2>Amount dued: $val</h2>";
    echo "<h2>Transaction ID: $tid</h2>";

    if ($due) {
        $query = "UPDATE transactions SET due = 0 WHERE tid = $tid";
        $result = mysql_query($query, $connect);

        if(! $result) {
            die("Error clearing dues to the transactions, clearDues.php!\n" . mysql_error());
        }

        echo "<center><h2>Transaction has been cleared!</h2>";
    }
    else {
        echo "<center><h2>Transaction has already been cleared!</h2>";
    }

    mysql_close($connect);
}
?>
