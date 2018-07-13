<?php
require_once 'database_config.php';
include 'group05_library.php';
session_start();

$_SESSION['user_id'] = 24;

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
    <body >
        <br>
        <div class="container border border-primary rounded bg-light text-dark col-sm-6">
            <h1>Reach Out to your future partner</h1>
            <br><br>
            <a href="Logon.php?userid=24">Log on</a> 
            &nbsp
            <a href="Register.php">Register</a>
        </div>
        <br><br>
        <div class="container border border-primary rounded bg-light text-dark col-sm-6">
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                <h1>Test Links (to be removed later)</h1>
                <br><br>
                <button name="btnAction" class="btn btn-success" type="submit" value="MeetingSpace">Meeting Space</button>
                <button name="btnAction" class="btn btn-success" type="submit" value="ViewMatchingProfile">View Matching Profile</button>

            </form>

        </div>

    </body>
</html> 