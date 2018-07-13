 <!DOCTYPE html>
<html lang="en">
<head>
  <title>chatline</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <style>
	#main{
		height: 455px;
		background-color: white;
		margin-top: 70px;
	}
	.output{
		background-color: white;
		box-shadow: 0px 1px 1px #000;
		height: 250px;
		margin-bottom: 20px;
		overflow: scroll;		
	}

	ul{
		list-style:none;
	}

	input[type=submit]{
		width: 100px;
		box-sizing: border-box;
		border:4px solid #6495ed;
		border-radius: 4px;
	}

	textarea{
		background-color: #dcdcdc;
		width: 350px;
	}

  </style>

</head>
<body>


<div class="container">
  <h1>chatline Screen</h1>
</div>

<div id="main">

<h1 style="background-color: #6495ed;color: white;"><?php echo $_SESSION['$user_id']?>-online</h1>
	<div class="output">
	
		<?php   
				// session_start();
				// $user_id = $_SESSION['user_id'];
				// $matching_user_id = $_SESSION['matching_user_id'];
				// echo "session user " . $user_id;
				// $sql = "SELECT * from user_communication WHERE " . $to_user_id ." = " . $matching_user_id ." AND " . $from_user_id ." =" . $user_id ." ;";
				// if ($result->num_rows > 0) {
					//output data of each row
					// while($row = $result->fetch_assoc()){
						// echo "" . $row["message"]." --" .$row["date"]. "<br>";
						// echo "<br>";
					// }
				// } else {
					// echo "0 results";
				// }
				// $conn->close();
		?>


	</div>

<form method="post" action="Send.php">
<textarea name="msg" placeholder="Type to send message...."
class="form-control"></textarea><br>
<input type="submit" value="Send">
</form>
<br>
<form action="Logout.php">

<input stype="width: 100%;background-color: #6495ed;color:
    white;font-size: 20px;" type="submit" value="Logout">
</form>

</div>

</body>
</html> 
