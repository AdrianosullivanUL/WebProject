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


$matching_user_id = $_SESSION['matching_user_id'];
$message = "";
// echo " matching_user_id " . $matching_user_id;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id = $_SESSION['user_id'];
    $matching_user_id = $_SESSION['matching_user_id'];
    if ($_POST['btnAction'] == "Suspend") {

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
    }
    if ($_POST['btnAction'] == "Bar") {

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
    }

    if ($_POST['btnAction'] == "Like" || $_POST['btnAction'] == "Maybe" || $_POST['btnAction'] == "Goodbye" || $_POST['btnAction'] == "Report" || $_POST['btnAction'] == "View") { // Get Match view row for subsequent buttons
// Get the match entry for both users
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
            if ($row['match_user_id_1'] == $user_id)
                $_SESSION['matching_user_id'] = $row['match_user_id_2'];
            else
                $_SESSION['matching_user_id'] = $row['match_user_id_1'];
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
        }
    }
}

// Get the user profile of the logged in user
$sql = "select * from user_profile where id =" . $user_id . ";";
//echo $sql;
$isAdmin = false;
$user_name = " ";
if ($result = mysqli_query($db_connection, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $isAdmin = $row['is_administrator'];
            $user_name = $row['first_name'] . " " . $row['surname'];
        }
    }
}
// Get the user profile of the being viewed profile


$sql = "SELECT up.*, DATE_FORMAT(up.date_of_birth,'%d/%m/%Y') as formatted_dob, g1.gender_name, g2.gender_name as preferred_gender_name, rt.relationship_type, c.city "
        . " fROM user_profile up "
        . " left join gender g1 on g1.id = up.gender_id "
        . " left join gender g2 on g2.id = up.gender_preference_id "
        . " left join relationship_type rt on rt.id = up.relationship_type_id "
        . " left join city c on c.id = up.city_id "
        . " where up.id =" . $matching_user_id . ";";
//echo $sql;
$mibio = "";
$picture = "";
$first_name = "";
$surname = "";
if ($result = mysqli_query($db_connection, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $mybio = $row['my_bio'];
            if (strlen($row['picture']) > 0) {
                $picture = base64_encode($row['picture']);
            } else {
                
            }
            $first_name = $row['first_name'];
            $surname = $row['surname'];


            $email = $row['email'];
            $gender = $row['gender_name'];
            $preferredGender = $row['preferred_gender_name'];
            $dob = $row['date_of_birth'];
            $relationshipType = $row['relationship_type'];
            $ageSelectionFrom = $row['from_age'];
            $ageSelectionTo = $row['to_age'];
            $travelDistance = $row['travel_distance'];
            $city = $row['city'];
        }
    }
}
?>
<html lang="en">
    <head>
        <title>view matching profile</title>
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
            <a class="active">View Profile</a>
            <?php
            if ($isAdmin == true)
                echo '<a href="AdminScreen.php" title="AdminScreen">' . $user_name . '(Admin. Mode)</a>';
            else
                echo '<a href="MeetingSpace.php" title="Meeting Space">' . $user_name . '</a>';
            $sql = "select first_name, surname from user_profile where id = " . $user_id;
//echo $sql;
            /* $result = execute_sql_query($db_connection, $sql);
              if ($result != null) {
              while ($row = mysqli_fetch_array($result)) {
              echo $row['first_name'] . " " . $row['surname'];
              }
              } */
            ?>


        
        <div class="topnav-right">
            <a href="MatchFind.php" title="Find People"><img height="16" width="16"   src='http://hive.csis.ul.ie/4065/group05/images/Find.png'/>Match Finder</a>
            <a href="UpdateProfile.php">Update Profile</a>
            <a data-toggle = "collapse" data-target = "#Help"><img height="16" width="16" src='http://hive.csis.ul.ie/4065/group05/images/help-faq.png'/><font color="white">Help</font></a>
            <a href="Logout.php">Log Out</a>
        </div>
    </div>

    <div id="Help" class="collapse container">
        <fieldset class="landscape_nomargin" style="max-width: min-width 0;padding:.75em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .9;">                                                                
            <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Help</legend>
            <div class="container">
                <h5>You are now in <b>View Selected match</b></h5>
                <br><b>How did you get here ? </b><br>
                You have selected <img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/View.png"/><b><font color="turquoise"> View </font></b> in the <b>Meeting Space</b><br>
                <br><b> What do i do next?</b>
                <br>From here you can checkout the Bio, Interests, and Profile details of the person you selected
                <br>If you are interested in this person, click <img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Like.png"/><b><font color="blue"> Like </font></b>, 
                this will put them into the <b>"People who I Like"</b>section in the Meeting Space,they will stay there until they also like you.
                <br>If they also <img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Like.png"/><b><font color="blue"> Like </font></b> you then they will move to your chat area and you can both chat then.
                <br>People who have liked you show in <b>"People who like me"</b> and again if you like them they move into the chat area.
                <br>If you want to remove a profile from your page, click on <img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Goodbye.png"/><b><font color="yellow"> Goodbye </font></b> and they are removed.
                <br>Have you been offended by someone? Click on <img height="16" width="16" title="View" src="http://hive.csis.ul.ie/4065/group05/images/Report.png"/><b><font color="red">Report </font></b>and the system moderator will review their account and if required Suspend
            </div>
        </fieldset>
    </div>                
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-horizontal" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >
        <div class="col-md-10 col-md-offset-1" >

            <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .75em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .9;">
                <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">View Your Selected Match</legend>
                <div class="container-fluid">
                    <div class ="row">
                        <div class=" col-sm-12 col-lg-12 col-xs-12 " style="border-style: solid;border-color: silver;background-color:transparent; opacity: 0.0;">
                        </div> 
                    </div>
                </div>
                <div class ="row">
                    <div class="col-xs-0 col-sm-1" style="background-color:transparent; opacity: 0.0;">
                        <p> " "</p>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-xs-6" style="border-style:solid; border-color: silver; background-color:white; opacity: 1;">
                        <h4><?php echo $first_name . " " . $surname ?> </h4>
                        <!-- Display Image -->
                        <?php
                        if (strlen($picture) > 0) {
                            echo '<img class="portrait"src="data:image/jpeg;base64,' . $picture . '"/><i></i>';
                        } else {
                            echo ("<img class='portrait' src='http://hive.csis.ul.ie/4065/group05/images/camera-photo-7.png'/><i></i>");
                        }
                        ?>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-xs-6" style="border-style:solid; border-color: silver;background-color:white; opacity: 0.9;">
                        <h3><?php echo $first_name ?>'s bio</h3>
                        <h5><?php echo $mybio ?></h5>

                    </div>
                </div>
                <div class ="row">
                    <div class="col-sm-12" style="background-color:transparent; opacity: 0.0;">

                    </div>
                </div>

                <div class ="row">
                    <div class="col-xs-0 col-sm-1" style="background-color:transparent; opacity: 0.0;">
                        <p>     </p>
                        <p>     </p>    
                    </div>
                    <div class ="col-xs-4 col-sm-4 col-xs-6"style="border-style:solid; border-color: silver;background-color:white;; opacity: 0.9;">
                        <?php
                        echo "<h3> $first_name's Interests </h3> ";
                        $sql = "SELECT description  FROM interests "
                                . " LEFT JOIN user_interests ON interest_id = interests.id "
                                . " where user_id = " . $matching_user_id . ";";
                        $interest = "";
                        if ($result = mysqli_query($db_connection, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    $description = $row['description'];
                                    echo("<h5> * $description ");
                                }
                            }
                        }
                        ?>
                    </div>


                    <div class="col-sm-6 col-md-6 col-lg-6 col-xs-6 mobileLabel"style="border-style:solid; border-color: silver;background-color:white; opacity: 0.9;text-align:left">

                        <div class="form-group">
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-8 mobileLabel" style=" font-size: 10pt; padding-top: 8px; text-align: left;">
                                <p>Date of Birth:</div>
                            <div class="col-sm-6 col-md-6 col-lg-5 col-xs-8 mobileLabel">
                                <input style="border-radius: 4px" type="date"  class="form-control"  value= "<?php echo $dob; ?>">
                            </div> 
                        </div>


                        <div class="form-group">
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-8 mobileLabel" style=" font-size: 10pt; padding-top: 8px; text-align: left;">
                                Nearest City:</div>  
                            <div class="col-sm-6 col-md-6 col-lg-5 col-xs-8 mobileLabel">
                                <input style="border-radius: 4px" type="text"  class="form-control"  value= "<?php echo $city; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-8 mobileLabel" style=" font-size: 10pt; padding-top: 8px; text-align: left;">
                                Gender:</div>  
                            <div class="col-sm-6 col-md-6 col-lg-5 col-xs-8 mobileLabel">
                                <input style="border-radius: 4px" type="text"  class="form-control"  value= "<?php echo $gender; ?>">
                            </div>
                        </div>
                        <div class="form-group">

                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-8 mobileLabel" style=" font-size: 10pt; padding-top: 8px; text-align: left;">
                                Preferred Gender:</div>  
                            <div class="col-sm-6 col-md-6 col-lg-5 col-xs-8 mobileLabel">
                                <input style="border-radius: 4px" type="text"  class="form-control" value= "<?php echo $preferredGender; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-8 mobileLabel" style=" font-size: 10pt; padding-top: 8px; text-align: left;">
                                Seeking Age Profile:</div>  
                            <div class="col-sm-6 col-md-6 col-lg-5 col-xs-8 mobileLabel">
                                <input style="border-radius: 4px" type="text"  class="form-control" value= "<?php echo $ageSelectionFrom; ?>"><p> to </>
                                    <input style="border-radius: 4px" type="text"  class="form-control" value= "<?php echo $ageSelectionTo; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 10pt; padding-top: 8px; text-align: left;">
                                Distance Will Travel:</div>  
                            <div class="col-sm-6 col-md-6 col-lg-5 col-xs-9 mobileLabel">
                                <input style="border-radius: 4px" type="text"  class="form-control" value= "<?php echo $travelDistance; ?>">
                            </div> 
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 10pt; padding-top: 8px; text-align: left;">
                                Relationship Type:</div>  
                            <div class="col-sm-6 col-md-6 col-lg-5 col-xs-9 mobileLabel">
                                <input style="border-radius: 4px" type="text"  class="form-control" value= "<?php echo $relationshipType; ?>">
                            </div> 

                        </div>
                    </div>


                    <div class="col-sm-8 col-md-8 col-lg-12 col-xs-10 mobileLabel" style=" text-align: center;">
                        <?php
                        if (strlen($message) > 0) {
                            echo "<div class='alert alert-danger'>";
                            echo "<p>" . $message . "</p>";
                            echo "</div>";
                        }

                        if ($isAdmin == true) {
                          
                            echo '<button name="btnAction" class="btn btn-primary" type="submit" value="Suspend"><img height="16" width="16" title="Suspend" src="http://hive.csis.ul.ie/4065/group05/images/Maybe.png"/>Suspend (1 Month)</button>';
                            echo '<button name="btnAction" class="btn btn-dark" type="submit" value="Bar"><img height="16" width="16" title="Bar" src="http://hive.csis.ul.ie/4065/group05/images/Goodbye.png"/>Bar</button>';
                        } else {
                            echo '<button name="btnAction" class="btn btn-success" type="submit" value="Like"><img height="16" width="16"  title="Like" src="http://hive.csis.ul.ie/4065/group05/images/Like.png"/>Like</button>';
                            
                            echo '<button name="btnAction" class="btn btn-primary" type="submit" value="Maybe"><img height="16" width="16" title="Maybe" src="http://hive.csis.ul.ie/4065/group05/images/Maybe.png"/>Maybe</button>';
                            echo '<button name="btnAction" class="btn btn-warning" type="submit" value="Goodbye"><img height="16" width="16" title="Goodbye" src="http://hive.csis.ul.ie/4065/group05/images/Goodbye.png"/>Goodbye</button>';
                            echo '<button name="btnAction" class="btn btn-danger" type="</div>submit" value="Report"><img height="16" width="16" title="Report" src="http://hive.csis.ul.ie/4065/group05/images/Report.png"/>Report</button>';
                        }
                        ?>
                    </div>
                </div>


            </fieldset>
        </div>
    </form>
</body>
</html> 