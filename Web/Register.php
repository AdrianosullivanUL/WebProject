<!DOCTYPE html>
<?php
session_start();
require_once 'database_config.php';
include 'group05_library.php';


$user_id = $_SESSION['user_id'];
$matching_user_id = $_SESSION['matching_user_id'];
$message = '';
//echo "session user " . $user_id;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // check the button selected (these are at the end of this form
    if ($_POST['btnAction'] == "Cancel") { // Call Edit Profile
        header("Location: index.php");
        exit();
    }
    if ($_POST['btnAction'] == "Next") { // Call Edit Profile
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
        $sql = "SELECT * FROM user_profile where email ='" . $email . "';";
        //   echo $sql;
        if ($result = mysqli_query($db_connection, $sql)) {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    $email_found = 1;
                }
            }
        }
        if ($email_found == 1) {
            $valid = 0;
            $message = 'This email address is already registered';
        }

        // Create user
        if ($valid == 1) {
            // insert into database
            $sql = "insert into user_profile (email, password_hash, first_name, surname, black_listed_reason, user_status_id, is_administrator) values ('" . $email . "', sha2('" . $password . "',256), '','','',1,0); ";
            //  echo $sql;
            if ($result = mysqli_query($db_connection, $sql)) {
                // get the new user id
                $sql = "select id from user_profile where email = '" . $email . "';";
                echo $sql;
                if ($result = mysqli_query($db_connection, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            $user_id = $row['id'];
                            $session_hash = hash('sha256', get_GUID());
                            echo "$session_hash " . $session_hash;
                            $sql = "update user_profile set session_hash = '" . $session_hash . "' where id = " . $row['id'];
                            execute_sql_update($db_connection, $sql);
                            $_SESSION['session_hash'] = $session_hash;



                            $_SESSION['user_id'] = $user_id;
                            $_SESSION['matching_user_id'] = 0;



                            $_SESSION['user_logged_in'] = 1;
                            echo $_SESSION['user_id'];
                            header("Location: UpdateProfile.php");
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
?>
<html lang="en">
    <head>
    <head>
        <title>Register</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="StyleSheet.css">
    </head>

</head>
<body>
    <div class="topnav">
        <a class="active">REGISTER</a>
        <div class="topnav-right">
            <a href="Logon.php" title="Log In"><img height="16" width="16"  src='/images/Logoff.png'/>Log In</a>
        </div>
    </div>

    <div class="container">
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <form method="post" name="challenge"  class="form-horizontal" role="form" action="#" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >
                <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .35em .625em .75em;margin:0 2px;border: 2px solid silver;margin-bottom: 10em;background-color:lavender; opacity: .9;">
                    <legend style="border-bottom: none;width: inherit;;padding:inherit;" class="legend">Registration</legend>

                    <div class="form-group">
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                        <div class="col-sm-4 col-md-4 col-lg-4 col-xs-10 mobileLabel" style="font-weight: bold;padding-top: 10px; text-align: left;">
                            Your Email <span style="color: red">*</span> :</div>
                        <div class="col-sm-7 col-md-7 col-lg-6 col-xs-10 input-group mobilePad" style="font-weight:800;">
                            <input style="border-radius: 4px" type="email"  class="form-control" name="email" id="yourEmail">                   
                        </div>
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                        <div class="col-sm-4 col-md-4 col-lg-4 col-xs-10 mobileLabel" style="font-weight: bold; padding-top: 10px; text-align: left;">
                            Password <span style="color: red">*</span> :</div>
                        <div class="col-sm-7 col-md-7 col-lg-6 col-xs-10 input-group mobilePad" style="font-weight: bold;">
                            <input style="border-radius: 4px" type="password"  class="form-control" name="password" id="yourEmail">                   
                        </div>
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                        <div class="col-sm-4 col-md-4 col-lg-4 col-xs-10 mobileLabel" style="font-weight: bold; padding-top: 10px; text-align: left;">
                            Confirm Your Password  <span style="color: red">*</span> :</div>
                        <div class="col-sm-7 col-md-7 col-lg-6 col-xs-10 input-group mobilePad" style=""font-weight: bold;">
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

                        <div class="col-sm-3 col-md-3 col-lg-3 col-xs-10" style="text-align: right;">
                            <span style="color: red">*</span> <span style="font-size: 6pt;">mandatory fields</span>
                        </div> 
                        <div class="col-sm-8 col-md-8 col-lg-12 col-xs-10" style="text-align: left;">   
                            <button name="btnAction" class="btn btn-success" type="submit" value="Next">Next</button>
                            <button name="btnAction" class="btn btn-warning" type="submit" value="Cancel">Cancel</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-1 col-md-2 col-lg-2 col-xs-1"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                        <div class="col-sm-10 col-md-10 col-lg-10 col-xs-10" style="border-style:solid; border-color: silver;background-color:white; opacity: 0.9;">

                            <h3>How does it work?</h3>
                            <p>Our process is simple and easy to use, we don't ask you a million questions or need you to do a psychological tests! Just answer a few simple questions and upload a recent picture. Leave the rest to us :</p>
                            <ul>
                                <li>Based on the criteria you have entered, we will find people who match your preferences and present these in the Meeting Space under the System Matches heading</li>
                                <li>From here you can view all of the people matched to you and do the following</li>
                                <ul>
                                    <li>Like - You would like to engage with this person, if they also like you then you are both free to chat</li>
                                    <li>Maybe - This keeps the person in your meeting space and you can decide later, by default people who you don't action are removed after 1 month</li>
                                    <li>Goodbye - You are not interested in this person, they will not be presented to you again</li>
                                    <li>Report - THis person has posted an offensive photo or used inappropriate language, this reports them to the site administrator for review/sanction</li>
                                </ul>
                                <li>If you "like" someone, you will be added to their "Interested in me" list in their Meeting Space, if they also "like" you then you are free to chat</li>
                                <li>A list of people who you are "chatting" with are presented in your Meeting Space also, click on their picture and click on the Chat button to communicate with them</li>
                            </ul>
                            &nbsp;
                            <p>Note: Distance willing to travel is used to calculate the distance from your town to your potential match, this is done using "as the crow flies", please bear this in mind when contacting people.</p>
                        </div>
                    </div>
                </fieldset> 

            </form>
        </div>
        &nbsp;
    </div>
</div> 
</body>
</html> 