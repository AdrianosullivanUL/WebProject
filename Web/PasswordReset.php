<?php
require_once 'database_config.php';
include 'group05_library.php';
session_start();
$_SESSION['user_logged_in'] = 0;
$_SESSION['is_administrator'] = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emailfound = 0;
    // check the button selected (these are at the end of this form
    if ($_POST['btnAction'] == "SendReset") { // Call Edit Profile
        $email = $_POST['email'];
        $password = $_POST['password'];
        $sql = "select * from user_profile where email = '" . $email . "';";
        if ($result = mysqli_query($db_connection, $sql)) {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    $emailfound = 1;
                    $_SESSION['user_id'] = $row['id'];
                    if ($row['is_administrator'] == 1) {
                        $_SESSION['is_administrator'] = 1;
                    }
                }
            }
            if ($emailfound == 1) {
                ini_set("SMTP", "aspmx.l.google.com");
                ini_set("sendmail_from", "donotreply@chance.com");

                $message = "Password reset email setting:\r\nSMTP = aspmx.l.google.com\r\nsmtp_port = 25\r\nsendmail_from = donotreply@chance.com";

                $headers = "From: YOURMAIL@gmail.com";


                mail("Sending@provider.com", "Testing", $message, $headers);
                echo "Check your email now....<BR/>";
                header("Location: MeetingSpace.php");
                exit();
            } else {
                $message = 'This email address is not registered';
            }
        }
        if ($_POST['btnAction'] == "ForgotPassword") { // Call Edit Profile
            header("Location: PasswordReset.php");
            exit();
        }
    }
} else {
    $message = 'Please input your email address and an email will be sent to you to allow for a password reset';
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Chance Dating</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  
        <style>
            body {
                background-image:    url(images/backlit-bonding-casual-708392.jpg);
                background-size:     cover;                      /* <------ */
                background-repeat:   no-repeat;
                background-position: center center;              /* optional, center the image */
            }


        </style>
    </head>
    <body>

        <!-- Form Code Start -->
        <div class="container border border-primary rounded bg-light text-dark col-sm-6">

            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="container-fluid">            
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" placeholder="">
                        <p><?php echo $message; ?></p>
                    </div>    

                    <button name="btnAction" class="btn btn-primary" type="submit" value="SendReset">Send Password Reset</button>
                </div>





        </div>


    </body>
</html> 