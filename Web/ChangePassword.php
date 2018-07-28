<!DOCTYPE html>
<?php
session_start();
require_once 'database_config.php';
include 'group05_library.php';

if (isset($_GET['ResetKey'])) {
    $resetKey = $_GET['ResetKey'];
    // echo $resetKey;

    $message = '';
//echo "session user " . $user_id;


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

// check the button selected (these are at the end of this form
        if ($_POST['btnAction'] == "Cancel") { // Call Edit Profile
            header("Location: index.php");
            exit();
        }
        if ($_POST['btnAction'] == "Submit") { // Call Edit Profile
// Validate the inputs
            $valid = 1;
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];
            if ($password != $confirmPassword) {
                $valid = 0;
                $message = 'The passwords do not match';
            }

// Password too short
            if (strlen($password) < 5) {
                $valid = 0;
                $message = 'The password too short';
            }

// valid email
            if ((strpos($email, '.')) && (strpos($email, '@'))) {
                
            } else {
                $valid = 0;
                echo $email;
                $message = 'Invalid email address';
            }

// already on file
            $email_found = 0;
            $sql = "SELECT * FROM user_profile where email ='" . $email . "' and session_hash = '" . $resetKey . "';";
   echo $sql;
            if ($result = mysqli_query($db_connection, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        $email_found = 1;
                        $user_id = $row['id'];
                    }
                }
            }
            if ($email_found == 0) {
                $valid = 0;
                $message = 'This email address is either not found or there is a resetkey mismatch, please try again';
            }

            // Create user
            if ($valid == 1) {
                // change password on user profile
                $session_hash = hash('sha256', get_GUID());
                $sql = "update user_profile set password_hash = sha2('" . $password . "',256), session_hash = '" . $session_hash . "' where id = " . $user_id . ";";

                echo $sql;
                if ($result = mysqli_query($db_connection, $sql)) {
                    // get the user profile row again
                    $sql = "select id from user_profile where id = " . $user_id . ";";
                    echo $sql;
                    if ($result = mysqli_query($db_connection, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                                $user_id = $row['id'];
                                $_SESSION['user_id'] = $user_id;
                                $_SESSION['matching_user_id'] = 0;
                                $_SESSION['user_logged_in'] = 1;
                                //        echo $_SESSION['user_id'];
                                header("Location: MeetingSpace.php");
                                exit();
                            }
                        } else {
                            $message = 'Failed to update database';
                        }
                    } else {
                        $message = 'Failed to update databvase';
                    }
                } else {
                    $message = 'Failed to update databvase';
                }
            }
        }
    }
} else {
// Fallback behaviour goes here
}
?>
<html lang="en">
    <head>
    <head>
        <title>Register</title>
        <meta charset="utf-8">
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  
        <link rel="stylesheet" href="StyleSheet.css">
    </head>

</head>
<body>
    <div class="topnav">
        <a class="active">CHANGE PASSWORD</a>
    </div>

    <div class="container">
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <form method="post" name="challenge"  class="form-horizontal" role="form" action="#" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >
                <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .35em .625em .75em;margin:0 2px;border: 2px solid silver;margin-bottom: 10em;background-color:lavender; opacity: .9;">
                    <legend style="border-bottom: none;width: inherit;;padding:inherit;" class="legend">Change Password</legend>

                    <div class="form-group">
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                        <div class="col-sm-3 col-md-3 col-lg-4 col-xs-10 mobileLabel" style="font-weight: bold;padding-top: 10px; text-align: left;">
                            Your Email <span style="color: red">*</span> :</div>
                        <div class="col-sm-7 col-md-7 col-lg-6 col-xs-9 input-group mobilePad" style="font-weight:800;">
                            <input style="border-radius: 4px" type="email"  class="form-control" name="email" id="yourEmail">                   
                        </div>
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                        <div class="col-sm-3 col-md-3 col-lg-4 col-xs-10 mobileLabel" style="font-weight: bold; padding-top: 10px; text-align: left;">
                            Password <span style="color: red">*</span> :</div>
                        <div class="col-sm-7 col-md-7 col-lg-6 col-xs-9 input-group mobilePad" style="font-weight: bold;">
                            <input style="border-radius: 4px" type="password"  class="form-control" name="password" id="yourEmail">                   
                        </div>
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                        <div class="col-sm-3 col-md-3 col-lg-4 col-xs-10 mobileLabel" style="font-weight: bold; padding-top: 10px; text-align: left;">
                            Confirm Your Password  <span style="color: red">*</span> :</div>
                        <div class="col-sm-7 col-md-7 col-lg-6 col-xs-9 input-group mobilePad" style=""font-weight: bold;">
                             <input style="border-radius: 4px" type="password"  class="form-control" name="confirmPassword" id="yourEmail">                   
                        </div>
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                    </div>


                    <?php
                    if (strlen($message) > 0) {
                        echo "<div class='alert alert-danger'>";
                        echo "<p>" . $message . "</p>";
                        echo "</div>";
                    }
                    ?>

                    <br>
                    <div class="col-sm-10 col-md-10 col-lg-10 col-xs-10 mobileLabel" style="font-weight: bold; padding-top: 10px; text-align: right;">
                        <div class="col-sm-1 col-md-1 col-lg-2 col-xs-1" style="text-align: left;"></div>
                        <div class="col-sm-2 col-md-2 col-lg-2 col-xs-2" style="text-align: left;">
                            <span style="color: red">*</span> <span style="font-size: 6pt;">mandatory fields</span>
                        </div>   
                        <button name="btnAction" class="btn btn-success" type="submit" value="Submit">Submit</button>
                        <button name="btnAction" class="btn btn-warning" type="submit" value="Cancel">Cancel</button>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-1 col-md-2 col-lg-2 col-xs-1"></div>
                    </div>
                </fieldset> 
            </form>
        </div>
        &nbsp;
    </div>
</div> 
</body>
</html> 