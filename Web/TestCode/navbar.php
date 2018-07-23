<!DOCTYPE html>
<?php
session_start();
if ($_SESSION['user_logged_in'] == 0) {
    header("Location: Logon.php");
}
require_once '../database_config.php';
include '../group05_library.php';

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
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            /* Add a black background color to the top navigation */
            .topnav {
                background-color: #333;
                overflow: hidden;
            }

            /* Style the links inside the navigation bar */
            .topnav a {
                float: left;
                display: block;
                color: #f2f2f2;
                text-align: center;
                padding: 14px 16px;
                text-decoration: none;
                font-size: 17px;
            }

            /* Add an active class to highlight the current page */
            .active {
                background-color: #4CAF50;
                color: white;
            }

            /* Hide the link that should open and close the topnav on small screens */
            .topnav .icon {
                display: none;
            }

            /* Dropdown container - needed to position the dropdown content */
            .dropdown {
                float: left;
                overflow: hidden;
            }

            /* Style the dropdown button to fit inside the topnav */
            .dropdown .dropbtn {
                font-size: 17px;
                border: none;
                outline: none;
                color: white;
                padding: 14px 16px;
                background-color: inherit;
                font-family: inherit;
                margin: 0;
            }

            /* Style the dropdown content (hidden by default) */
            .dropdown-content {
                display: none;
                position: absolute;
                background-color: #f9f9f9;
                min-width: 160px;
                box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                z-index: 1;
            }

            /* Style the links inside the dropdown */
            .dropdown-content a {
                float: none;
                color: black;
                padding: 12px 16px;
                text-decoration: none;
                display: block;
                text-align: left;
            }

            /* Add a dark background on topnav links and the dropdown button on hover */
            .topnav a:hover, .dropdown:hover .dropbtn {
                background-color: #555;
                color: white;
            }

            /* Add a grey background to dropdown links on hover */
            .dropdown-content a:hover {
                background-color: #ddd;
                color: black;
            }

            /* Show the dropdown menu when the user moves the mouse over the dropdown button */
            .dropdown:hover .dropdown-content {
                display: block;
            }

            /* When the screen is less than 600 pixels wide, hide all links, except for the first one ("Home"). Show the link that contains should open and close the topnav (.icon) */
            @media screen and (max-width: 600px) {
                .topnav a:not(:first-child), .dropdown .dropbtn {
                    display: none;
                }
                .topnav a.icon {
                    float: right;
                    display: block;
                }
            }

            /* The "responsive" class is added to the topnav with JavaScript when the user clicks on the icon. This class makes the topnav look good on small screens (display the links vertically instead of horizontally) */
            @media screen and (max-width: 600px) {
                .topnav.responsive {position: relative;}
                .topnav.responsive a.icon {
                    position: absolute;
                    right: 0;
                    top: 0;
                }
                .topnav.responsive a {
                    float: none;
                    display: block;
                    text-align: left;
                }
                .topnav.responsive .dropdown {float: none;}
                .topnav.responsive .dropdown-content {position: relative;}
                .topnav.responsive .dropdown .dropbtn {
                    display: block;
                    width: 100%;
                    text-align: left;
                }
            } 
             <!-- invisible radio button with image select -->
            ul {
                list-style: none;
            }
            li {
                display: inline-block;
                margin-right: 15px;
            }
            input {
                visibility:hidden;
            }
            img {
                cursor: pointer;
            }
            input:checked + label {
                border:2px solid #f00;
            } 
            .stackem div {
                width: 100%;
            }
                        body {
                background-image:    url(backlit-bonding-casual-708392.jpg);
                background-size:     cover;                      /* <------ */
                background-repeat:   no-repeat;
                background-position: center center;              /* optional, center the image */
            }
        </style>
    </head>
    <body>
        <div class="topnav" id="myTopnav">
            <a href="#home" class="active">Home</a>
            <a href="#news">News</a>
            <a href="#contact">Contact</a>
            <div class="dropdown">
                <button class="dropbtn"><img height="32" width="32"  title="Edit Profile" src='/images/menubutton.png'/> Menu
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="#"><img height="16" width="16"  title="Edit Profile" src='/images/Edit.png'/> Edit</a>
                    <a href="#"><img height="16" width="16"  title="Match Finder" src='/images/Find.png'/> Find Matches</a>
                    <a href="#"><img height="16" width="16"  title="Remove Account" src='/images/Delete.png'/> Remove Account</a>
                    <a href="#"><img height="16" width="16"  title="Logoff" src='/images/Logoff.png'/> Logoff</a>
                </div>
            </div>
            <a href="#about">About</a>
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">&#9776;</a>
        </div> 
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-group" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >        
            
            
            <!--
            
            <div class="topnav">
                <a class="active">MEETING SPACE</a>
                <a href="MeetingSpace.php">
                    <?php echo $first_name . " " . $surname ?>

                </a>

                <div class="topnav-right">
                    <button class="dropbtn"><img height="32" width="32"  title="Edit Profile" src='/images/menubutton.png'/> Menu
                        <i class="fa fa-caret-down"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="#"><img height="16" width="16"  title="Edit Profile" src='/images/Edit.png'/> Edit</a>
                        <a href="#"><img height="16" width="16"  title="Match Finder" src='/images/Find.png'/> Find Matches</a>
                        <a href="#"><img height="16" width="16"  title="Remove Account" src='/images/Delete.png'/> Remove Account</a>
                        <a href="#"><img height="16" width="16"  title="Logoff" src='/images/Logoff.png'/> Logoff</a>
                    </div>
                </div>
            </div>
            
            -->
            <div class="container">
                <div class ="row">
                    <div class="col-md-12 col-md-offset-0.5" >



                        <fieldset class="landscape_nomargin" style="max-width: min-width 0;padding:.75em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .9;">                                                                
                            <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Matches</legend>
                            <div class="container">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-lg-8 stackem">
                                        <div class="col-xs-12 col-sm-12 col-lg-12" style="background-color:transparent;">

                                        </div>
                                        <!--
                                        Chatting With Section 
                                        ---------------------  -->
                                        <div class="col-xs-12 col-sm-12 col-lg-12" style="border-style:solid; border-color: silver;background-color:white; opacity: 0.9;">
                                            <?php if (strlen($message) > 0) echo "<p><font color='red'>" . $message . "</font></p>" ?>
                                            <h3>You are Chatting with: </h3>
                                            <div class="container col-xs-12 col-sm-12 col-lg-12" style="background-color:white; opacity: 0.9;">

                                                <?php
                                                $sql = "SELECT * FROM matches_view where (match_user_id_1 =" . $user_id
                                                        . " or  match_user_id_2 =" . $user_id . ")"
                                                        . " and (user_profile_1_match_status = 'Chatting'"
                                                        . " or user_profile_2_match_status = 'Chatting');";
                                                $result = execute_sql_query($db_connection, $sql);
                                                if ($result == null) {
                                                    echo "<br><p>No matches found</p>";
                                                } else {
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        $pictureIndex++;
                                                        //echo ("<li>");
                                                        if ($row['match_user_id_1'] == $user_id) {
                                                            // echo "<div class='container>";
                                                            echo "        <input type='radio' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                            echo "        <label for='radio" . $pictureIndex . "'>";
                                                            echo "        <label >" . $row['user_profile_2_first_name'] . " " . $row['user_profile_2_surname'] . "</label>";
                                                            echo "<br>";
                                                            if (strlen($row['user_profile_2_picture']) > 0)
                                                                echo "<img class='rounded-circle'  height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_2_picture"]) . "'/>";
                                                            else
                                                                echo ("<img height='100' width='100' src='../images/camera-photo-7.png'/><i></i>'");
                                                            echo "</label>";
                                                        } else {
                                                            echo "        <input type='radio' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                            echo "        <label for='radio" . $pictureIndex . "'>";
                                                            echo "        <label >" . $row['user_profile_1_first_name'] . " " . $row['user_profile_1_surname'] . "</label>";
                                                            echo "<br>";
                                                            if (strlen($row['user_profile_1_picture']) > 0)
                                                                echo "<img  class='rounded-circle' height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_1_picture"]) . "'/>";
                                                            else
                                                                echo ("<img height='100' width='100' src='../images/camera-photo-7.png'/><i></i>'");
                                                            echo "</label>";
                                                        }
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <p>Click on Photograph and do one of the following:</p>
                                            <button name="btnAction" class="btn btn-success" type="submit" value="Chat"><img height="32" width="32"  title="Chat" src='/images/Chat.png'/></button>
                                            <button name="btnAction" class="btn btn-info" type="submit" value="View"><img height="32" width="32"  title="View" src='/images/View.png'/></button>
                                            <button name="btnAction" class="btn btn-warning" type="submit" value="Goodbye"><img height="32" width="32"  title="Goodbye"  src='/images/Goodbye.png'/></button>
                                            <button name="btnAction" class="btn btn-danger" type="submit" value="Report"><img height="32" width="32"  title="Report"  src='/images/Report.png'/></button>


                                            <div class="col-xs-5 col-sm-5 col-lg-4" style="background-color:lavender; opacity: 0.9;">

                                            </div>
                                        </div>
                                        <div class="col-sm-1 col-md-1 col-lg-1 col-xs-0" style="background-color:transparent; opacity: 0.0;">

                                        </div>
                                        <!-- 
                                        System Matches Section 
                                        ---------------------  -->
                                        <div class="col-xs-12 col-sm-12 col-lg-12" style="border-style:solid; border-color: silver;background-color:white; opacity: 0.9;">
                                            <h3>System Matches</h3>
                                            <div class="col-xs-12 col-sm-12 col-lg-12" style="background-color:white; opacity: 0.9;">
                                                <?php
                                                $sql = "SELECT * FROM matches_view where system_generated_match = true and (match_user_id_1 =" . $user_id
                                                        . " or  match_user_id_2 =" . $user_id . ")"
                                                        . " and user_profile_1_match_status not in ('Chatting','Goodbye');";
                                                $result = execute_sql_query($db_connection, $sql);

                                                if ($result == null) {
                                                    echo "<br><p>No matches found</p>";
                                                } else {
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        $pictureIndex++;
                                                        if ($row['match_user_id_1'] == $user_id) {
                                                            //   echo "<div class='col-sm-1'>";
                                                            echo "        <input type='radio' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                            echo "        <label for='radio" . $pictureIndex . "'>";
                                                            echo "        <label >" . $row['user_profile_2_first_name'] . " " . $row['user_profile_2_surname'] . "</label>";
                                                            echo "<br>";

                                                            if (strlen($row['user_profile_2_picture']) > 0)
                                                                echo "<img class='rounded-circle' height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_2_picture"]) . "'/>";
                                                            else
                                                                echo ("<img height='100' width='100' src='../images/camera-photo-7.png'/>");
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
                                                                    echo ("<div class='centred'><img height='32' width='32' src='/images/Report.png'/></div>");
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
                                                                echo ("<img height='100' width='100' src='../images/camera-photo-7.png'/><i></i>'");
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
                                                    }
                                                }
                                                ?>
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
                                    <!--
                                    Interested in me section 
                                    ------------------------ -->
                                    <div class="col-xs-12 col-sm-12 col-lg-4" style="border-style:solid; border-color: silver;background-color:white; opacity: 1;">
                                        <h3>Interested in Me</h3>
                                        <?php
                                        $sql = "SELECT * FROM matches_view where system_generated_match = false and (match_user_id_1 =" . $user_id
                                                . " or  match_user_id_2 =" . $user_id . ")"
                                                . " and user_profile_1_match_status not in ('Chatting','Goodbye') and user_profile_2_match_status not in ('Chatting','Goodbye');";
                                        // echo $sql;
                                        $result = execute_sql_query($db_connection, $sql);
                                        if ($result == null) {
                                            echo "<br><p>No matches found</p>";
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
                                                        echo ("<img class='rounded-circle' height='100' width='100' src='../images/camera-photo-7.png'/><i></i>'");
                                                    echo "</label>";
                                                } else {
                                                    echo "        <input type='radio' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                    echo "        <label for='radio" . $pictureIndex . "'>";
                                                    echo "        <label >" . $row['user_profile_1_first_name'] . " " . $row['user_profile_1_surname'] . "</label>";
                                                    echo "<br>";
                                                    if (strlen($row['user_profile_1_picture']) > 0)
                                                        echo "<img  height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_1_picture"]) . "'/>";
                                                    else
                                                        echo ("<img height='100' width='100' src='../images/camera-photo-7.png'/><i></i>'");
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
                            </div>
                        </fieldset>
                    </div>

                </div>
        </form>

    </body>
</html>
