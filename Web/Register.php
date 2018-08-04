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
        $email = strtolower($_POST['email']);
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
        $sql = "SELECT * FROM user_profile where lower(email) ='" . $email . "';";
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
            $stmt = $db_connection->prepare("insert into user_profile (email, password_hash, first_name, surname, black_listed_reason, user_status_id, is_administrator) values (?, sha2(?,256), '','','',1,0); ");
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            //  echo $sql;
            if ($result = mysqli_query($db_connection, $sql)) {
                // get the new user id
                $sql = "select id from user_profile where email = '" . $email . "';";
                //echo $sql;
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
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  
        <link rel="stylesheet" href="StyleSheet.css">
    </head>

</head>
<body>
    <div class="topnav">
        <a class="active">REGISTER</a>
        <div class="topnav-right">
            <a data-toggle = "collapse" data-target = "#Help"><img height="16" width="16" src='http://hive.csis.ul.ie/4065/group05/images/help-faq.png'/><font color="white">Help</font></a>
            <a href="Logon.php" title="Log In"><img height="16" width="16"  src='http://hive.csis.ul.ie/4065/group05/images/logon.png'/>Log In</a>
        </div>
    </div>
    <div id="Help" class="collapse container">
        <fieldset class="landscape_nomargin" style="max-width: min-width 0;padding:.75em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .9;">                                                                
            <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Help</legend>
            <div class="container">
                <h3>How it works</h3>
                <p>Our process is simple and easy to use, we don't ask you a million questions or need you to do psychological tests! Just answer a few simple questions, upload a recent picture and take a CHANCE with us:</p>
                <ul>
                    <li>Based on the criteria you have entered, we will find people who match your preferences and present these in the Meeting Space under the <b>Possible Matches</b> heading</li>
                    <li>From here you can view all of the people matched to you and do the following:</li>
                    <ul>
                        <li><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Like.png"/>Like<br>You like this person and would like to chat with them. When you use this option, the person is moved to your <b>People who I like</b> section.</li>
                        <li><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/View.png"/>View<br>Have a look at this persons profile, from there you can also action their profile</li>
                        <li><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Maybe.png"/>Maybe<br>If you are not sure about a person, you can click this button to have them remain in your <b>Possible Matches</b>,
                            <br> Please note: by default <img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/SystemGenerated.png"/> system generate matches expire after one month</li>
                        <li><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Goodbye.png"/>Goodbye<br>If you are not interested, click this to remove the profile from your meeting space</li>
                        <li><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Report.png"/>Report<br>Feel offended by they persons profile, an in appropriate image for instance? Click to have their profile reviewed by the system moderator</li>
                    </ul>
                    <li>If you "like" someone, you will be added to their "Interested in me" list in their Meeting Space, if they also "like" you then you are free to chat</li>
                    <li>A list of people who you are "chatting" with are presented in your Meeting Space also, click on their picture and click on the Chat button to communicate with them</li>
                </ul>
                Want to search the field? Have a look at our <b>match finder</b> where you can browse the entire menu and go "al a carte".
                <br><br>
                <p>Note: Distance willing to travel is used to calculate the distance from your town to your potential match, this is done using "as the crow flies", please bear this in mind when contacting people.</p>
            </div>
        </fieldset>
    </div>                


    <div class="container">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1" >
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-horizontal" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >
                    <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .35em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .9;">
                        <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Registration Details</legend>

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


                    </fieldset> 

                </form>
            </div>
        </div>
    </div> 
</body>
</html> 