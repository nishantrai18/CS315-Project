<?php

$sname = "127.0.0.1";
$uname = "root";
$pwd = "db.123";
$dbname = "cs252test";

$connect = mysql_connect($sname,$uname,$pwd);

if (! $connect){
    die("NOT CONNECTED!\n" . mysql_error());
}

$userName = $_POST['id'];
$userPass = $_POST['pass'];

//Choose the required database 
mysql_select_db($dbname);

// Extract the decrypted passwords and usernames
// Note that the table contains entries 'username' and 'password'
$query = "SELECT aes_decrypt(password, 'some_secret_key') AS password, username FROM profLogin";
$result = mysql_query($query, $connect);

if(! $result) {
    die("Some error in profileLogin.php!\n" . mysql_error());
}

// Get the number of rows in the result
$num_rows = mysql_num_rows($result);
 
//Loop through the rows of table to check the password 
while($num_rows > 0) {

    $tmpDetails = mysql_fetch_array($result);
    $tmpName    = $tmpDetails["username"];
    $tmpPass    = $tmpDetails["password"];
    $flag       = 0;

    if(strcmp($tmpName, $userName) == 0) {
        if(strcmp($tmpPass, $userPass) == 0) {
            $flag = 1;
            break;
        }
    }
         
    $num_rows--;
 
 }

if($flag == 1)
    echo "<font color='green'> Successful Login! </font>";
else
    echo "<font color='red'> Unsuccessful Login! </font>";

echo "<br><br><br><center><form action='index.html' method='post'> <input type='submit' value='Go Back to Main Page'></form></center>";

mysql_close($connect);

?>