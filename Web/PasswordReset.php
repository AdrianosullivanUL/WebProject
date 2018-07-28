<?php
require_once 'database_config.php';
include 'group05_library.php';
session_start();
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emailfound = 0;
// check the button selected (these are at the end of this form
    if ($_POST['btnAction'] == "SendReset") { // Call Edit Profile
        $email = $_POST['email'];

        $stmt = $db_connection->prepare("select * from user_profile where email = ?;");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = mysqli_fetch_array($result)) {
            $emailfound = 1;
            $user_id = $row['id'];
            $_SESSION['user_id'] = $row['id'];
            if ($row['is_administrator'] == 1) {
                $_SESSION['is_administrator'] = 1;
            }
            if ($emailfound == 1) {
// Reset the session hash for this account
                $session_hash = hash('sha256', get_GUID());
                $sql = "update user_profile set session_hash = '" . $session_hash . "' where id = " . $user_id;
                execute_sql_update($db_connection, $sql);
                $_SESSION['session_hash'] = $session_hash;
                $_SESSION['user_id'] = $row['id'];

// Send an email link to the emial address with the reset link
// $resetLink = "http://hive.csis.ul.ie/4065/group05/ChangePassword.php?ResetKey=" . $session_hash;
                $resetLink = "http://localhost/ChangePassword.php?ResetKey=" . $session_hash;
                $msg = "Please click <a href='" . $resetLink . "'>here</a> to reset your password";
                $subject = "Password reset request for Chance Dating web site";
//                $msg = wordwrap($msg, 70);
                mail($email, $subject, $msg);
//echo $email . "<br>" . $subject  . "<br>" . $msg  . "<br>";
                echo "mail($email,$subject , $msg)";
                $message = "Please check your email for a password reset email and click on the link provided to complete the process";

//ini_set("SMTP", "aspmx.l.google.com");
//     ini_set("sendmail_from", "donotreply@chance.com");
//     $message = "Password reset email setting:\r\nSMTP = aspmx.l.google.com\r\nsmtp_port = 25\r\nsendmail_from = donotreply@chance.com";
//     $headers = "From: YOURMAIL@gmail.com";
//     mail("Sending@provider.com", "Testing", $message, $headers);
//     echo "Check your email now....<BR/>";
//     header("Location: MeetingSpace.php");
//     exit();
            } else {
                $message = 'This email address is not registered';
            }
        }
        if ($_POST['btnAction'] == "ForgotPassword") { // Call Edit Profile
            header("Location: PasswordReset.php");
            exit();
        }
    } else {
        $message = 'Please input your email address and an email will be sent to you to allow for a password reset';
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
                background-image:    url(backlit-bonding-casual-708392.jpg);
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
<?php
if (strlen($message) > 0) {
    echo "<div class='alert alert-danger'>";
    echo "<p>" . $message . "</p>";
    echo "</div>";
}
?>
                    </div>    

                    <button name="btnAction" class="btn btn-primary" type="submit" value="SendReset">Send Password Reset</button>
                </div>





        </div>


    </body>
</html> 