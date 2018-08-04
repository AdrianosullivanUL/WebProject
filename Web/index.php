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
        <link rel="stylesheet" href="StyleSheet.css">

    </head>
    <body >
        <div class="topnav">
            <a class="active">Chance Dating &hearts;</a>
            <div class="topnav-right">
                <a data-toggle = "collapse" data-target = "#About"><img height="16" width="16" src='http://hive.csis.ul.ie/4065/group05/images/help-faq.png'/><font color="white">About</font></a>
            </div>
        </div>
        <div id="About" class="collapse container">
            <fieldset class="landscape_nomargin" style="max-width: min-width 0;padding:.75em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .9;">                                                                
                <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">How it Works</legend>
                <div class="col-md-12">
                    <h5></b>How Does it work ? </b></h5>
                    <br><b> The <font color="purple">CHANCE Dating</font> Matchfinder uses 5 different parameters from your profile that are essential for building a romantic relationship.  </b><br>

                    <p>We consider...</p>
                    <ul align="center"
                        <li>Factor 1: Your Gender Preference</li><br>
                        <li>Factor 2: Your Location </li><br>
                        <li>Factor 3: Age Range</li><br>
                        <li>Factor 4: Interests and Hobbies</li><br>
                        <li>Factor 5: Distance you are willing to travel to pursue relationship </li><br>
                    </ul>

                    <a href="Register.php"> <font color="purple"><h5>Click here to begin</font></h5></a>

                    <h5></b>You also have the option to do your own Search</h5>
                    <p></p>

                    <br><b> What do i do next?</b>
                    <br>From the list of potential matches you can check out the Bio's, Interests, and Profile details of the person you selected
                    <br>When you find a match, click <img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Like.png"/><b><font color="blue"> Like </font></b>, 
                    this will put them into the <b>"People who I Like"</b>section in the Meeting Space,they will stay there until they also like you.
                    <br>If they also <img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Like.png"/><b><font color="blue"> Like </font></b> you then they will move to your chat area and you can both chat then.
                    <br>People who have liked you show in <b>"People who like me"</b> and again if you like them they move into the chat area.
                    <br>If you want to remove a profile from your page, click on <img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Goodbye.png"/><b><font color="yellow"> Goodbye </font></b> and they are removed.
                    <br>Have you been offended by someone? Click on <img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Report.png"/><b><font color="red">Report </font></b>and the system moderator will review their account and if required Suspend
                </div>
            </fieldset>
        </div>
        <div class="container" >
            <div class='row'>
                <div class="col-md-6 offset-md-3">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-horizontal" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >
                        <fieldset class="landscape_nomargin" style="text-align: center;min-width: 0;padding:    .35em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:white; opacity: .9;">
                            <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Take that Chance</legend>
                            <b><i> <h2>The Exclusive Dating Site for Irish Singles</h2></b></i>
                            <br>
                            <b><h5> 4 Easy Steps</h5></b>
                            <ul align="left">
                                <li>   1. Register</li><br>
                                <li>   2. Complete your Profile details</li><br>
                                <li>   3. Find people you want to meet</li><br>
                                <li>   4. Chat with your potential partner </li><br>
                            </ul>
                            <br>
                            <button name="btnAction" class="btn btn-info" type="submit" value="Register">Register</button>
                            <button name="btnAction" class="btn btn-warning" type="submit" value="Logon">Logon</button>
                            <br>
                            <br>
                        </fieldset>
                    </form>
                </div>
            </div>
            <div class='row'>
                <div class="container" >
                    <br>
                    <br>
                </div>
            </div>


    </body>
</html> 