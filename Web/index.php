<?php
require_once 'database_config.php';
include 'group05_library.php';
session_start();

$_SESSION['user_id'] = 0;
$_SESSION['matching_user_id'] = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // check the button selected (these are at the end of this form
    if ($_POST['btnAction'] == "MeetingSpace") { // Call Edit Profile
        header("Location: MeetingSpace.php");
        exit();
    }
    if ($_POST['btnAction'] == "ViewMatchingProfile") { // Call Edit Profile
        header("Location: ViewMatchProfile.php");
        exit();
    }
    if ($_POST['btnAction'] == "UpdateProfile") { // Call Edit Profile
        header("Location: UpdateProfile.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Chance Dating</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  
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
            }

            .dropdown {
                float: Right;
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
                opacity:0.9;
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
                height: 250px;
            }

            .column a {
                float: none;
                color:grey;
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
                        <h2>Chance Dating will help you make up your mind <br>
                            with eligible partners in line<br>
                            Honey are you still free ?<br>
                            Take that CHANCE......there's no Fee </h2>
                    </div>   
                    <div class="row">
                        <div class="column">
                            <h3>New to Chance Dating?</h3>
                            <a href="Register.php"><img height="32" width="32"  src='http://hive.csis.ul.ie/4065/group05/images/register.png'/>Register</a>
                            
                        </div>
                        <div class="column">
                            <h3>Already a Member?</h3>
                            <a href="Logon.php"><img height="32" width="32"  src='http://hive.csis.ul.ie/4065/group05/images/logon.png'/>Log In</a>
                            </div>
                        <div class="column">
                            <h3>Who Are We ?</h3>
                            <h5>We are a dating agency focused on helping single people to find a partner on the island of Ireland. We are a small technologically minded group based in in Limerick and our aim is to help you find your perfect match.</h5>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html> 