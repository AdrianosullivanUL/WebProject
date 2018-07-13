<?php

 session_start();
 $msg=$_POST['msg'];
 $name=$_SESSION['name']
 echo "Name is: " $name . " Message is: " . $msg;
 $sql="insert into user_communications(from_user_id,message) values('$name','$msg')";
 $result=$conn->query($sql);
 echo $result;
?>
