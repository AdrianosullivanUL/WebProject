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
    <body >
        <div class="container-fluid">        
            <br>
            <div class="row">
                <div class="col-sm-6 container border border-primary rounded bg-light text-dark">
                    <h1>Reach Out to your future partner</h1>
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
                    <p>We are a dating agency focused on helping single people to find a partner on the island of Ireland. We are a small technologically minded group based in in Limerick and our aim is to help you find your perfect match</p>
                </div>
            </div>
            &nbsp;
            <div class="row">
                <div class="col-sm-6 container border border-primary rounded bg-light text-dark">
                    <h1>How does it work?</h1>
                    <p>Our process is simple and easy to use, we don't focus on asking you a million questions or doing psychological tests! We ask you a few simple questions and ask you to post a recent picture of yourself. After that we do the following for you:</p>
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
        </div>
    </body>
</html> 