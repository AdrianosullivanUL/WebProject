<!DOCTYPE html>
<?php
session_start();
require_once 'database_config.php';
include 'group05_library.php';
$user_id = $_SESSION['user_id'];
$session_hash = $_SESSION['session_hash'];
if (validate_logon($db_connection, $user_id, $session_hash) == false) {
    // User is not correctly logged on, route to Logon screen
    header("Location: Logon.php");
}

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
        if (isset($_POST['selected_match'])) {
            $matchId = $_POST['selected_match'];
            $sql = "SELECT * FROM matches_view where match_id = " . $matchId . ";";
            // echo $sql;
            $result = execute_sql_query($db_connection, $sql);
            if ($result == null) {
                $message = "ERROR: Cannot match entry " . $matchId;
            } else {
                while ($row = mysqli_fetch_array($result)) {
                    $matching_user_id = 0;
                    if ($row['match_user_id_1'] == $user_id && $row['user_profile_2_match_status'] == 'Chatting')
                        $matching_user_id = $row['match_user_id_2']; // chatting with profile 2
                    if ($row['match_user_id_2'] == $user_id && $row['user_profile_1_match_status'] == 'Chatting')
                        $matching_user_id = $row['match_user_id_1']; // chatting with profile 2                                
                    if ($matching_user_id != 0) {
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['matching_user_id'] = $matching_user_id;
                        $_SESSION['match_id'] = $row['match_id'];

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
        if (isset($_POST['selected_match']))
            $matchId = $_POST['selected_match'];
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
                $user_profile_1_match_status = $row['user_profile_1_match_status'];
                $user_profile_2_match_status = $row['user_profile_2_match_status'];
                // Like Button
                // -----------
                if ($_POST['btnAction'] == "Like") { // Update Status
                    // Check and see current status
                    $valid = true;
                    if ($user_profile_1_match_status == 'Chatting' || $user_profile_2_match_status == 'Chatting') {
                        $valid = false;
                        $message = "You are already chatting with this person";
                    }

                    if ($valid == true) {
                        $newStatus = "";
                        if ($row['match_user_id_1'] == $user_id) {
                            $updateUser1or2 = 1;
                            echo "user_profile_2_match_status" . $row['user_profile_2_match_status'];
                            if ($row['user_profile_2_match_status'] == 'Like') {
                                // both users must like each other before chatting
                                $updateResult = update_match_status($db_connection, $matchId, 'Chatting', 2);
                                $newStatus = "Chatting";
                            } else
                                $newStatus = "Like";
                        } else {
                            $updateUser1or2 = 2;
                            echo "user_profile_1_match_status" . $row['user_profile_2_match_status'];
                            if ($row['user_profile_1_match_status'] == 'Like') {

                                $updateResult = update_match_status($db_connection, $matchId, 'Chatting', 1);
// both users must like each other before chatting
                                $newStatus = "Chatting";
                            } else
                                $newStatus = "Like";
                        }
                        $updateResult = update_match_status($db_connection, $matchId, $newStatus, $updateUser1or2);
                    }
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
    }
}
?>

<html lang="en">
    <head>
        <title>meeting space</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="StyleSheet.css">
    </head>
    <body>
        <?php
        $first_name = "";
        $surname = "";
        $sql = "SELECT first_name, surname, picture FROM user_profile where id = " . $user_id . ";";
//echo $sql;
        $result = execute_sql_query($db_connection, $sql);
        if ($result == null) {
            echo "ERROR: Cannot find profile for user id " . $user_id;
        } else {
            while ($row = mysqli_fetch_array($result)) {
                $first_name = $row['first_name'];
                $surname = $row['surname'];
                $picture = $row['picture'];
            }
        }
        ?>  
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-group" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >        

            <div class="topnav">
                <a class="active">MEETING SPACE</a>
                <a href="MeetingSpace.php" title="Meeting Space">

                    <?php
                    if (strlen($picture) > 0)
                        echo "<img class='rounded-circle selectimg'  height='32' width='32' src='data:image/jpeg;base64," . base64_encode($picture) . "'/>";
                    else
                        echo ("<img class='selectimg' height='32' width='32' src='http://hive.csis.ul.ie/4065/group05/images/camera-photo-7.png'/><i></i>");
                    echo "&nbsp;" . $first_name . " " . $surname
                    ?>

                </a>
                <div class="topnav-right">
                    <a href="UpdateProfile.php" title="Edit your User Profile"><img height="16" width="16"  src='http://hive.csis.ul.ie/4065/group05/images/Edit.png'/>Edit Profile</a>
                    <a href="MatchFind.php" title="Find People"><img height="16" width="16"   src='http://hive.csis.ul.ie/4065/group05/images/Find.png'/>Match Finder</a>
                    <a href="RemoveAccount.php" title="Remove your User Profile"><img height="16" width="16"  src='http://hive.csis.ul.ie/4065/group05/images/Delete.png'/>Remove Profile</a>
                    <a href="Logout.php" title="Log out of the system"><img height="16" width="16"  src='http://hive.csis.ul.ie/4065/group05/images/Logoff.png'/>Logoff</a>
                </div>
            </div>
            <div class="container">
                <div class ="row">
                    <div class="col-md-12 col-md-offset-0.5" >
                        <fieldset class="landscape_nomargin" style="max-width: min-width 0;padding:.75em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .9;">                                                                
                            <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Matches</legend>
                            <div class="container">
                                <div class="col-xs-12 col-sm-12 col-lg-12" style="background-color:transparent;">
                                </div>
                                <div class="row">
                                    <!--
                                    Chatting With Section 
                                    ------------------------  -->
                                    <div class="col-xs-12 col-sm-8 col-lg-8" style="border-style:solid; border-color: silver;background-color:white; opacity: 0.9;">
                                        <?php
                                        if (strlen($message) > 0) {
                                            echo "<div class='alert alert-danger'>";
                                            echo "<p>" . $message . "</p>";
                                            echo "</div>";
                                        }
                                        ?>
                                        <h3>You are Chatting with</h3>
                                        <?php
                                        $ChatMatchesFound = true;
                                        $sql = "SELECT * FROM matches_view "
                                                . " where (match_user_id_2 =" . $user_id . " and user_profile_1_match_status = 'Chatting' and user_profile_2_match_status not in ('Report', 'Goodbye')) "
                                                . " or (match_user_id_1 =" . $user_id . " and user_profile_2_match_status = 'Chatting' and user_profile_1_match_status not in ('Report', 'Goodbye'));";
                                        //echo $sql;
                                        $result = execute_sql_query($db_connection, $sql);
                                        if ($result == null) {
                                            echo "<br><p>No matches found</p>";
                                            $ChatMatchesFound = false;
                                        } else {
                                            while ($row = mysqli_fetch_array($result)) {
                                                $pictureIndex++;
                                                //echo ("<li>");
                                                $youHaveMail = false;
                                                if ($row['match_user_id_1'] == $user_id) {
                                                    $sql = "select count(*) cnt  from user_communication where to_user_id = $user_id and from_user_id = " . $row['match_user_id_2']
                                                            . " and id > ifnull((select max(id) from user_communication where from_user_id = $user_id),0)";
                                                    $result1 = execute_sql_query($db_connection, $sql);
                                                    if ($result1 != null) {
                                                        while ($row1 = mysqli_fetch_array($result1)) {
                                                            if ($row1['cnt'] > 0) {
                                                                $youHaveMail = true;
                                                            }
                                                        }
                                                    }
                                                    // echo "<div class='container>";
                                                    echo "        <input type='radio' class='hideinput' name='selected_match' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                    echo "        <label for='radio" . $pictureIndex . "'>";
                                                    echo "        <label >" . $row['user_profile_2_first_name'] . " " . $row['user_profile_2_surname'] . "</label>";
                                                    echo "<br>";
                                                    if (strlen($row['user_profile_2_picture']) > 0)
                                                        echo "<img class='rounded-circle selectimg'  height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_2_picture"]) . "'/>";
                                                    else
                                                        echo ("<img class='selectimg' height='100' width='100' src='http://hive.csis.ul.ie/4065/group05/images/camera-photo-7.png'/><i></i>");
                                                    if ($youHaveMail == true) {
                                                        echo ("<br><img height='32' width='32' tiitle='Liked' src='http://hive.csis.ul.ie/4065/group05/images/send.png'/>");
                                                    }
                                                    echo "</label>";
                                                } else {
                                                    $sql = "select count(*) cnt  from user_communication where to_user_id = $user_id and from_user_id = " . $row['match_user_id_1']
                                                            . " and id > ifnull((select max(id) from user_communication where from_user_id = $user_id),0)";
                                                    $result1 = execute_sql_query($db_connection, $sql);
                                                    if ($result1 != null) {
                                                        while ($row1 = mysqli_fetch_array($result1)) {
                                                            if ($row1['cnt'] > 0) {
                                                                $youHaveMail = true;
                                                            }
                                                        }
                                                    }
                                                    echo "        <input type='radio' class='hideinput' name='selected_match' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                    echo "        <label for='radio" . $pictureIndex . "'>";
                                                    echo "        <label >" . $row['user_profile_1_first_name'] . " " . $row['user_profile_1_surname'] . "</label>";
                                                    echo "<br>";
                                                    if (strlen($row['user_profile_1_picture']) > 0)
                                                        echo "<img  class='rounded-circle selectimg' height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_1_picture"]) . "'/>";
                                                    else
                                                        echo ("<img class='selectimg' height='100' width='100' src='http://hive.csis.ul.ie/4065/group05/images/camera-photo-7.png'/><i></i>");
                                                    if ($youHaveMail == true) {
                                                        echo ("<br><img height='32' width='32' tiitle='Liked' src='http://hive.csis.ul.ie/4065/group05/images/send.png'/>");
                                                    }

                                                    echo "</label>";
                                                }
                                            }
                                        }
                                        ?>
                                        <?php
                                        if ($ChatMatchesFound == true) {
                                            echo '<p><b>Click on Photograph and do one of the following:</b></p>';
                                            echo '<button name="btnAction" class="btn btn-success" type="submit" value="Chat"><img height="16" width="16"  title="Chat" src="/images/Chat.png"/>Chat</button>';
                                            echo '<button name="btnAction" class="btn btn-info" type="submit" value="View"><img height="16" width="16"  title="View" src="/images/View.png"/>View</button>';
                                            echo '<button name="btnAction" class="btn btn-warning" type="submit" value="Goodbye"><img height="16" width="16"  title="Goodbye"  src="/images/Goodbye.png"/>Goodbye</button>';
                                            echo '<button name="btnAction" class="btn btn-danger" type="submit" value="Report"><img height="16" width="16"  title="Report"  src="/images/Report.png"/>Report!</button>';
                                        }
                                        ?>

                                        <div class="col-xs-5 col-sm-5 col-lg-4" style="background-color:lavender; opacity: 0.9;">

                                        </div>
                                    </div>
                                    <!--
                                    Like Me
                                    ------------------------ -->
                                    <div class="col-xs-12 col-sm-12 col-lg-4" style="border-style:solid; border-color: silver;background-color:white; opacity: 1;">
                                        <h3>People who Like me</h3>
                                        <p>(these will move to Chat section when you like them)</p>
                                        <?php
                                        $peopleWhoLikeMeFound = true;
                                        $sql = "SELECT * FROM matches_view "
                                                . " where (match_user_id_2 =" . $user_id . " and user_profile_1_match_status = 'Like' and user_profile_2_match_status not in ('Like', 'Chatting','Report', 'Goodbye')) "
                                                . " or (match_user_id_1 =" . $user_id . " and user_profile_2_match_status = 'Like' and user_profile_1_match_status not in ('Like', 'Chatting','Report', 'Goodbye'));";

                                        //echo $sql;
                                        $result = execute_sql_query($db_connection, $sql);
                                        if ($result == null) {
                                            echo "<br><p>No matches found</p>";
                                            $peopleWhoLikeMeFound = false;
                                        } else {
                                            while ($row = mysqli_fetch_array($result)) {
                                                $pictureIndex++;
                                                echo ("<li>");
                                                if ($row['match_user_id_1'] == $user_id) {
                                                    echo "        <input type='radio' class='hideinput' name='selected_match' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                    echo "        <label for='radio" . $pictureIndex . "'>";
                                                    echo "        <label >" . $row['user_profile_2_first_name'] . " " . $row['user_profile_2_surname'] . "</label>";
                                                    echo "<br>";
                                                    if (strlen($row['user_profile_2_picture']) > 0)
                                                        echo "<img class='rounded-circle selectimg' height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_2_picture"]) . "'/>";
                                                    else
                                                        echo ("<img class='rounded-circle selectimg' height='100' width='100' src='http://hive.csis.ul.ie/4065/group05/images/camera-photo-7.png'/><i></i>");
                                                    echo "</label>";
                                                } else {
                                                    echo "        <input type='radio' class='hideinput' name='selected_match' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                    echo "        <label for='radio" . $pictureIndex . "'>";
                                                    echo "        <label >" . $row['user_profile_1_first_name'] . " " . $row['user_profile_1_surname'] . "</label>";
                                                    echo "<br>";
                                                    if (strlen($row['user_profile_1_picture']) > 0)
                                                        echo "<img class='rounded-circle selectimg'  height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_1_picture"]) . "'/>";
                                                    else
                                                        echo ("<img class='selectimg' height='100' width='100' src='http://hive.csis.ul.ie/4065/group05/images/camera-photo-7.png'/><i></i>");
                                                    echo "</label>";
                                                }
                                                echo "    </li>";
                                                echo "</ul>";
                                            }
                                        }
                                        if ($peopleWhoLikeMeFound == true) {
                                            echo '<p><b>Click on Photograph and do one of the following:</b></p>';
                                            echo '<button name="btnAction" class="btn btn-success" type="submit" value="Like"><img height="16" width="16"  title="Like" src="http://hive.csis.ul.ie/4065/group05/images/Like.png"/>Like</button>';
                                            echo '<button name="btnAction" class="btn btn-info" type="submit" value="View"><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/View.png"/>View</button>';
                                            echo '<button name="btnAction" class="btn btn-primary" type="submit" value="Maybe"><img height="16" width="16" title="Maybe" src="http://hive.csis.ul.ie/4065/group05/images/Maybe.png"/>Maybe</button>';
                                            echo '<button name="btnAction" class="btn btn-warning" type="submit" value="Goodbye"><img height="16" width="16" title="Goodbye" src="http://hive.csis.ul.ie/4065/group05/images/Goodbye.png"/>Goodbye</button>';
                                            echo '<button name="btnAction" class="btn btn-danger" type="</div>submit" value="Report"><img height="16" width="16" title="Report" src="http://hive.csis.ul.ie/4065/group05/images/Report.png"/>Report</button>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- 
                                    Possible Matches Section 
                                    ------------------------  -->
                                    <div class="col-xs-12 col-sm-8 col-lg-8" style="border-style:solid; border-color: silver;background-color:white; opacity: 0.9;">
                                        <h3>Possible Matches</h3>
                                        <?php
                                        $systemMatchesFound = true;
                                        $sql = "SELECT * FROM matches_view where system_generated_match = true"
                                                . " and (match_user_id_1 =" . $user_id . " and user_profile_1_match_status in ('Matched','Maybe') and user_profile_2_match_status not in ('Like','Report', 'Goodbye')) "
                                                . " or  (match_user_id_2 =" . $user_id . " and user_profile_2_match_status in ('Matched','Maybe') and user_profile_1_match_status not in ('Like','Report', 'Goodbye'));";

                                        //echo $sql;
                                        $result = execute_sql_query($db_connection, $sql);
                                        if ($result == null) {
                                            echo "<br><p>No matches found</p>";
                                            $systemMatchesFound = false;
                                        } else {
                                            while ($row = mysqli_fetch_array($result)) {
                                                $pictureIndex++;
                                                if ($row['match_user_id_1'] == $user_id) {
                                                    //   echo "<div class='col-sm-1'>";
                                                    echo "        <input type='radio' class='hideinput' name='selected_match' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                    echo "        <label for='radio" . $pictureIndex . "'>";
                                                    echo "        <label >" . $row['user_profile_2_first_name'] . " " . $row['user_profile_2_surname'] . "</label>";
                                                    echo "<br>";
                                                    if (strlen($row['user_profile_2_picture']) > 0)
                                                        echo "<img class='rounded-circle selectimg' height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_2_picture"]) . "'/>";
                                                    else
                                                        echo ("<img height='100' class='selectimg' width='100' src='http://hive.csis.ul.ie/4065/group05/images/camera-photo-7.png'/>");
                                                    switch ($row['user_profile_1_match_status']) {
                                                        case 'Like':
                                                            echo ("<div class=''><img height='32' width='32' tiitle='Liked' src='http://hive.csis.ul.ie/4065/group05/images/Like.png'/></div>");
                                                            break;
                                                        case 'Maybe':
                                                            echo ("<div class=''><img height='32' width='32' tiitle='Maybe interested' src='http://hive.csis.ul.ie/4065/group05/images/Maybe.png'/></div>");
                                                            break;
                                                        case 'Matched':
                                                            echo ("<div class=''><img height='32' width='32' src='http://hive.csis.ul.ie/4065/group05/images/SystemGenerated.png'/></div>");
                                                            break;
                                                        case 'Report':
                                                            echo ("<div class='centred'><img height='32' width='32' src='http://hive.csis.ul.ie/4065/group05/images/Report.png'/></div>");
                                                            break;
                                                    }
                                                    echo "</label>";
                                                } else {
                                                    echo "        <input type='radio' name='selected_match'  class='hideinput' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                    echo "        <label for='radio" . $pictureIndex . "'>";
                                                    echo "        <label >" . $row['user_profile_1_first_name'] . " " . $row['user_profile_1_surname'] . "</label>";
                                                    echo "<br>";
                                                    if (strlen($row['user_profile_1_picture']) > 0)
                                                        echo "<img class='rounded-circle selectimg' height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_1_picture"]) . "'/>";
                                                    else
                                                        echo ("<img class='selectimg' height='100' width='100' src='http://hive.csis.ul.ie/4065/group05/images/camera-photo-7.png'/>");
                                                    switch ($row['user_profile_2_match_status']) {
                                                        case 'Like':
                                                            echo ("<div class=''><img height='32' width='32' tiitle='Liked' src='http://hive.csis.ul.ie/4065/group05/images/Like.png'/></div>");
                                                            break;
                                                        case 'Maybe':
                                                            echo ("<div class=''><img height='32' width='32' tiitle='Maybe interested' src='http://hive.csis.ul.ie/4065/group05/images/Maybe.png'/></div>");
                                                            break;
                                                        case 'Matched':
                                                            echo ("<div class=''><img height='32' width='32' src='http://hive.csis.ul.ie/4065/group05/images/SystemGenerated.png'/></div>");
                                                            break;
                                                        case 'Report':
                                                            echo ("<div class='centred'><img height='32' width='32' src='http://hive.csis.ul.ie/4065/group05/images/Report.png'/></div>");
                                                            break;
                                                    }
                                                    echo "</label>";
                                                }
                                            }
                                        }
                                        if ($systemMatchesFound == true) {
                                            echo '<p><b>Click on Photograph and do one of the following:</b></p>';
                                            echo '<button name="btnAction" class="btn btn-success" type="submit" value="Like"><img height="16" width="16"  title="Like" src="http://hive.csis.ul.ie/4065/group05/images/Like.png"/>Like</button>';
                                            echo '<button name="btnAction" class="btn btn-info" type="submit" value="View"><img height="16" width="16"  title="View" src="http://hive.csis.ul.ie/4065/group05/images/View.png"/>View</button>';
                                            echo '<button name="btnAction" class="btn btn-primary" type="submit" value="Maybe"><img height="16" width="16"  title="Maybe" src="http://hive.csis.ul.ie/4065/group05/images/Maybe.png"/>Maybe</button>';
                                            echo '<button name="btnAction" class="btn btn-warning" type="submit" value="Goodbye"><img height="16" width="16"  title="Goodbye" src="http://hive.csis.ul.ie/4065/group05/images/Goodbye.png"/>Goodbye</button>';
                                            echo '<button name="btnAction" class="btn btn-danger" type="submit" value="Report"><img height="16" width="16"  title="Report" src="http://hive.csis.ul.ie/4065/group05/images/Report.png"/>Report!</button>';
                                        }
                                        ?>
                                    </div>

                                    <!--
                                    I Like
                                    ------------------------ -->
                                    <div class="col-xs-12 col-sm-12 col-lg-4" style="border-style:solid; border-color: silver;background-color:white; opacity: 1;">
                                        <h3>People who I Like</h3>
                                        <p>(these will move to Chat section when they like you)</p>
                                        <?php
                                        $iLikeFound = true;
                                        $sql = "SELECT * FROM matches_view "
                                                . " where (match_user_id_1 =" . $user_id . " and user_profile_1_match_status = 'Like' and user_profile_2_match_status not in ('Like', 'Chatting','Report', 'Goodbye')) "
                                                . " or (match_user_id_2 =" . $user_id . " and user_profile_2_match_status = 'Like' and user_profile_1_match_status not in ('Like', 'Chatting','Report', 'Goodbye'));";
                                        //echo $sql;
                                        $result = execute_sql_query($db_connection, $sql);
                                        if ($result == null) {
                                            echo "<br><p>No matches found</p>";
                                            $iLikeFound = false;
                                        } else {
                                            while ($row = mysqli_fetch_array($result)) {
                                                $pictureIndex++;
                                                echo ("<li>");
                                                if ($row['match_user_id_1'] == $user_id) {
                                                    echo "        <input type='radio' class='hideinput' name='selected_match' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                    echo "        <label for='radio" . $pictureIndex . "'>";
                                                    echo "        <label >" . $row['user_profile_2_first_name'] . " " . $row['user_profile_2_surname'] . "</label>";
                                                    echo "<br>";
                                                    if (strlen($row['user_profile_2_picture']) > 0)
                                                        echo "<img class='rounded-circle selectimg' height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_2_picture"]) . "'/>";
                                                    else
                                                        echo ("<img class='rounded-circle selectimg' height='100' width='100' src='http://hive.csis.ul.ie/4065/group05/images/camera-photo-7.png'/><i></i>");
                                                    echo "</label>";
                                                } else {
                                                    echo "        <input type='radio' class='hideinput' name='selected_match' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                    echo "        <label for='radio" . $pictureIndex . "'>";
                                                    echo "        <label >" . $row['user_profile_1_first_name'] . " " . $row['user_profile_1_surname'] . "</label>";
                                                    echo "<br>";
                                                    if (strlen($row['user_profile_1_picture']) > 0)
                                                        echo "<img class='rounded-circle selectimg'  height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_1_picture"]) . "'/>";
                                                    else
                                                        echo ("<img class='selectimg' height='100' width='100' src='http://hive.csis.ul.ie/4065/group05/images/camera-photo-7.png'/><i></i>");
                                                    echo "</label>";
                                                }
                                                echo "    </li>";
                                                echo "</ul>";
                                            }
                                        }
                                        if ($iLikeFound == true) {
                                            echo '<p><b>Click on Photograph and do one of the following:</b></p>';
                                            echo '<button name="btnAction" class="btn btn-info" type="submit" value="View"><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/View.png"/>View</button>';
                                            echo '<button name="btnAction" class="btn btn-primary" type="submit" value="Maybe"><img height="16" width="16" title="Maybe" src="http://hive.csis.ul.ie/4065/group05/images/Maybe.png"/>Maybe</button>';
                                            echo '<button name="btnAction" class="btn btn-warning" type="submit" value="Goodbye"><img height="16" width="16" title="Goodbye" src="http://hive.csis.ul.ie/4065/group05/images/Goodbye.png"/>Goodbye</button>';
                                            echo '<button name="btnAction" class="btn btn-danger" type="</div>submit" value="Report"><img height="16" width="16" title="Report" src="http://hive.csis.ul.ie/4065/group05/images/Report.png"/>Report</button>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-5 col-md-5 col-lg-6 col-xs-1"></div>
                                <div class="col-sm-7 col-md-7 col-lg-6 col-xs-10 mobilePad"  data-toggle="collapse" data-target="#howItWorks" style="font-weight: bold;font-size: 10pt;padding-left: 0px;color: black;cursor: pointer;text-decoration: underline;"><img height="32" width="32" title="" src="http://hive.csis.ul.ie/4065/group05/images/question.png"/>How it Works
                                    <span class="caret"></span>
                                </div>  
                            </div>
                            <div class="form-group" style="margin-bottom: 0px;">
                                <div class="col-sm-4 col-md-4 col-lg-6 col-xs-1"></div>
                                <div id="howItWorks" class="col-sm-8 col-md-8 col-lg-6 col-xs-10 collapse mobilePad" style="padding-right: 17px;">
                                    <ul type="disc" style="padding-left: 0px;">
                                        <li>Your Password must have minimum 6 characters.</li>
                                        <li>Your Password must contain at least one number, one uppercase, lowercase & special character.</li>
                                        <li>Your Password must not contain your Username.</li>
                                        <li>Your Password must not contain Character or Number repetition.</li>

                                    </ul> 
                                </div>
                            </div>   

                    </div>

                    </fieldset>
                </div>

            </div>
        </form>

    </body>
</html>

