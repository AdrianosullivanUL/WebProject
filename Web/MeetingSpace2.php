<!DOCTYPE html>
<?php
session_start();
if ($_SESSION['user_logged_in'] == 0) {
    header("Location: Logon.php");
}
require_once 'database_config.php';
include 'group05_library.php';

$user_id = $_SESSION['user_id'];
if (isset($_SESSION['matching_user_id']))
    $matching_user_id = $_SESSION['matching_user_id'];
else
    $matching_user_id = 0;
//echo "session user " . $user_id;

$pictureIndex = 0;
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

// check the button selected (these are at the end of this form
    if ($_POST['btnAction'] == "EditProfile") { // Call Edit Profile
        $_SESSION['user_id'] = $user_id;
        $_SESSION['matching_user_id'] = $matching_user_id;
        header("Location: UpdateProfile.php");
        exit();
    }
    if ($_POST['btnAction'] == "MatchFinder") { // Call MatchFinder
        $_SESSION['user_id'] = $user_id;
        $_SESSION['matching_user_id'] = $matching_user_id;
        header("Location: MatchFind.php");
        exit();
    }
    if ($_POST['btnAction'] == "Logoff") { // Logoff
        //$_SESSION['user_logged_in'] = 0;
        header("Location: Logout.php");
        exit();
    }
    if ($_POST['btnAction'] == "RemoveAccount") { // Call RemoveAccount
        $_SESSION['user_id'] = $user_id;
        $_SESSION['matching_user_id'] = $matching_user_id;
        header("Location: RemoveAccount.php");
        exit();
    }

    // CHAT
    if ($_POST['btnAction'] == "Chat") { // Call RemoveAccount
        if (isset($_POST['selected_user'])) {
            $matchId = $_POST['selected_user'];
            $sql = "SELECT * FROM matches_view where match_id = " . $matchId . ";";
            echo $sql;
            $result = execute_sql_query($db_connection, $sql);
            if ($result == null) {
                $message = "ERROR: Cannot match entry " . $matchId;
            } else {
                while ($row = mysqli_fetch_array($result)) {
                    $matching_user_id = 0;
                    if ($row[match_user_id_1] == $user_id && $row[user_profile_2_match_status] == 'Chatting')
                        $matching_user_id = $row[match_user_id_2]; // chatting with profile 2
                    if ($row[match_user_id_2] == $user_id && $row[user_profile_1_match_status] == 'Chatting')
                        $matching_user_id = $row[match_user_id_1]; // chatting with profile 2                                
                    if ($matching_user_id != 0) {
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['matching_user_id'] = $matching_user_id;
                        header("Location: ChatLine.php");
                        exit();
                    } else {
                        $message = "This user has not responded to your Like request yet";
                    }
                }
            }
        } else {
            $message = "You must select a profile before clicking on Chat";
        }
    }
// Process User Profile specific buttons
    if ($_POST['btnAction'] == "Like" || $_POST['btnAction'] == "Maybe" || $_POST['btnAction'] == "Goodbye" || $_POST['btnAction'] == "Report" || $_POST['btnAction'] == "View") { // Get Match view row for subsequent buttons
        $_SESSION['user_id'] = $user_id;
        if (isset($_POST['selected_user']))
            $matchId = $_POST['selected_user'];
        else
            $matchId = 0;
        $sql = 'select * from matches_view where match_id = ' . $matchId . ";";
        $result = execute_sql_query($db_connection, $sql);
        if ($result == null) {
            echo "ERROR: Cannot find match entry to update status with, id =" . $matchId;
        } else {
            while ($row = mysqli_fetch_array($result)) {
                if ($row['match_user_id_1'] == $user_id)
                    $_SESSION['matching_user_id'] = $row['match_user_id_2'];
                else
                    $_SESSION['matching_user_id'] = $row['match_user_id_1'];
                $matchId = $row['match_id'];
                if ($_POST['btnAction'] == "View") { // View Profile
                    header("Location: ViewMatchProfile.php");
                    exit();
                }
                // Like Button
                // -----------
                if ($_POST['btnAction'] == "Like") { // Update Status
                    $newStatus = "";
                    if ($row['match_user_id_1'] == $user_id) {
                        $updateUser1or2 = 1;
                        if ($row['user_profile_2_match_status'] == 'Like')
                        // both users must like each other before chatting
                            $newStatus = "Chatting";
                        else
                            $newStatus = "Like";
                    } else {
                        $updateUser1or2 = 2;
                        if ($row['user_profile_1_match_status'] == 'Like')
                        // both users must like each other before chatting
                            $newStatus = "Chatting";
                        else
                            $newStatus = "Like";
                    }
                    $updateResult = update_match_status($db_connection, $matchId, $newStatus, $updateUser1or2);
                    //echo "Update result " . $updateResult;
                    //  $message = "Failed to update user status for match id " . $matchId; 
                }
                // Maybe Button
                // -----------

                if ($_POST['btnAction'] == "Maybe") { // Update Status
                    if ($row['match_user_id_1'] == $user_id)
                        $updateUser1or2 = 1;
                    else
                        $updateUser1or2 = 2;

                    $updateResult = update_match_status($db_connection, $matchId, 'Maybe', $updateUser1or2);
                    //echo "Update result " . $updateResult;
                }
                // Goodbye Button
                // --------------
                if ($_POST['btnAction'] == "Goodbye") { // Update Status
                    if ($row['match_user_id_1'] == $user_id)
                        $updateUser1or2 = 1;
                    else
                        $updateUser1or2 = 2;

                    $updateResult = update_match_status($db_connection, $matchId, 'Goodbye', $updateUser1or2);
                }
                // Report Button
                // -------------
                if ($_POST['btnAction'] == "Report") { // Update Status
                    if ($row['match_user_id_1'] == $user_id)
                        $updateUser1or2 = 1;
                    else
                        $updateUser1or2 = 2;

                    $updateResult = update_match_status($db_connection, $matchId, 'Report', $updateUser1or2);
                }
            }
        }
//   if ($_POST['btnAction'] == "Goodbye") { // Update Status
//       $matchId =    }
    } else {
        
    }
}
?>

<html lang="en">
    <head>
        <title>meeting space</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
        <style>
            body {
                background-image:    url(backlit-bonding-casual-708392.jpg);
                background-size:     cover;                      /* <------ */
                background-repeat:   no-repeat;
                background-position: center center;              /* optional, center the image */
            }

            /* Add a black background color to the top navigation */
            .topnav {
                background-color: #333;
                overflow: hidden;
            }

            /* Style the links inside the navigation bar */
            .topnav a {
                float: left;
                color: #F0F8FF;
                text-align: center;
                padding: 14px 16px;
                text-decoration: none;
                font-size: 17px;
            }

            /* Change the color of links on hover */
            .topnav a:hover {
                background-color: #ddd;
                color: grey;
            }

            /* Add a color to the active/current link */
            .topnav a.active {
                background-color: #A9A9A9;
                color: white;
            }

            /* Right-aligned section inside the top navigation */
            .topnav-right {
                float: right;
            }
            iv.first {
                opacity: 0.1;
                filter: alpha(opacity=10); 
            }
        </style>
    </head>
    <body>
        <div class="topnav">
            <a class="active">MEETING SPACE</a>
            <a href="MeetingSpace.php">Home</a>
            <div class="topnav-right">
                <a href="UpdateProfile.php">Update Profile</a>
                <a href="logout.php">Log Out</a>
            </div>
        </div>
        <div class ="row">
            <div class="col-md-offset-1 col-md-10"> 
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-horizontal" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >
                    <fieldset class="landscape_nomargin" style="max-width: min-width 0;padding:.75em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .9;">
                        <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Matches</legend>
                        <div class ="row">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-0" ></div>
                            <div class="col-xs-12 col-sm-10 col-lg-10" style="border-style:solid; border-color: silver;background-color:white; opacity: 0.9;">
                                <!-- Header section---------------------  -->

                                <?php
                                $first_name = "";
                                $surname = "";
                                $sql = "SELECT first_name, surname FROM user_profile where id = " . $user_id . ";";
                                //echo $sql;
                                $result = execute_sql_query($db_connection, $sql);
                                if ($result == null) {
                                    echo "ERROR: Cannot find profile for user id " . $user_id;
                                } else {
                                    while ($row = mysqli_fetch_array($result)) {
                                        $first_name = $row['first_name'];
                                        $surname = $row['surname'];
                                    }
                                }
                                ?>

                                <h4><?php echo $first_name . " " . $surname ?> </h4>
                                <button class="btn btn-primary" name="btnAction" type="submit" value="EditProfile"><img height="32" width="32"  title="Edit Profile" src='/images/Edit.png'/></button>
                                <button name="btnAction" class="btn btn-secondary" type="submit" value="MatchFinder"><img height="32" width="32"  title="Match Finder" src='/images/Find.png'/></button></button>                        
                                <button name="btnAction" class="btn btn-warning" type="submit" value="Logoff"><img height="32" width="32"  title="Logoff" src='/images/Logoff.png'/></button></button>
                                <button name="btnAction" class="btn btn-danger" type="submit" value="RemoveAccount"><img height="32" width="32"  title="Remove Account" src='/images/Delete.png'/></button></button>
                                <br>
                            </div>

                        </div>
                        <div class ="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xs-1" style ="background-color:transparent;"></div>
                            <h4> </h4>
                            </div>
                            
                            
                        <!--Chatting With Section ---------------------  -->
                        
                        <div class="row">
                            <div class="col-xs-1 col-sm-1 col-lg-1" style="background-color:transparent;"></div>
                            <div class="col-xs-4 col-sm-4 col-lg-5" style="border-style:solid; border-color: silver;background-color:white; opacity: 0.9;">
                                <?php if (strlen($message) > 0) echo "<p><font color='red'>" . $message . "</font></p>" ?>
                                <h3>You are Chatting with - </h3>

                                <div class="col-xs-4 col-sm-4 col-lg-5" style="background-color:white; opacity: 0.9;">

                                    <?php
                                    //echo "<ul>";
                                    $sql = "SELECT * FROM matches_view where (match_user_id_1 =" . $user_id
                                            . " or  match_user_id_2 =" . $user_id . ")"
                                            . " and (user_profile_1_match_status = 'Chatting'"
                                            . " or user_profile_2_match_status = 'Chatting');";
                                    $result = execute_sql_query($db_connection, $sql);
                                    if ($result == null) {
                                        echo "No matches found";
                                    } else {
                                        while ($row = mysqli_fetch_array($result)) {
                                            $pictureIndex++;
                                            //echo ("<li>");
                                            if ($row['match_user_id_1'] == $user_id) {
                                                echo "        <input type='radio' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                echo "        <label for='radio" . $pictureIndex . "'>";
                                                echo "        <label >" . $row['user_profile_2_first_name'] . " " . $row['user_profile_2_surname'] . "</label>";
                                                echo "<br>";
                                                if (strlen($row['user_profile_2_picture']) > 0)
                                                    echo "<img class='rounded-circle'  height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_2_picture"]) . "'/>";
                                                else
                                                    echo ("<img height='100' width='100' src='camera-photo-7.png'/><i></i>'");
                                                echo "</label>";
                                            } else {
                                                echo "        <input type='radio' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                echo "        <label for='radio" . $pictureIndex . "'>";
                                                echo "        <label >" . $row['user_profile_1_first_name'] . " " . $row['user_profile_1_surname'] . "</label>";
                                                echo "<br>";
                                                if (strlen($row['user_profile_1_picture']) > 0)
                                                    echo "<img  class='rounded-circle' height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_1_picture"]) . "'/>";
                                                else
                                                    echo ("<img height='100' width='100' src='camera-photo-7.png'/><i></i>'");
                                                echo "</label>";
                                            }
                                            //echo "    </li>";
                                            //echo "</ul>";
                                        }
                                    }
                                    ?>
                                </div>
                                <p>Click on Photograph and do one of the following:</p>
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-group" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >

                                    <button name="btnAction" class="btn btn-success" type="submit" value="Chat"><img height="32" width="32"  title="Chat" src='/images/Chat.png'/></button>
                                    <button name="btnAction" class="btn btn-info" type="submit" value="View"><img height="32" width="32"  title="View" src='/images/View.png'/></button>
                                    <button name="btnAction" class="btn btn-warning" type="submit" value="Goodbye"><img height="32" width="42"  title="Goodbye"  src='/images/Goodbye.png'/></button>
                                    <button name="btnAction" class="btn btn-danger" type="submit" value="Report"><img height="32" width="32"  title="Report"  src='/images/Report.png'/></button>
                                </form>

                                <div class="col-xs-5 col-sm-5 col-lg-4" style="background-color:lavender; opacity: 0.9;">

                                </div>
                            </div>
                            <!--<div class="col-xs-1 col-sm-1 col-lg-1" style="background-color:transparent; opacity: 0.0;"></div>-->

                            <!--Interested in me section ---------------------  -->
                            <div class="col-xs-4 col-sm-4 col-lg-5" style="border-style:solid; border-color: silver;background-color:white; opacity: 1;"
                                 <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-horizontal" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >
                                    <h3>Interested in Me</h3>
                                    <?php
                                    $sql = "SELECT * FROM matches_view where system_generated_match = false and (match_user_id_1 =" . $user_id
                                            . " or  match_user_id_2 =" . $user_id . ")"
                                            . " and user_profile_1_match_status not in ('Chatting','Goodbye') and user_profile_2_match_status not in ('Chatting','Goodbye');";
// echo $sql;
                                    $result = execute_sql_query($db_connection, $sql);
                                    if ($result == null) {
                                        echo "No matches found";
                                    } else {
                                        while ($row = mysqli_fetch_array($result)) {
                                            $pictureIndex++;
                                            echo ("<li>");
                                            if ($row['match_user_id_1'] == $user_id) {
                                                echo "        <input type='radio' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                echo "        <label for='radio" . $pictureIndex . "'>";
                                                echo "        <label >" . $row['user_profile_2_first_name'] . " " . $row['user_profile_2_surname'] . "</label>";
                                                echo "<br>";
                                                if (strlen($row['user_profile_2_picture']) > 0)
                                                    echo "<img class='rounded-circle' height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_2_picture"]) . "'/>";
                                                else
                                                    echo ("<img class='rounded-circle' height='100' width='100' src='camera-photo-7.png'/><i></i>'");
                                                echo "</label>";
                                            } else {
                                                echo "        <input type='radio' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                echo "        <label for='radio" . $pictureIndex . "'>";
                                                echo "        <label >" . $row['user_profile_1_first_name'] . " " . $row['user_profile_1_surname'] . "</label>";
                                                echo "<br>";
                                                if (strlen($row['user_profile_1_picture']) > 0)
                                                    echo "<img  height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_1_picture"]) . "'/>";
                                                else
                                                    echo ("<img height='100' width='100' src='camera-photo-7.png'/><i></i>'");
                                                echo "</label>";
                                            }
                                            echo "    </li>";
                                            echo "</ul>";
                                        }
                                    }
                                    ?>


                                    <p>Click on Photograph and do one of the following:</p>
                                    <button name="btnAction" class="btn btn-success" type="submit" value="Like"><img height="32" width="32"  title="Like" src='/images/Like.png'/></button>
                                    <button name="btnAction" class="btn btn-info" type="submit" value="View"><img height="32" width="32"  title="View" src='/images/View.png'/></button>
                                    <button name="btnAction" class="btn btn-primary" type="submit" value="Maybe"><img height="32" width="32"  title="Maybe" src='/images/Maybe.png'/></button>
                                    <button name="btnAction" class="btn btn-warning" type="submit" value="Goodbye"><img height="32" width="32"  title="Goodbye" src='/images/Goodbye.png'/></button>
                                    <button name="btnAction" class="btn btn-danger" type="</div>submit" value="Report"><img height="32" width="32"  title="Report" src='/images/Report.png'/></button>

                            </div>
                        </div>
                        <div class ="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xs-1" style ="background-color:transparent;"></div>
                            <h4> </h4>
                            </div>
                        <!-- 
                        System Matches Section 
                        ---------------------  -->
                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-0" style="background-color:transparent; opacity: 0.0;"></div>
                            <div class="col-xs-8 col-sm-8 col-lg-10" style="border-style:solid; border-color: silver;background-color:white; opacity: 0.9;">
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-horizontal" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >
                                    <h3>System Matches </h3>

                                    <div class="col-xs-8 col-sm-8 col-lg-10" style="background-color:white; opacity: 0.9;">
                                        >
                                        <ul>
                                            <?php
                                            $sql = "SELECT * FROM matches_view where system_generated_match = true and (match_user_id_1 =" . $user_id
                                                    . " or  match_user_id_2 =" . $user_id . ")"
                                                    . " and user_profile_1_match_status not in ('Chatting','Goodbye');";
                                            $result = execute_sql_query($db_connection, $sql);

                                            if ($result == null) {
                                                echo "No matches found";
                                            } else {
                                                while ($row = mysqli_fetch_array($result)) {
                                                    $pictureIndex++;
                                                    echo ("<li>");
                                                    if ($row['match_user_id_1'] == $user_id) {
                                                        //   echo "<div class='col-sm-1'>";
                                                        echo "        <input type='radio' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                        echo "        <label for='radio" . $pictureIndex . "'>";
                                                        echo "        <label >" . $row['user_profile_2_first_name'] . " " . $row['user_profile_2_surname'] . "</label>";
                                                        echo "<br>";

                                                        if (strlen($row['user_profile_2_picture']) > 0)
                                                            echo "<img class='rounded-circle' height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_2_picture"]) . "'/>";
                                                        else
                                                            echo ("<img height='100' width='100' src='camera-photo-7.png'/>");
                                                        switch ($row['user_profile_1_match_status']) {
                                                            case 'Like':
                                                                echo ("<div class=''><img height='32' width='32' tiitle='Liked' src='/images/Like.png'/></div>");
                                                                break;
                                                            case 'Maybe':
                                                                echo ("<div class=''><img height='32' width='32' tiitle='Maybe interested' src='/images/Maybe.png'/></div>");
                                                                break;
                                                            case 'Matched':
                                                                echo ("<div class=''><img height='32' width='32' src='/images/SystemGenerated.png'/></div>");
                                                                break;
                                                            case 'Report':
                                                                echo ("<div class=''><img height='32' width='32' src='/images/Report.png'/></div>");
                                                                break;
                                                        }

                                                        echo "</label>";
                                                    } else {
                                                        echo "        <input type='radio' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                        echo "        <label for='radio" . $pictureIndex . "'>";
                                                        echo "        <label >" . $row['user_profile_1_first_name'] . " " . $row['user_profile_1_surname'] . "</label>";
                                                        echo "<br>";
                                                        if (strlen($row['user_profile_1_picture']) > 0)
                                                            echo "<img class='rounded-circle' height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_1_picture"]) . "'/>";
                                                        else
                                                            echo ("<img height='100' width='100' src='camera-photo-7.png'/><i></i>'");
                                                        switch ($row['user_profile_2_match_status']) {
                                                            case 'Like':
                                                                echo ("<div class=''><img height='32' width='32' src='/images/Like.png'/></div>");
                                                                break;
                                                            case 'Maybe':
                                                                echo ("<div class=''><img height='32' width='32' src='/images/Maybe.png'/></div>");
                                                                break;
                                                            case 'Matched':
                                                                break;
                                                                echo ("<div class=''><img height='32' width='32' src='/images/SystemGenerated.png'/></div>");
                                                            case 'Report':
                                                                echo ("<div class=''><img height='32' width='32' src='/images/Report.png'/></div>");
                                                                break;
                                                        }
                                                        echo "</label>";
                                                    }
                                                    //   echo "<div>";
                                                    echo "    </li>";
                                                }
                                            }
                                            ?>
                                        </ul>

                                    </div>
                                    <div class="col-xs-10 col-sm-10 col-lg-10" style="background-color:whites; opacity: 0.9;">
                                        <p>Click on Photograph and do one of the following:</p>

                                        <button name="btnAction" class="btn btn-success" type="submit" value="Like"><img height="32" width="32"  title="Like" src='/images/Like.png'/></button>
                                        <button name="btnAction" class="btn btn-info" type="submit" value="View"><img height="32" width="32"  title="View" src='/images/View.png'/></button>
                                        <button name="btnAction" class="btn btn-primary" type="submit" value="Maybe"><img height="32" width="32"  title="Maybe" src='/images/Maybe.png'/></button>
                                        <button name="btnAction" class="btn btn-warning" type="submit" value="Goodbye"><img height="32" width="32"  title="Goodbye" src='/images/Goodbye.png'/></button>
                                        <button name="btnAction" class="btn btn-danger" type="submit" value="Report"><img height="32" width="32"  title="Report" src='/images/Report.png'/></button>
                                    </div>

                            </div>
                        </div>
                        </div> </div> 
                        </div>
                        </div>
                        </body>
                        </html>


                        <!-- 
                        <!--Interested in me section
                        ---------------------  -->
                        <!--<div class="col-sm-3 container border border-primary rounded bg-light text-dark" >
                            <h3>Interested in Me</h3>
                             php
                           // $sql = "SELECT * FROM matches_view where system_generated_match = false and (match_user_id_1 =" . $user_id
                                   // . " or  match_user_id_2 =" . $user_id . ")"
                                    //. " and user_profile_1_match_status not in ('Chatting','Goodbye') and user_profile_2_match_status not in ('Chatting','Goodbye');";
                        // echo $sql;
                            $result = execute_sql_query($db_connection, $sql);
                            if ($result == null) {
                                echo "No matches found";
                            } else {
                                while ($row = mysqli_fetch_array($result)) {
                                    $pictureIndex++;
                                    echo ("<li>");
                                    if ($row['match_user_id_1'] == $user_id) {
                                        echo "        <input type='radio' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                        echo "        <label for='radio" . $pictureIndex . "'>";
                                        echo "        <label >" . $row['user_profile_2_first_name'] . " " . $row['user_profile_2_surname'] . "</label>";
                                        echo "<br>";
                                        if (strlen($row['user_profile_2_picture']) > 0)
                                            echo "<img class='rounded-circle' height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_2_picture"]) . "'/>";
                                        else
                                            echo ("<img class='rounded-circle' height='100' width='100' src='camera-photo-7.png'/><i></i>'");
                                        echo "</label>";
                                    } else {
                                        echo "        <input type='radio' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                        echo "        <label for='radio" . $pictureIndex . "'>";
                                        echo "        <label >" . $row['user_profile_1_first_name'] . " " . $row['user_profile_1_surname'] . "</label>";
                                        echo "<br>";
                                        if (strlen($row['user_profile_1_picture']) > 0)
                                            echo "<img  height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_1_picture"]) . "'/>";
                                        else
                                            echo ("<img height='100' width='100' src='camera-photo-7.png'/><i></i>'");
                                        echo "</label>";
                                    }
                                    echo "    </li>";
                                    echo "</ul>";
                                }
                            }
                            ?>
                        
                            <div class="col-sm-12 ">
                                <p>Click on Photograph and do one of the following:</p>
                                <button name="btnAction" class="btn btn-success" type="submit" value="Like"><img height="32" width="32"  title="Like" src='/images/Like.png'/></button>
                                <button name="btnAction" class="btn btn-info" type="submit" value="View"><img height="32" width="32"  title="View" src='/images/View.png'/></button>
                                <button name="btnAction" class="btn btn-primary" type="submit" value="Maybe"><img height="32" width="32"  title="Maybe" src='/images/Maybe.png'/></button>
                                <button name="btnAction" class="btn btn-warning" type="submit" value="Goodbye"><img height="32" width="32"  title="Goodbye" src='/images/Goodbye.png'/></button>
                                <button name="btnAction" class="btn btn-danger" type="submit" value="Report"><img height="32" width="32"  title="Report" src='/images/Report.png'/></button>
                        
                            </div>
                        
                        
                        </div>
                        </div>
                        </div>
                        
                        
                        </form>        
                        </body>
                        </html> 