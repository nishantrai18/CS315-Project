<?php
echo "

<div style='height:10%'></div>

<div style='width:10%; margin:0 auto'>
<img src='static/img/iitklogo.jpg' style='width:100%'></img>
<h2 align='center'>No Dues Portal</h2>
</div>

<div style='margin: 0 auto; width:40%; text-align:center'>
<b id='error' style='color:red'></b>
<form action='login.php' method='post'>
      <br><b>Username:</b> <input style = 'margin: 0 auto' pattern = '[ a-zA-Z]*'
                     type='text' name='id' placeholder='CC Login' required/><br>
      <br><b>Password:</b> <input style = 'margin:0 auto' type='password'
                     name='pass' placeholder='Password' required/><br>
      <br><input type='submit'>
</form>

";

if (isset($_POST['id'])){
session_start();
$id=$_POST['id'];
$pass=($_POST['pass']);
unset($_POST);
if (@ftp_login(@ftp_connect("vyom.cc.iitk.ac.in"), $id, $pass))
{
    if (array_key_exists('id', $_SESSION))
    {
        if($_SESSION['id'] == $id){
        echo"<script type='text/javascript'>alert('User already logged in!');
             window.location.href='dash.html';</script>";
        }

        else{
        echo"<script type='text/javascript'>alert('Some other user is logged in!');
             </script>";
        die();
        }
    }

    else
    {
        session_destroy();
        session_start();
        $_SESSION['id'] = $id;
        header("Location: dash.html");

    }
}

else
{
    echo"<script type='text/javascript'>
    document.getElementById('error').innerHTML='Invalid Username/Password';
    </script>";
    unset($_POST);
	session_destroy();
	die();
}
}
?>
