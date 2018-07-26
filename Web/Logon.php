<?php
require_once 'database_config.php';
include 'group05_library.php';
session_start();
$_SESSION['user_logged_in'] = 0;
$_SESSION['is_administrator'] = 0;
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $logon = 0;
    // check the button selected (these are at the end of this form
    if ($_POST['btnAction'] == "ForgotPassword") { // Call Edit Profile
        header("Location: PasswordReset.php");
        exit();
    }
        if ($_POST['btnAction'] == "Cancel") { // Call Edit Profile
        header("Location: index.php");
        exit();
    }

    if ($_POST['btnAction'] == "Logon") { // Call Edit Profile
        $email = $_POST['email'];
        $password = $_POST['password'];
        $isAdmin = 0;
        $sql = "select * from user_profile where LOWER(trim(email)) = trim('" . strtolower($email) . "') and password_hash = sha2('" . $password . "',256);";
        //echo $sql;
        if ($result = mysqli_query($db_connection, $sql)) {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    $logon = 1;
                    $_SESSION['user_id'] = $row['id'];
                    if ($row['is_administrator'] == 1) {
                        $isAdmin = 1;
                    }
                }
            }
            if ($logon == 1) {
                echo "is admin" . $isAdmin;
                if ($isAdmin == 1) {
                    $_SESSION['user_logged_in'] = 1;
                    $_SESSION['is_administrator'] = $isAdmin;
                    header("Location: AdminScreen.php");
                    exit();
                } else {
                    $_SESSION['user_logged_in'] = 1;
                    header("Location: MeetingSpace.php");
                    exit();
                }
            } else {
                $message = 'Logon failed, please ensure you are entering the correct email address and password';
            }
        }
    }
} else {
    $message = 'Please input your email address and password and then press logon';
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Logon</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  
        <link rel="stylesheet" href="StyleSheet.css">
    </head>
    <body>
        <div class="topnav">
            <a class="active">LOG ON</a>
        </div>        
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3" >

                    <form method="post" name="challenge"  class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" >
                        <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .35em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .8;">

                            <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Log On Details</legend>

                            <div class="form-group"></div>
                            <label for="email">Email</label>
                            <input type="text" class="form-control" name="email" placeholder="">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="">
                            <?php
                            if (strlen($message) > 0) {
                                echo "<div class='alert alert-danger'>";
                                echo "<p>" . $message . "</p>";
                                echo "</div>";
                            }
                            ?>


                            <button name="btnAction" class="btn btn-primary" type="submit" value="Logon">Log on</button>
                            <button name="btnAction" class="btn btn-danger" type="submit" value="ForgotPassword">Forgot Password?</button>
                            <button name="btnAction" class="btn btn-warning" type="submit" value="Cancel">Cancel</button>

                        </fieldset> 

                    </form>
                </div>
            </div>

        </div>        
    </body>
</html> 