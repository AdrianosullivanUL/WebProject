<?php
require_once 'database_config.php';
include 'group05_library.php';
session_start();
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
    if ($_POST['btnAction'] == "Register") { // Call Edit Profile
        header("Location: Register.php");
        exit();
    }

    if ($_POST['btnAction'] == "Logon") { // Call Edit Profile
        $email = $_POST['email'];
        $password = $_POST['password'];
        $isAdmin = 0;
        $logon = 0;
        $sql = "select * from user_profile where LOWER(trim(email)) = trim('" . strtolower($email) . "') and password_hash = sha2('" . $password . "',256);";
        //echo $sql;
        if ($result = mysqli_query($db_connection, $sql)) {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    if ($row['user_status_id'] == 4) {
                        $message = "You have been bared from this system, your account is disabled.";
                        $logon = 2;
                    }

                    if ($row['user_status_id'] == 3 && $row['suspended_until_date'] > date("Y-m-d H:i:s")) {

                        $message = "You have been suspended until " . $row['suspended_until_date'] . "> (" . date("Y-m-d H:i:s") . ")";
                        $logon = 2;
                    }
                    if ($row['user_status_id'] == 3 && $row['suspended_until_date'] < date("Y-m-d H:i:s")) {
                        $message = "Your suspension has been lifted. Please provide your credentials again to continue.";
                        // reactivate the account
                        $sql = "update user_profile set user_status_id  = 1, user_status_date = now() where id = " . $row['id'];
                        execute_sql_update($db_connection, $sql);
                        $logon = 2;
                    }

                    if ($logon != 2) {
                        // Assign a newly encrypted session identifier when the log on, store this on their user_profile. If not matching on other screens then logon is rejected
                        $session_hash = hash('sha256', get_GUID());
                        //echo "$session_hash " . $session_hash;
                        $sql = "update user_profile set session_hash = '" . $session_hash . "' where id = " . $row['id'];
                        execute_sql_update($db_connection, $sql);
                        $_SESSION['session_hash'] = $session_hash;
                        $_SESSION['user_id'] = $row['id'];
                        $logon = 1;
                        if ($row['is_administrator'] == 1) {
                            $isAdmin = 1;
                        }
                    }
                }
            }
            if ($logon == 1) {
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
                if ($logon != 2)
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
        <title>Chance Dating</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="StyleSheet.css">
        <style>
            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
            }

            .navbar {
                overflow: hidden;
                background-color: #333;
                font-family: Arial, Helvetica, sans-serif;
            }

            .navbar a {
                float: left;
                font-size: 16px;
                color: white;
                text-align: center;
                padding: 14px 16px;
                text-decoration: none;
                background-color: grey;
                
            }

            .dropdown {
                float: right;
                overflow: hidden;
            }

            .dropdown .dropbtn {
                font-size: 12px;    
                border: none;
                outline: none;
                color: white;
                padding: 14px 16px;
                background-color: inherit;
                font: inherit;
                margin: 0;
            }

            .navbar a:hover, .dropdown:hover .dropbtn {
                background-color: purple;
            }

            .dropdown-content {
                display: none;
                position: absolute;
                background-color: #f9f9f9;
                width: 100%;
                left: 0;
                box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                z-index: 1;
            }

            .dropdown-content .header {
                background: purple;
                padding: 16px;
                color: white;

            }

            .dropdown:hover .dropdown-content {
                display: block;
            }

            /* Create three equal columns that floats next to each other */
            .column {
                float: left;
                width: 33.33%;
                padding: 10px;
                background-color: #ccc;
                height: 400px;
            }
            .container{
                float: left;
                margin: 0 auto;              
                width: 33.33%;
                padding: 10px;
                background-color: transparent;
                height: 300px;
                opacity: 0.9;
                vertical-align: middle;
                text-align: center;
            }

            .column a {
                float: none;
                color: white;
                padding: 16px;
                text-decoration: none;
                display: block;
                text-align: left;
            }

            .column a:hover {
                background-color: #ddd;
            }

            /* Clear floats after the columns */
            .row:after {
                content: "";
                display: table;
                clear: both;
            }
            .register{
                vertical-align: middle;
                text-align: center;
                color: #fff;
                font-size: 20px;
                cursor:pointer;
                background: Purple;
                line-height: 50px;
                margin-left: 0px;
                margin-bottom: 5px;
                float: left;
                border: none;
                border-radius: 7px;
                box-shadow: 0px 1px 0px 0px rgba(18, 17, 12, 1.0);
                width: 95%;
                height: 48px;
            }
            @media only screen and (max-width: 768px) 
            {
                /* For mobile phones: */
                [class = "container"]{
                    width: 100%;
                }
            }
            .footer {
                    background-color: silver;
                    color: #ffffff;
                    text-align: center;
                    font-size: 12px;
                    padding: 15px;
                    opacity: 0.9;
                }
                

            </style>
        </head>
        <body >
            <div class="navbar">
                <a href="#home">Chance Dating</a>
                <div class="dropdown">
                    <button class="dropbtn">Take that CHANCE 
                        <i class="fa fa-caret-down"></i>
                    </button>
                    <div class="dropdown-content">
                        <div class="header">
                            <h2>Chance Dating will help you make up your mind,<br>
                                with eligible partners in line &#8192;    &#9794;&#9792;&#9794;&#9792;<br>
                                Honey are you still free? &#8192;   &hearts;&#9892;&hearts;&#9890;&hearts;&#9891;<br>
                                Take that CHANCE......there's no Fee </h2>
                        </div>   
                        <div class="row">
                            <div class="column">
                                <h3>New to Chance Dating</h3>
                                <a href="Register.php">Register</a>

                            </div>
                            <div class="column">
                                <h3>Already a Member</h3>
                                <a href="Logon.php">Log In</a>
                            </div>
                            <div class="column">
                                <h3>Who Are We ?</h3>
                                <h>We are a dating agency focused on helping single people to find a partner on the island of Ireland. We are a small technologically minded group based in in Limerick and our aim is to help you find your perfect match.</h5>

                            </div>


                        </div>
                    </div>
                </div>

            </div>



            <div class='row'>
                <div class="container" >
                    <form method="post" name="challenge"  class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" >
                        <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .35em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color: white; opacity: .9;">

                            <b> <h1>Take that Chance</h1></b>

                            <b><i> <h2>The Exclusive Dating Site for Irish Singles</h2></b></i>

                            <br>
                            <b><h3> 3 Easy Steps</h3></b>
                            <br> 
                            <p>   1. <button name="btnAction" class="btn btn-info" type="submit" value="Register">Register</button>
                            <p>   2. Complete your Profile details</p>
                            <p>   3. Chat with your potential partner </p><br>

                            <p><button name="btnAction" class="register" type="Register" value="Register">Register Now</button><br>
                            
                            <br>
                            <br>

                        </fieldset>
                    </form>
                </div>

            </div>
            <div class='row'>
                <div class="container" ></div>

            </div>
            <div class="footer">
                <p><i>Stock Images curtesy of: <a href="https://www.pexels.com">Pexels.com</a></i> </p>
            </div>

        </body>
    </html> 