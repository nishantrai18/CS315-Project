<?php

@session_start();
include_once("check.php");
include_once('header.html');

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

echo"
    <form action='email.php' method='post'>
    <center>
    <table>
    <th> Complaint Form </th>
    <tr>
    <td> Transaction ID: </td>
    <td> <input type='number', name='tid'></input></td>
    <td id='tid_error'></td>
    </select>
    </tr>
    <td> Complaint: </td>
    <td> <textarea name='complaint' cols='65' rows='7'></textarea></td>
    <tr>
    <td> <input type='submit' value='Submit'> </td>
    <td id='submit_error'></td>
    </tr>
    </table>
";

if(isset($_POST['tid'])){

$email=$_SESSION['id'];
$tid=($_POST['tid']);
$complaint=($_POST['complaint']);

// Extract the department names
$query = "SELECT dname FROM transactions
          WHERE tid=$tid";
$result = mysql_query($query, $connect);
$result = mysql_fetch_assoc($result);
if ($result){
$dept = $result['dname'];
}
else{
    die("Please check Transaction ID!");
}

$body="
Dear Admin,\n

The following student has issues with the no-dues, please follow up:
Email:      $email@iitk.ac.in \n
Department of Issue: $dept \n
Complaint:  $complaint \n
Please reply to him. \n
\n
";

require("class.phpmailer.php");
require("class.smtp.php");
$mail=new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth=true;
$mail->Username="mshivam";
$mail->Password="neelam";
$mail->Host="172.31.1.22";
$mail->From=$email."@iitk.ac.in";
$mail->FromName=$email;
$mail->Subject="No Dues Complaint";
$mail->Body=$body;
$mail->WordWrap=80;

$query = "SELECT uname FROM admin WHERE dname IS NULL OR dname='$dept'";
$result = mysql_query($query, $connect);

if ($result){
    $num_rows = mysql_num_rows($result);

    for ($i = 0; $i < $num_rows; $i++) {
	    $tmpDetails = mysql_fetch_array($result);
        $mail->AddAddress($tmpDetails['uname']."@iitk.ac.in");
        echo $tmpDetails['uname']."@iitk.ac.in";
    }

    if(!$mail->Send()){
        echo "Message was not sent. Please try again later!";
    }
    else
    {
        echo "Message has been sent";
    }
}

else{
    echo "Internal Error!";
}
}
?>
