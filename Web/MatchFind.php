<?php
session_start();
// redirect to the logon screen if the user is not logged in
require_once 'database_config.php';
include 'group05_library.php';
$user_id = $_SESSION['user_id'];
$session_hash = $_SESSION['session_hash'];
if (validate_logon($db_connection, $user_id, $session_hash) == false) {
    // User is not correctly logged on, route to Logon screen
    header("Location: Logon.php");
}


//echo "session user " . $user_id;
$city = "";
$first_name = "";
$surname = "";
$gender_name = "";
$preferred_gender_name = "";
$message = "";
$email = "";
$relationship_type = "";
$description = "";
$from_age = "";
$to_age = "";



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['btnAction'] == "View") { // View Profile
        $matching_user_id = $_POST['selected_user_id'];
        if ($matching_user_id == 0)
            $message = "Please click on a profile picture before clicking on these buttons";
        else {
            $_SESSION['matching_user_id'] = $matching_user_id;
            header("Location: ViewMatchProfile.php");
            exit();
        }
    }
    if ($_POST['btnAction'] == "Suspend") {
        if (isset($_POST['selected_user_id'])) {
            $matching_user_id = $_POST['selected_user_id'];
            $sql = 'select * from user_profile where id = ' . $matching_user_id . ";";
            $result = execute_sql_query($db_connection, $sql);
            if ($result == null) {
                echo "ERROR: Cannot find match entry to update status with, id =" . $matching_user_id;
            } else {
                while ($row = mysqli_fetch_array($result)) {
                    if ($row['user_status_id'] == 3 || $row['user_status_id'] == 4)
                        $message = "This user is already suspended or barred";
                    else {
                        $sql = "update user_profile set user_status_id = 3, user_status_date = now(), suspended_until_date = DATE_ADD(now(), INTERVAL 1 MONTH) where id = " . $matching_user_id;
                        $message = "User Suspended";
                        //echo $sql;
                        $result1 = execute_sql_update($db_connection, $sql);
                    }
                }
            }
        } else {
            $message = "You must select a profile first";
        }
    }
    if ($_POST['btnAction'] == "Bar") {
        if (isset($_POST['selected_user_id'])) {
            $matching_user_id = $_POST['selected_user_id'];
            $sql = 'select * from user_profile where id = ' . $matching_user_id . ";";
            $result = execute_sql_query($db_connection, $sql);
            if ($result == null) {
                echo "ERROR: Cannot find match entry to update status with, id =" . $matching_user_id;
            } else {
                while ($row = mysqli_fetch_array($result)) {
                    if ($row['user_status_id'] == 4)
                        $message = "This user is already barred";
                    else {
                        $sql = "update user_profile set user_status_id = 4, user_status_date = now(), suspended_until_date = DATE_ADD(now(), INTERVAL 99 MONTH) where id = " . $matching_user_id;
                        //echo $sql;
                        $result1 = execute_sql_update($db_connection, $sql);
                        $message = "User barred";
                    }
                }
            }
        } else {
            $message = "You must select a profile first";
        }
    }

    if ($_POST['btnAction'] == "Like" || $_POST['btnAction'] == "Maybe" || $_POST['btnAction'] == "Goodbye" || $_POST['btnAction'] == "Report") { // Get Match view row for subsequent buttons
        if (isset($_POST['selected_user_id']))
            $matching_user_id = $_POST['selected_user_id'];
        else
            $matching_user_id = 0;
        if ($matching_user_id == 0)
            $message = "Please click on a profile picture before clicking on these buttons";
        else {
            $sql = 'select * from matches_view '
                    . " where (match_user_id_1 = " . $user_id . " and match_user_id_2 = " . $matching_user_id . ")"
                    . " or (match_user_id_1 = " . $matching_user_id . " and match_user_id_2 = " . $user_id . ");";

            //echo $sql;
            $result = execute_sql_query($db_connection, $sql);
            if ($result == null) {
                // No entry found so create a new match entry
                $updatesql = "INSERT INTO match_table "
                        . " (match_user_id_1,match_user_id_2,match_date,user_1_match_status_id,user_1_match_status_date,user_2_match_status_id,user_2_match_status_date,system_generated_match)"
                        . " VALUES (" . $user_id . "," . $matching_user_id
                        . ",now(),(select id from status_master where is_match_table_status = true and status_description = 'Matched')"
                        . ",now(),(select id from status_master where is_match_table_status = true and status_description = 'Matched'),now(),0); ";
                $updateResult = execute_sql_update($db_connection, $updatesql);
// get the new entry 
                $result = execute_sql_query($db_connection, $sql);
            }
            while ($row = mysqli_fetch_array($result)) {
                $matchId = $row['match_id'];
                // Like Button
                // -----------
                if ($_POST['btnAction'] == "Like") { // Update Status
                    $valid = true;
                    if ($row['user_profile_1_match_status'] == 'Chatting' || $row['user_profile_2_match_status'] == 'Chatting') {
                        $message = "You are already chatting with this person";
                        $valid = false;
                    }
                    if ($valid == true) {
                        $newStatus = "";
                        if ($row['match_user_id_1'] == $user_id) {
                            //echo "match_user_id_1";
                            $updateUser1or2 = 1;
                            //   echo "user_profile_2_match_status" . $row['user_profile_2_match_status'];
                            if ($row['user_profile_2_match_status'] == 'Like') {
                                // both users must like each other before chatting
                                //echo $matchId . ",Chatting, 2";
                                $updateResult = update_match_status($db_connection, $matchId, 'Chatting', 2);
                                $newStatus = "Chatting";
                            } else
                                $newStatus = "Like";
                        } else {
                            $updateUser1or2 = 2;
                            //  echo "user_profile_1_match_status" . $row['user_profile_2_match_status'];
                            if ($row['user_profile_1_match_status'] == 'Like') {
                                //echo $matchId . ",Chatting, 1";
                                $updateResult = update_match_status($db_connection, $matchId, 'Chatting', 1);
                                // both users must like each other before chatting
                                $newStatus = "Chatting";
                            } else
                                $newStatus = "Like";
                        }
                        $updateResult = update_match_status($db_connection, $matchId, $newStatus, $updateUser1or2);
// Return to meeting space
                        header("Location: MeetingSpace.php");
                        exit();
                    }
                }
                // Maybe Button
                // -----------
                if ($_POST['btnAction'] == "Maybe") { // Update Status
                    $valid = true;
                    if ($row['user_profile_1_match_status'] == 'Matched' || $row['user_profile_2_match_status'] == 'Matched') {
                        $valid = true;
                    } else {
                        $message = "You cannot set the profile to Maybe";
                        $valid = false;
                    }
                    if ($valid == true) {
                        if ($row['match_user_id_1'] == $user_id)
                            $updateUser1or2 = 1;
                        else
                            $updateUser1or2 = 2;
                        $updateResult = update_match_status($db_connection, $matchId, 'Maybe', $updateUser1or2);
// Return to meeting space
                        header("Location: MeetingSpace.php");
                        exit();
                    }
                }
                // Goodbye Button
                // --------------
                if ($_POST['btnAction'] == "Goodbye") { // Update Status
                    if ($row['match_user_id_1'] == $user_id)
                        $updateUser1or2 = 1;
                    else
                        $updateUser1or2 = 2;
                    $updateResult = update_match_status($db_connection, $matchId, 'Goodbye', $updateUser1or2);
// Return to meeting space
                    header("Location: MeetingSpace.php");
                    exit();
                }
                // Report Button
                // -------------
                if ($_POST['btnAction'] == "Report") { // Update Status
                    if ($row['match_user_id_1'] == $user_id)
                        $updateUser1or2 = 1;
                    else
                        $updateUser1or2 = 2;
                    $updateResult = update_match_status($db_connection, $matchId, 'Report', $updateUser1or2);
// Return to meeting space
                    header("Location: MeetingSpace.php");
                    exit();
                }
                if ($_POST['btnAction'] == "Suspend") {
                    echo $matchId;
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
                        }
                    }
                }
            }
        }
    }
    // Get the user profile informatio
    $preferred_gender_name = $_POST['preferredGenderInput'];
    $city = $_POST['selectedCity'];
    $relationship_type = $_POST['selectedRelationship'];
    $description = $_POST['selectedHobby'];
    $from_age = $_POST['fromAge'];
    $to_age = $_POST['toAge'];
    // check the button selected (these are at the end of this form
    if ($_POST['btnAction'] == "Cancel") { // cancel the update
        // echo "Cancel pressed";
        header("Location: meetingspace.php");
        exit();
    }
    $sql = "SELECT up.* FROM user_profile up   "
            . " where up.id = '" . $user_id . "'";
    //echo $sql;
// 
    if ($result = mysqli_query($db_connection, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $isAdmin = $row['is_administrator'];
            }
        } else {
            $message = "Cannot find user profile";
        }
    }
} else {
    $sql = "SELECT up.*, c.city, r.relationship_type, g1.gender_name, g2.gender_name as preferred_gender_name, description "
            . " FROM user_profile up   "
            . " LEFT JOIN city c ON c.id = up.city_id   "
            . " LEFT JOIN relationship_type r ON r.id = up.relationship_type_id   "
            . " LEFT JOIN gender g1 ON g1.id = up.gender_preference_id  "
            . " left join gender g2 on g2.id = up.gender_id "
            . " left join user_interests ui on ui.user_id = up.id "
            . " left join interests i on i.id = ui.interest_id"
            . " where up.id = '" . $user_id . "'";
    // echo $sql;
// 
    if ($result = mysqli_query($db_connection, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $city = $row['city'];
                $first_name = $row['first_name'];
                $surname = $row['surname'];
                $gender_name = $row['gender_name'];
                $preferred_gender_name = $row['preferred_gender_name'];
                //  echo "city " . $city;
                //echo "pref " . $preferred_gender_name;
                $email = $row['email'];
                $relationshipTypeId = $row['relationship_type_id'];
                $relationship_type = $row['relationship_type'];
                $description = $row['description'];
                $from_age = $row['from_age'];
                $to_age = $row['to_age'];
                $isAdmin = $row['is_administrator'];
                $_SESSION['user_name'] = $first_name . " " . $surname;
                // echo "relationship " . $relationship_type;
                //echo "hobby " . $description;
            }
        } else {
            $message = "Cannot find user profile";
        }
    }
}
if (isset($_SESSION['user_name']))
    $user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Match find</title>
       <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  
        <link rel="stylesheet" href="StyleSheet.css">
    </head>
    <body>
        <div class="topnav">
            <a class="active">FIND YOUR MATCH</a>
            <?php
            if ($isAdmin == true)
                echo '<a href="AdminScreen.php" title="AdminScreen">' . $user_name . '(Admin. Mode)</a>';
            else
                echo '<a href="MeetingSpace.php" title="Meeting Space">' . $user_name . '</a>';
            ?>
            <div class="topnav-right">
                <a data-toggle = "collapse" data-target = "#Help1"><img height="16" width="16" src='http://hive.csis.ul.ie/4065/group05/images/help-faq.png'/><font color="white">Help</font></a>
                <a href="Logout.php" title="Log out of the system"><img height="16" width="16" src='http://hive.csis.ul.ie/4065/group05/images/Logoff.png'/>Logoff</a>
            </div>
        </div>

        <div id="Help1" class="collapse container">
            <fieldset class="landscape_nomargin" style="max-width: min-width 0;padding:.75em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .9;">                                                                
                <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Help</legend>
                <div class="container">
                    <h5>You are now in <b>Match Find screen</b></h5>
                    <br>
                    <br><b> What do I do next?</b>
                    <br>Your preferences are populated by default in the search filters. Change these around to suit your match and then click submit.<br>
                    Your search results will be returned in the form of a series of profile pictures, click on a profile picture and select from one of the following:
                    <ul>
                        <li><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Like.png"/>Like<br>You like this person and would like to chat with them. When you use this option, the person is moved to your <b>People who I like</b> section of your meeting space.</li>
                        <li><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/View.png"/>View<br>Have a look at this persons profile, from there you can also action their profile</li>
                        <li><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Maybe.png"/>Maybe<br>If you are not sure about a person, you can click this button to have them remain in your <b>Possible Matches</b> section  of your meeting space.
                        <li><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Goodbye.png"/>Goodbye<br>If you are not interested, click this to remove the profile from your meeting space</li>
                        <li><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Report.png"/>Report<br>Feel offended by they persons profile, an in appropriate image for instance? Click to have their profile reviewed by the system moderator</li>
                    </ul>


                </div>
            </fieldset>
        </div>



        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1" >
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-horizontal" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >
                        <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .35em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .9;">
                            <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Perfect Match Filter</legend>

                            <div class="form-group">
                                <div class="col-sm-8 col-md-8 col-lg-8 col-xs-8" style="text-align: right;">
                                    <span style="color: red">*</span> <span style="font-size: 8pt;">mandatory fields</span>
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom: 0px;">
                                <div class="col-sm-8 col-md-8 col-lg-8 col-xs-8"></div>
                                <div class="col-sm-8 col-md-8 col-lg-7 col-xs-10 mobilePad" id="message10" style=" font-size: 15pt;padding-left: 0px;"></div>

                            </div>

                            <div class="form-group">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 15pt; padding-top: 10px; text-align: left;">
                                    Gender Preference <span style="color: red">*</span> :</div>
                                <div class="col-sm-6 col-md-6 col-lg-5 col-xs-9 mobileLabel">
                                    <select name="preferredGenderInput" class="selectpicker form-control"style=" font-size:15pt;height: 40px;">
                                        <option ></option>";
                                        <?php
                                        $sql = "select gender_name  from gender order by gender_name";
                                        if ($result = mysqli_query($db_connection, $sql)) {
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_array($result)) {
                                                    if ($preferred_gender_name == $row['gender_name'])
                                                        echo "<option selected='selected'>" . $row['gender_name'] . "</option>";
                                                    else
                                                        echo "<option>" . $row['gender_name'] . "</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 15pt;padding-top: 7px; text-align: left;">
                                    Preferred Location <span style="color: red">*</span> :</div>
                                <div class="col-sm-6 col-md-6 col-lg-5 col-xs-9 mobileLabel">
                                    <select name ="selectedCity" class="selectpicker form-control"style=" font-size:15pt;height: 40px;">
                                        <option ></option>";
                                        <?php
// get the user_profile record here
// 

                                        $sql = "select city from city";
                                        if ($result = mysqli_query($db_connection, $sql)) {
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_array($result)) {
                                                    //echo $city . " " . $row['city'];
                                                    if ($city == $row['city'])
                                                        echo "<option selected='selected'>" . $row['city'] . "</option>";
                                                    else
                                                        echo "<option>" . $row['city'] . "</option>";
                                                }
                                            }
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <!--Relationship Type drop down-->
                            <div class="form-group">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 15pt;padding-top: 7px; text-align: left;">
                                    Relationship Type <span style="color: red">*</span> :</div>
                                <div class="col-sm-6 col-md-6 col-lg-5 col-xs-9 mobileLabel">
                                    <select name= "selectedRelationship"class="selectpicker form-control"style=" font-size:15pt;height: 40px;">
                                        <option ></option>";
                                        <?php
                                        $sql = "select relationship_type from relationship_type";
                                        if ($result = mysqli_query($db_connection, $sql)) {
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_array($result)) {
                                                    if ($relationship_type == $row['relationship_type'])
                                                        echo "<option selected='selected'>" . $row['relationship_type'] . "</option>";
                                                    else
                                                        echo "<option>" . $row['relationship_type'] . "</option>";
                                                }
                                            }
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 15pt;padding-top: 7px; text-align: left;">
                                    Interests <span style="color: red">*</span> :</div>
                                <div class="col-sm-6 col-md-6 col-lg-5 col-xs-9 mobileLabel">
                                    <select name= "selectedHobby"class="selectpicker form-control"style=" font-size:15pt;height: 40px;">
                                        <option ></option>";
                                        <?php
                                        $sql = "select description from interests";
                                        if ($result = mysqli_query($db_connection, $sql)) {
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_array($result)) {
                                                    if ($description == $row['description'])
                                                        echo "<option selected='selected'>" . $row['rdescrition'] . "</option>";
                                                    else
                                                        echo "<option>" . $row['description'] . "</option>";
                                                }
                                            }
                                        }
                                        ?> 
                                    </select>
                                </div>

                                <?php
                                if (!empty($_POST['check_list'])) {
                                    foreach ($_POST['check_list'] as $check) {
                                        echo $check;
                                        //echoes the value set in the HTML form for each checked checkbox.
                                        //so, if I were to check 1, 3, and 5 it would echo value 1, value 3, value 5.
                                        //in your case, it would echo whatever $row['Report ID'] is equivalent to.
                                    }
                                }
                                ?>
                            </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12"></div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12"></div>
                                <div class="form-group">
                                    <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                    <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 15pt;padding-top: 7px; text-align: left;">
                                        Seeking Age Profile <span style="color: red">*</span> :</div>
                                    <div class="col-sm-2 col-md-2 col-lg-2 col-xs-8 mobileLabel">
                                        <input name= "fromAge" type="range" min="18" max="100" value="18" step="2" list="tickmarks" id="rangeInput" oninput="output.value = rangeInput.value">
                                        <datalist id="tickmarks">
                                            <option value="18 to 100">18</option>
                                            <option>18</option>
                                            <option>40</option>
                                            <option>60</option>
                                            <option>80</option>
                                            <option>100</option>
                                        </datalist>
                                        <output id="output" for="rangeInput"> Min Age : 18</output> <!-- Just to display selected Age -->
                                    </div>
                                    <div class="col-sm-2 col-md-2 col-lg-2 col-xs-8 mobileLabel">
                                        <input name= "toAge" type="range" min="18" max="100" value="100" step="2" list="tickmarks" id="rangeInput2" oninput="output2.value = rangeInput2.value">
                                        <datalist id="tickmarks">
                                            <option value="18 to 100">100</option>
                                            <option>20</option>
                                            <option>40</option>
                                            <option>60</option>
                                            <option>80</option>
                                            <option>100</option>
                                        </datalist>
                                        <output id="output2" for="rangeInput2"> Max Age : 100</output> <!-- Just to display selected Age -->
                                    </div>
                                </div>
                                <div class="form-group">

                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12"></div>




                                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12"></div>
                                <div class="form-group">
                                    <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                    <div class="col-sm-11 col-md-11 col-lg-11 col-xs-10" style="text-align:center;">
                                        <?php
                                        if (strlen($message) > 0) {
                                            echo "<div class='alert alert-danger'>";
                                            echo "<p>" . $message . "</p>";
                                            echo "</div>";
                                        }
                                        ?>
                                        <button class="btn btn-primary" id="valuser" type="submit" name="btnAction" name="btnAction" value="Submit" class="btn btn-success">
                                            Submit</button>
                                    </div>

                                    <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                </div>
                                <div class="form-group" style="text-align:center;font-weight:bold">


                                    <div class="row">
                                        <div class="col-md-12 col-md-offset-0.5" >
                                            <?php
                                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                                // check the button selected (these are at the end of this form
                                                if ($_POST['btnAction'] == "Submit") { // Call Edit Profile
                                                    // Sql to pull data from other users and match selected gender selectedcity and relationshiptyoe
                                                    $sql = "SELECT up.*, g1.gender_name, g2.gender_name as preferred_gender_name, c.city, r.relationship_type " // i.description "
                                                            . "FROM user_profile up"
                                                            . " left join gender g1 on g1.id = up.gender_id "
                                                            . " left join gender g2 on g2.id = up.gender_preference_id  "
                                                            . " left join city c on c.id = up.city_id "
                                                            . " left join relationship_type r on r.id=up.relationship_type_id"
                                                            // . " left join user_interests ui on ui.user_id = up.id "
                                                            // . " left join interests i on i.id = ui.interest_id"
                                                            . " where is_administrator = FALSE AND black_listed_user = false "
                                                            . " and up.id != " . $user_id;

                                                    if (strlen($preferred_gender_name) > 0)
                                                        $sql = $sql . " and gender_id in (select id from gender where gender_name = '" . $preferred_gender_name . "')"
                                                                . " ";
                                                    if (strlen($city) > 0)
                                                        $sql = $sql . " And city_id in (select id from city where city = '" . $city . "')";

                                                    if (strlen($relationship_type) > 0)
                                                        $sql = $sql . " and relationship_type_id in (select id from relationship_type where relationship_type = '" . $relationship_type . "')";
                                                    if (strlen($description) > 0)
                                                        $sql = $sql . " And up.id in (select user_id  from user_interests ui join interests i on i.id = ui.interest_id  where description = '" . $description . "')";
                                                    if (strlen($from_age) > 0)
                                                        $sql = $sql . " And up.id in (select id from user_profile where from_age > '" . $from_age . "')";
                                                    if (strlen($to_age) > 0)
                                                        $sql = $sql . " And up.id in (select id from user_profile where to_age < '" . $to_age . "')";
                                                    // echo $sql;

                                                    $pictureIndex = 0;
                                                    $matchesFound = true;
                                                    $result = execute_sql_query($db_connection, $sql);
                                                    if ($result == null) {
                                                        echo "<div class='alert alert-info col-md-12'>";
                                                        echo "No matches found";
                                                        echo "</div>";
                                                        $matchesFound = false;
                                                    } else {
                                                        while ($row = mysqli_fetch_array($result)) {
                                                            $pictureIndex++;
                                                            //echo ("<li>");
                                                            // echo "<div class='container>";
                                                            echo "        <input type='radio' class='hideinput' name='selected_user_id' id='radio" . $pictureIndex . "' value='" . $row['id'] . "'/>";
                                                            echo "        <label for='radio" . $pictureIndex . "'>";
                                                            echo "        <label >" . $row['first_name'] . " " . $row['surname'] . "</label>";
                                                            echo "<br>";
                                                            if (strlen($row['picture']) > 0)
                                                                echo "<img class='rounded-circle selectimg'  height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["picture"]) . "'/>";
                                                            else
                                                                echo ("<img class='selectimg' height='100' width='100' src='http://hive.csis.ul.ie/4065/group05/images/camera-photo-7.png'/><i></i>");
                                                            echo "</label>";
                                                        }
                                                    }


                                                    if ($result == null) {
                                                        $message = "ERROR: Cannot match entry " . $user_id;
                                                    } else {
                                                        while ($row = mysqli_fetch_array($result)) {
                                                            echo $row['first_name'] . " " . $row['city'] . " " . $row['gender_name'] . " " . $row['preferred_gender_name'] . "<br>";
                                                            if (strlen($row['picture']) > 0)
                                                                echo "<img class='rounded-circle'  height='32' width='32' src='data:image/jpeg;base64," . base64_encode($row["picture"]) . "'/>";
                                                            else
                                                                echo ("<img height='32' width='32' src='http://hive.csis.ul.ie/4065/group05/images/camera-photo-7.png'/><i></i>");
                                                        }
                                                    }
                                                    if ($matchesFound == true) {
                                                        echo '<div>';
                                                        if ($isAdmin == true) {
                                                            echo '<button name="btnAction" class="btn btn-info" type="submit" value="View"><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/View.png"/>View</button>';
                                                            echo '<button name="btnAction" class="btn btn-primary" type="submit" value="Suspend"><img height="16" width="16" title="Suspend" src="http://hive.csis.ul.ie/4065/group05/images/Maybe.png"/>Suspend (1 Month)</button>';
                                                            echo '<button name="btnAction" class="btn btn-dark" type="submit" value="Bar"><img height="16" width="16" title="Bar" src="http://hive.csis.ul.ie/4065/group05/images/Goodbye.png"/>Bar</button>';
                                                            echo '<button name="btnAction" class="btn btn-danger" type="</div>submit" value="Report"><img height="16" width="16" title="Report" src="http://hive.csis.ul.ie/4065/group05/images/Report.png"/>Report</button>';
                                                        } else {
                                                            echo '<button name="btnAction" class="btn btn-success" type="submit" value="Like"><img height="16" width="16"  title="Like" src="http://hive.csis.ul.ie/4065/group05/images/Like.png"/>Like</button>';
                                                            echo '<button name="btnAction" class="btn btn-info" type="submit" value="View"><img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/View.png"/>View</button>';
                                                            echo '<button name="btnAction" class="btn btn-primary" type="submit" value="Maybe"><img height="16" width="16" title="Maybe" src="http://hive.csis.ul.ie/4065/group05/images/Maybe.png"/>Maybe</button>';
                                                            echo '<button name="btnAction" class="btn btn-warning" type="submit" value="Goodbye"><img height="16" width="16" title="Goodbye" src="http://hive.csis.ul.ie/4065/group05/images/Goodbye.png"/>Goodbye</button>';
                                                            echo '<button name="btnAction" class="btn btn-danger" type="</div>submit" value="Report"><img height="16" width="16" title="Report" src="http://hive.csis.ul.ie/4065/group05/images/Report.png"/>Report</button>';
                                                        }
                                                        echo '</div>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>


                                </div>
                            </div<!-- and up.id in (select user_id from user_interests where interest_id 
                                    //in (select id from interests where description in ('Music','Sport','Traveling')));-->
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
    </div>
    </body>
</html>