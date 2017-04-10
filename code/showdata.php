<?php
$sname = "localhost";
$uname = "root";
$pwd = "db.123";

$con = mysql_connect($sname,$uname,$pwd);

if (! $con){
	die(mysql_error());
}

mysql_select_db('tester');

$query = "SELECT * FROM `student`";
$result = mysql_query($query, $con);

echo "<center><h2>Relevant Data</h2><table border='2'>\n<tr>\n<th> Name </th>\n" . 
	"<th> DOB </th>\n<th> Roll </th>\n<th> Rnum </th>\n" . 
	"<th> CPI </th>\n<th> Grade </th>\n" . 
	"<th> Update CPI </th>\n<th> Delete </th>\n</tr>";

$i = "0";

while($row = mysql_fetch_array($result))
{
	echo "<tr>";
	echo "<td>" . $row['name'] . "</td>" . "<td>" . $row['dob'] . "</td>";
	echo "<td>" . $row['roll'] . "</td>" . "<td>" . $row['rnum'] . "</td>";
	echo "<td>" . $row['cpi'] . "</td>" . "<td>" . $row['grade'] . "</td>";
	echo "<td>" . "<form action='updatedata.php' method='post'>" .
			"<input type='hidden' name='roll' value=" . $row['roll'] . ">" . 
			"   <input type='submit' value='Update Data'></form>" . "</td>";
	echo "<td>" . "<form action='deleter.php' method='post'>" . 
			"<input type='hidden' name='roll' value=" . $row['roll'] . ">" . 
			"<input type='submit' value='Delete'></form>" . "</td>";
	echo "</tr>";
	$i = $i +1;
}

echo "</table></center>";

if($i=="0")
{
	echo "<center><br><br><h3>No Data to show</h3><br><br><br></center>";
}

echo "<br><br><center><form action='test.html' method='post'> <input type='submit' value='Go Back to Main Page'></form></center>";

mysql_close($con);
?>


