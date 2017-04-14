<?php

@session_start();
include_once("header.html");
include_once("check.php");

echo "
<div style='height:10%'></div>

<div style='width:10%; margin:0 auto'>
<img src='static/img/iitklogo.jpg' style='width:100%'></img>
<h3 align='center'>Profile Login</h3>
</div>
<div style='margin: 0 auto; width:40%; text-align:center'>
<b id='error' style='color:red'></b>
<form action='profileLogin.php' method='post'>
      <br><b>Profile Password:</b> <input style = 'margin:0 auto' type='password'
                     name='pass' placeholder='Profile Password' required/><br>
      <br><input type='submit' value='Login'>
</form>
";

if (isset($_POST['complain'])){
    $_SESSION['complain']=$_POST['complain'];
}

if(isset($_POST['pass'])){
require("sql_conn.php");

$userName = $_SESSION['id'];
$userPass = $_POST['pass'];

// Extract the decrypted passwords and usernames
// Note that the table contains entries 'username' and 'password'
$query = "SELECT aes_decrypt(password, 'some_secret_key') AS password FROM profLogin
          WHERE username='$userName'";
$result = mysql_query($query, $connect);

if(!$result) {
    echo"<script type='text/javascript'>
    document.getElementById('error').innerHTML='Profile Login Failed';
    </script>";
    mysql_close($connect);
    die();
}

$result = mysql_fetch_assoc($result);
if (strcmp($result['password'], $userPass) == 0) {
    if (array_key_exists('complain',$_SESSION)){
        header("Location: email.php");
    }
}

else{
    echo"<script type='text/javascript'>
    document.getElementById('error').innerHTML='Profile Login Failed';
    </script>";
    mysql_close($connect);
    die();
}

}
?>
