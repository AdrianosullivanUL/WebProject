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
                float: left;
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
                height: 250px;
            }

            .column a {
                float: none;
                color: black;
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
                            <h3>New to Chance Dating</h3>
                            <a href="Register.php">Register</a>
                            
                        </div>
                        <div class="column">
                            <h3>Already a Member</h3>
                            <a href="Logon.php">Log In</a>
                            </div>
                        
                    </div>
                </div>
            </div>
            <div id="Help" class="collapse container">
            <fieldset class="landscape_nomargin" style="max-width: min-width 0;padding:.75em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .9;">                                                                
                <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Help</legend>
                <div class="container">
                    <h5>You are now in <b>Meeting Space screen</b></h5>
                    <br>
                    <br><b> What do i do next?</b>
                    <br>The Navigation Bar consists of the following links:<br>
                    <b>&nbsp;&nbsp;&nbsp;User Name</b> - Click on your name at any point to return to the Meeting Space screen<br>
                    <b>&nbsp;&nbsp;&nbsp;Edit Profile</b> - Fine tune your preferences to allow the system make better matches<br>
                    <b>&nbsp;&nbsp;&nbsp;Match Finder</b> - Do wild card searches and see a wider range of people on our site<br>
                    <b>&nbsp;&nbsp;&nbsp;Remove Profile</b> - Remove your profile from this system<br>
                    <b>&nbsp;&nbsp;&nbsp;Help</b> - Get information on how the current screen works<br>
                    <b>&nbsp;&nbsp;&nbsp;Logoff</b> - Log off and return to the log on screen<br>
                    <br><br><b>In this screen you can see the following sections:</b>
                    <ul >
                        <li><b>Possible Matches</b><br>These are people who the system have identified <img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/SystemGenerated.png"/> as meeting your profile match criteria or people who you have searched for and marked as a Maybe</li>
                        <li><b>People who I like</b><br>These I have selected and then Liked them, they are "pending" they liking you and will progress to the Chat section when they do so</li>
                        <li><b>People who like me</b><br>These are people who like me and are pending you liking them</li>
                        <li><b>You are chatting with</b><br>After you both like each other, people are shown in this window you are free to chat with the people listed here</li>
                    </ul>
                    <br>In each section a set of buttons are provided relevant to that section, click on the user image first and then select from one of the following:
                    <ul>
                        <li><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Like.png"/>Like<br>You like this person and would like to chat with them. When you use this option, the person is moved to your <b>People who I like</b> section.</li>
                        <li><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/View.png"/>View<br>Have a look at this persons profile, from there you can also action their profile</li>
                        <li><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Maybe.png"/>Maybe<br>If you are not sure about a person, you can click this button to have them remain in your <b>Possible Matches</b>,
                            <br> Please note: by default <img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/SystemGenerated.png"/> system generate matches expire after one month</li>
                        <li><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Goodbye.png"/>Goodbye<br>If you are not interested, click this to remove the profile from your meeting space</li>
                        <li><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Report.png"/>Report<br>Feel offended by they persons profile, an in appropriate image for instance? Click to have their profile reviewed by the system moderator</li>
                    </ul>

                </div>
            </fieldset>
        </div>
        </div>

        
        <div class="container-fluid">        
            <form method="post" name="challenge"  class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" >
                <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .35em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .8;">

                    <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Chance Dating</legend>

                    <div class="row">
                        <div class="col-sm-7 container border border-primary rounded bg-light text-dark">
                            <h1>Reach Out to your future partner</h1>
                            <p>Connecting singles across the Ireland to their ideal partner</p>
                            <br><br>
                            <a href="Logon.php">Log on</a> 
                            &nbsp;
                            <a href="Register.php">Register</a>
                        </div>
                    </div>            
                    &nbsp;
                    <div class="row">
                        <div class="col-sm-6 container border border-primary rounded bg-light text-dark">
                            <h1>Who are we?</h1>
                            <p>We are a dating agency focused on helping single people to find a partner on the island of Ireland. We are a small technologically minded group based in in Limerick and our aim is to help you find your perfect match.</p>
                            <br>
                            <p><i>Stock Images curtesy of: <a href="https://www.pexels.com">Pexels.com</a></i> </p>
                        </div>
                    </div>
                </fieldset>
        </div>
    </body>
</html> 