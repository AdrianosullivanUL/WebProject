 <!DOCTYPE html>
<html lang="en">
<head>
  <title>Logon Screen</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h1>Login Details</h1>
  
  <form action="UpdateProfile.php" method="post">
  <label><b>email address</b></label>
  <input type="text" name="uname" placeholder="email address"><br><br>
  <label><b>Password</b></label>
  <input type="text" name="pass" placeholder="password"><br><br>
  
  <a href="ResetPassword.php">Forgot password</a><br><br>
  
  <button style="background-color: #6495ed;color: white;
  " type="submit"><b>Login</b></button>
  </form>
  
</div>

</body>
</html> 