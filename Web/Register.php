<!DOCTYPE html>
<?php
session_start();
require_once 'database_config.php';


$user_id = $_SESSION['user_id'];
$matching_user_id = $_SESSION['matching_user_id'];
$message = '';
//echo "session user " . $user_id;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // check the button selected (these are at the end of this form
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
                //  echo $sql;
                if ($result = mysqli_query($db_connection, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            $user_id = $row['id'];
                            $_SESSION['user_id'] = $user_id;
                            $_SESSION['matching_user_id'] = 0;
                            $_SESSION['user_logged_in'] = 1;        
                            //        echo $_SESSION['user_id'];
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
        <title>register</title>
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
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="container">
                <div class="row">
                    <div class="container border border-primary rounded bg-light text-dark col-sm-6">
                        <h1>Register</h1>
                    </div>
                </div>

                <br>
                <div class="container border border-primary rounded bg-light text-dark col-sm-6">
                    <div class="row">
                        <br>
                        <div class="col">email:</div>
                        <div class="col"><input type="text" name="email"></div>
                    </div>
                    <div class="row">
                        <br>
                        <div class="col">Password:</div>
                        <div class="col"><input type="password" name="password"></div>
                    </div>                    
                    <div class="row">
                        <br>
                        <div class="col">Confirm Password:</div>
                        <div class="col"><input type="password" name="confirmPassword"></div>
                    </div>         
                    <div class="row">
                        <p style="color:red"> <?php echo $message; ?></p>
                    </div>

                    <button name="btnAction" class="btn btn-success" type="submit" value="Next">Next</button>
                    </form>

                </div>
            </div>
        </form>
    </body>

</html> 