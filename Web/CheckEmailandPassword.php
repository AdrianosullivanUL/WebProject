<?php

define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'group05'); 

    // Attempt to connect to MySQL database
    $db_connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check connection
    if($db_connection === false){
        die("ERROR: Problem connecting to database, error details: " . mysqli_connect_error());
    }

$uname=$_POST['uname'];
$pass=$_POST['pass'];


$sql = "select password_hash from user_profile where email=$uname;";
$result = $db_connection->query($sql);
$row = $result->fetch_assoc();
print "<P>password_hash for this email is {$row["password_hash"]}</P>";
		
//$result=$conn->query($sql);


if(!$row=$result->fetch_assoc()){
	header("Location:Register.php");
	
} else {
	
	$_SESSION['name']=$_POST['uname'];
	header("Location:UpdateProfile.php");
}

?>