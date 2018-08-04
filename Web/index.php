<?php
require_once 'database_config.php';
include 'group05_library.php';
session_start();
$_SESSION['is_administrator'] = 0;
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $logon = 0;
    echo $_POST['btnAction'];
    if ($_POST['btnAction'] == "Register") { // Call Edit Profile
        header("Location: Register.php");
        exit();
    }
    if ($_POST['btnAction'] == "Logon") { // Call Edit Profile
        header("Location: Logon.php");
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="StyleSheet.css">
        <style>
            /* Create three equal columns that floats next to each other */

            .container{
                float: center;
                width: 33.33%;
                padding: 10px;
                background-color: transparent;
                height: 300px;
                opacity: 0.9;
                vertical-align: middle;
                text-align: center;
            }
        </style>
    </head>
    <body >
        <div class="topnav">
            <a href="#home">Chance Dating</a>
        </div>
        <div class='row'>
            <div class="container" >
                <form method="post" name="challenge"  class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" >
                    <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .35em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color: white; opacity: .9;">
                        <b> <h1>Take that Chance</h1></b>
                        <b><i> <h2>The Exclusive Dating Site for Irish Singles</h2></b></i>
                        <br>
                        <b><h3> 3 Easy Steps</h3></b>
                        <ul align="left">
                        <li>   1. Register</li><br>
                        <li>   2. Complete your Profile details</li><br>
                        <li>   3. Find people you want to meet</li><br>
                        <li>   4. Chat with your potential partner </li><br>
                        </ul>
                        <button name="btnAction" class="btn btn-info" type="submit" value="Register">Register</button>
                        <button name="btnAction" class="btn btn-warning" type="submit" value="Logon">Logon</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </body>
</html> 