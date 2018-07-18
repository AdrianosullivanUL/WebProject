<?php
	session_start();
	// redirect to the logon screen if the user is not logged in
	if ($_SESSION['user_logged_in'] == 0) {
		header("Location: Logon.php");
	}
	require_once 'database_config.php';
	include 'group05_library.php';
	$user_id = $_SESSION['user_id'];
	$matching_user_id = $_SESSION['matching_user_id'];

	$msg=$_POST['msg'];
	
	echo "user_id user no. " . $user_id . "<br>";
	echo "msg is " . $msg . "<br>";
	echo "matching_user_id use no. " . $matching_user_id . "<br>";
	$sql="insert into user_communications(from_user_id, to_user_id, message) values('$user_id', '$matching_user_id', '$msg')";
	$result = execute_sql_query($db_connection, $sql);
	echo $result;
	header("Location: ChatLine.php");
?>
