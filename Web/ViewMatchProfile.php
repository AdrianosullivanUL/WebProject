<!DOCTYPE html>
<?php
session_start();
if ($_SESSION['user_logged_in'] == 0) {
    header("Location: Logon.php");
}
require_once 'database_config.php';
include 'group05_library.php';


$user_id = $_SESSION['user_id'];
$matching_user_id = $_SESSION['matching_user_id'];
// echo " matching_user_id " . $matching_user_id;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id = $_SESSION['user_id'];
    $matching_user_id = $_SESSION['matching_user_id'];
    
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
                    // $updateResult = update_match_status($db_connection, $matchId, $newStatus, 2);
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
        <title>view matching profile</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  
        <link rel="stylesheet" href="StyleSheet.css">

    </head>
    <body>
        <form action="/ViewMatchProfile.php" method="Post">
            <div class="topnav">
                <a class="active">MATCHED PROFILE</a>
                <a href="MeetingSpace.php">Home</a>
                <div class="topnav-right">
                    <a href="UpdateProfile.php">Update Profile</a>
                    <a href="logout.php">Log Out</a>
                </div>
            </div>
            <div class="col-md-10 col-md-offset-2" >
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-horizontal" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >
                    <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .75em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .8;">
                        <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">View Your Selected Match</legend>
                        <div class="container-fluid">
                            <div class ="row">
                                <div class="col-xs-12 col-sm-12 col-lg-12 col-xs-12 " style="border-style: solid;border-color: silver;background-color:transparent; opacity: 0.0;">
                                </div> 
                            </div>
                        </div>
                        <div class ="row">
                            <div class="col-xs-0 col-sm-1" style="background-color:transparent; opacity: 0.0;">
                                <p> " "</p>
                            </div>
                            <div class="col-xs-6 col-sm-4" style="border-style:solid; border-color: silver; background-color:white; opacity: 1;">
                                <?php
                                $sql = "SELECT * FROM user_profile where id =" . $matching_user_id . ";";
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
                                        }
                                    }
                                }
                                ?>
                                <h4><?php echo $first_name . " " . $surname ?> </h4>
                                <!-- Display Image -->
                                <?php
                                if (strlen($picture) > 0) {
                                    echo '<img class="portrait"src="data:image/jpeg;base64,' . $picture . '"/><i></i>';
                                } else {
                                    echo ("<img class='portrait' src='camera-photo-7.png'/><i></i>'");
                                }
                                ?>
                            </div>
                            <div class="col-xs-6 col-sm-6" style="border-style:solid; border-color: silver;background-color:white; opacity: 0.9;">
                                <h3><?php echo $first_name ?>'s bio</h3>
                                <h4><?php echo $mybio ?></h4>

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
                            <div class ="col-xs-4 col-sm-4"style="border-style:solid; border-color: silver;background-color:white;; opacity: 0.9;">
                                <?php
                                echo "<h3> $first_name's Interests </h3> ";
                                $sql = "SELECT description
                       FROM interests
                       LEFT JOIN user_interests ON interest_id = interests.id
                       where user_id = " . $matching_user_id . ";";
                                $interest = "";
                                if ($result = mysqli_query($db_connection, $sql)) {
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_array($result)) {
                                            $description = $row['description'];
                                            echo("<h4>. $description ");
                                        }
                                    }
                                }
                                ?>
                            </div>
                            <div class="col-xs-6 col-sm-6"style="border-style:solid; border-color: silver;background-color:white; opacity: 0.9;text-align:right">


                                <br>
                                <br>
                                <br>
                                <br>
                                <button name="btnAction" class="btn btn-success" type="submit" value="Like">Like</button>
                                <button name="btnAction" class="btn btn-primary" type="submit" value="Maybe">Maybe</button>
                                <button name="btnAction" class="btn btn-warning" type="submit" value="Goodbye">Goodbye</button>
                                <button name="btnAction" class="btn btn-danger" type="submit" value="Report"> Report!</button>
                            </div>
                        </div>

                        <div class="col-xs-8 col-sm-8 col-lg-12"style="background-color:lavender; opacity: 0.8;text-align:right">



                        </div>

                    </fieldset>


            </div>

        </form>

    </body>
</html> 