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
      <br><b>Username:</b> <input style = 'margin: 0 auto' pattern = '[ a-zA-Z0-9]*'
                     type='text' name='id' placeholder='CC Login' required/><br>
      <br><b>Password:</b> <input style = 'margin:0 auto' type='password'
                     name='pass' placeholder='Password' required/><br>
      <br><input type='submit' value='Login'>
</form>

";

if (isset($_POST['id'])){
session_start();
$id=$_POST['id'];
$pass=($_POST['pass']);
unset($_POST);

// Note that this condition is just added for testing purposes
// The condition implies access to users with id==password
// And whose id follows (contains) the format testuserX
$cond = (($id == $pass) and (strpos($id, 'test') !== false));
// Remove it in case we need to run it actually

// Original condition, only allows login using cc credentials
// if (@ftp_login(@ftp_connect("vyom.cc.iitk.ac.in"), $id, $pass))
// Replace the below with the above when we need to actually run it

if ($cond or (@ftp_login(@ftp_connect("vyom.cc.iitk.ac.in"), $id, $pass)))
{
    if (array_key_exists('id', $_SESSION))
    {
        if($_SESSION['id'] == $id){
        echo"<script type='text/javascript'>alert('User already logged in!');
             window.location.href='query.php';</script>";
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

        require("sql_conn.php");
        $query = "SELECT superFlag FROM admin WHERE uname='$id'";
        $result = mysql_query($query,$connect);
        $result = mysql_fetch_assoc($result);

        if ($result){
            $mode = $result['superFlag'];
            if ($mode == 0){
                $_SESSION['mode'] = 'Admin';
            }
            else{
                $_SESSION['mode'] = 'Super_Admin';
            }
        }

        else{
            $_SESSION['mode'] = 'Student';
        }
        header("Location: query.php");
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
