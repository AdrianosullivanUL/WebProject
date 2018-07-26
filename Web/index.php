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
        <link rel="stylesheet" href="StyleSheet.css">
    </head>
    <body >
        <div class="container-fluid">        
            <form method="post" name="challenge"  class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" >
                <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .35em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .8;">

                    <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Chance Dating</legend>

                    <div class="row">
                        <div class="col-sm-6 container border border-primary rounded bg-light text-dark">
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