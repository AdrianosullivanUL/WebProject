<?php
session_start();
// redirect to the logon screen if the user is not logged in
if ($_SESSION['user_logged_in'] == 0) {
    header("Location: Logon.php");
}

require_once 'database_config.php';
include 'group05_library.php';


$user_id = $_SESSION['user_id'];
//$matching_user_id = $_SESSION['matching_user_id'];
//echo "session user " . $user_id;
//echo " here";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//echo " here1";
    $_SESSION['user_id'] = $user_id;


    if (isset($_POST['selected_user']))
        $matchId = $_POST['selected_user'];
    else
        $matchId = 0;

    if ($matchId == 0) {
        $message = "No user Selected.";
        //header("Location: Logout.php");
    } else {
        if ($_POST['btnAction'] == "View") { // View Profile
            $matching_user_id = $_POST['selected_user_id'];
            $_SESSION['matching_user_id'] = $matchId;
            header("Location: ViewMatchProfile.php");
            exit();
        }
        if ($_POST['btnAction'] == "Suspend") {
            $sql = 'select * from user_profile where id = ' . $matchId . ";";
            $result = execute_sql_query($db_connection, $sql);
            if ($result == null) {
                echo "ERROR: Cannot find match entry to update status with, id =" . $matchId;
            } else {
                while ($row = mysqli_fetch_array($result)) {
                    if ($row['user_status_id'] == 3 || $row['user_status_id'] == 4)
                        $message = "This user is already suspended or barred";
                    else {
                        $sql = "update user_profile set user_status_id = 3, user_status_date = now(), suspended_until_date = DATE_ADD(now(), INTERVAL 1 MONTH) where id = " . $matchId;
                        //echo $sql;
                        $result1 = execute_sql_update($db_connection, $sql);
                        $message = "User Suspended";
                    }
                }
            }
        }

        if ($_POST['btnAction'] == "RemoveSuspension") {
            $sql = 'select * from user_profile where id = ' . $matchId . ";";
            $result = execute_sql_query($db_connection, $sql);
            if ($result == null) {
                echo "ERROR: Cannot find match entry to update status with, id =" . $matchId;
            } else {
                while ($row = mysqli_fetch_array($result)) {
                    if ($row['user_status_id'] != 3)
                        $message = "This user is not suspended";
                    else {
                        $sql = "update user_profile set user_status_id = 2, user_status_date = now(), suspended_until_date = null where id = " . $matchId;
                        $result1 = execute_sql_update($db_connection, $sql);
                        $message = "User can log in now and resume using system";
                    }
                }
            }
        }
        if ($_POST['btnAction'] == "Bar") {
            $sql = 'select * from user_profile where id = ' . $matchId . ";";
            $result = execute_sql_query($db_connection, $sql);
            if ($result == null) {
                echo "ERROR: Cannot find match entry to update status with, id =" . $matchId;
            } else {
                while ($row = mysqli_fetch_array($result)) {
                    if ($row['user_status_id'] == 4)
                        $message = "This user is already barred";
                    else {
                        $sql = "update user_profile set user_status_id = 4, user_status_date = now(), suspended_until_date = DATE_ADD(now(), INTERVAL 99 MONTH) where id = " . $matchId;
                        //echo $sql;
                        $result1 = execute_sql_update($db_connection, $sql);
                        $message = "User Barred";
                    }
                }
            }
        }
        if ($_POST['btnAction'] == "un-bar") {
            $sql = 'select * from user_profile where id = ' . $matchId . ";";
            $result = execute_sql_query($db_connection, $sql);
            if ($result == null) {
                echo "ERROR: Cannot find match entry to update status with, id =" . $matchId;
            } else {
                while ($row = mysqli_fetch_array($result)) {
                    if ($row['user_status_id'] != 4)
                        $message = "This user is not barred";
                    else {
                        $sql = "update user_profile set user_status_id = 2, user_status_date = now(), suspended_until_date = null where id = " . $matchId;
                        //echo $sql;
                        $result1 = execute_sql_update($db_connection, $sql);
                        $message = "User can use the system again";
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang = "en">
    <head>
        <title>Admin</title>
        <meta charset = "utf-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1">
        <link rel = "stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  
        <link rel="stylesheet" href="StyleSheet.css">
    </head>
    <body>

        <div class="topnav">
            <a class="active">ADMINISTRATION</a>
            <a href="AdminScreen.php" title="Meeting Space"></a>
            <div class="topnav-right">
                <a href="MatchFind.php" title="Find People"><img height="16" width="16"   src='http://hive.csis.ul.ie/4065/group05/images/Find.png'/>Profile Finder</a>
                <a href="Logout.php" title="Log out of the system"><img height="16" width="16"  src='http://hive.csis.ul.ie/4065/group05/images/Logoff.png'/>Logoff</a>

            </div>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-group" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >
            <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .75em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .8;">
                <div class="col-xs-12 col-sm-12 col-lg-12" style="border-style:solid; border-color: silver;background-color:white; opacity: 1;">

                    <!-- 
                    REPORTED
                    --------
                    -->
                    <h3>Reported</h3>
                    <?php
                    $pictureIndex = 0;
                    $sql = "SELECT * FROM matches_view where  "
                            . " (user_profile_1_match_status = 'Report' or user_profile_2_match_status = 'Report')";
                    // echo $sql;
                    $result = execute_sql_query($db_connection, $sql);
                    if ($result == null) {
                        echo "<br><p>No matches found</p>";
                    } else {
                        while ($row = mysqli_fetch_array($result)) {
                            $pictureIndex++;
                            echo ("<li>");
                            if ($row['user_profile_1_match_status'] == 'Report') {
                                echo "        <input type='radio' class='hideinput' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['match_user_id_1'] . "'/>";
                                echo "        <label for='radio" . $pictureIndex . "'>";
                                echo "        <label >Reported By " . $row['user_profile_2_first_name'] . " " . $row['user_profile_2_surname'] . "</label>";
                                echo "<br>";
                                if (strlen($row['user_profile_1_picture']) > 0)
                                    echo "<img class='rounded-circle selectimg' height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_1_picture"]) . "'/>";
                                else
                                    echo ("<img class='rounded-circle selectimg' height='100' width='100' src='http://hive.csis.ul.ie/4065/group05/images/camera-photo-7.png'/><i></i>");
                                echo "<br> " . $row['user_profile_1_first_name'] . " " . $row['user_profile_1_surname'];
                                echo "</label>";
                            } else {
                                echo "        <input type='radio' class='hideinput' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['match_user_id_2'] . "'/>";
                                echo "        <label for='radio" . $pictureIndex . "'>";
                                echo "        <label >Reported By " . $row['user_profile_1_first_name'] . " " . $row['user_profile_1_surname'] . "</label>";
                                echo "<br>";
                                if (strlen($row['user_profile_2_picture']) > 0)
                                    echo "<img class='rounded-circle selectimg'  height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_2_picture"]) . "'/>";
                                else
                                    echo ("<img class='selectimg' height='100' width='100' src='http://hive.csis.ul.ie/4065/group05/images/camera-photo-7.png'/><i></i>");
                                 echo "<br> " . $row['user_profile_2_first_name'] . " " . $row['user_profile_2_surname'];
                                echo "</label>";
                            }
                            echo "    </li>";
                            echo "</ul>";
                        }
                    }
                    ?>
                    <p><b>Click on Photograph and do one of the following:</b></p>
                    <button name="btnAction" class="btn btn-info" type="submit" value="View"><img height="16" width="16" title="View" src='http://hive.csis.ul.ie/4065/group05/images/View.png'/>View</button>
                    <button name="btnAction" class="btn btn-primary" type="submit" value="Suspend"><img height="16" width="16" title="Suspend" src='http://hive.csis.ul.ie/4065/group05/images/Maybe.png'/>Suspend (1 Month)</button>
                    <button name="btnAction" class="btn btn-dark" type="submit" value="Bar"><img height="16" width="16" title="Bar" src='http://hive.csis.ul.ie/4065/group05/images/Goodbye.png'/>Bar</button>
                </div>
                <!-- 
                Suspended
                --------
                -->            
                <div class="col-xs-12 col-sm-12 col-lg-12" style="border-style:solid; border-color: silver;background-color:white; opacity: 1;">
                    <h3>Suspended</h3>
                    <?php
                    $sql = "SELECT * FROM user_profile where user_status_id in (select id from status_master where status_description = 'Suspended') ";
                    //echo $sql;
                    $result = execute_sql_query($db_connection, $sql);
                    if ($result == null) {
                        echo "<br><p>No matches found</p>";
                    } else {
                        while ($row = mysqli_fetch_array($result)) {
                            $pictureIndex++;
                            echo ("<li>");

                            echo "        <input type='radio' class='hideinput' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['id'] . "'/>";
                            echo "        <label for='radio" . $pictureIndex . "'>";
                            echo "        <label >" . $row['first_name'] . " " . $row['surname'] . "</label>";
                            echo "<br>";
                            if (strlen($row['picture']) > 0)
                                echo "<img class='rounded-circle selectimg' height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["picture"]) . "'/>";
                            else
                                echo ("<img class='rounded-circle selectimg' height='100' width='100' src='http://hive.csis.ul.ie/4065/group05/images/camera-photo-7.png'/><i></i>");
                            echo "</label>";

                            echo "    </li>";
                            echo "</ul>";
                        }
                    }
                    ?>
                    <p><b>Click on Photograph and do one of the following:</b></p>
                    <button name="btnAction" class="btn btn-info" type="submit" value="View"><img height="16" width="16" title="View" src='http://hive.csis.ul.ie/4065/group05/images/View.png'/>View</button>
                    <button name="btnAction" class="btn btn-secondary" type="submit" value="RemoveSuspension"><img height="16" width="16" title="RemoveSuspension" src='http://hive.csis.ul.ie/4065/group05/images/undo.png'/>Revoke Suspension</button>
                    <button name="btnAction" class="btn btn-dark" type="submit" value="Bar"><img height="16" width="16" title="Bar" src='http://hive.csis.ul.ie/4065/group05/images/Goodbye.png'/>Bar</button>
                </div>
                <!-- 
                Barred
                --------
                -->                
                <div class="col-xs-12 col-sm-12 col-lg-12" style="border-style:solid; border-color: silver;background-color:white; opacity: 1;">
                    <h3>Barred</h3>
                    <?php
                    $sql = "SELECT * FROM user_profile where user_status_id in (select id from status_master where status_description = 'Barred') ";
                    //echo $sql;
                    $result = execute_sql_query($db_connection, $sql);
                    if ($result == null) {
                        echo "<br><p>No matches found</p>";
                    } else {
                        while ($row = mysqli_fetch_array($result)) {
                            $pictureIndex++;
                            echo ("<li>");

                            echo "        <input type='radio' class='hideinput' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['id'] . "'/>";
                            echo "        <label for='radio" . $pictureIndex . "'>";
                            echo "        <label >" . $row['first_name'] . " " . $row['surname'] . "</label>";
                            echo "<br>";
                            if (strlen($row['picture']) > 0)
                                echo "<img class='rounded-circle selectimg' height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["picture"]) . "'/>";
                            else
                                echo ("<img class='rounded-circle selectimg' height='100' width='100' src='http://hive.csis.ul.ie/4065/group05/images/camera-photo-7.png'/><i></i>");
                            echo "</label>";

                            echo "    </li>";
                            echo "</ul>";
                        }
                    }
                    ?>
                    <p><b>Click on Photograph and do one of the following:</b></p>
                    <button name="btnAction" class="btn btn-info" type="submit" value="View"><img height="16" width="16" title="View" src='http://hive.csis.ul.ie/4065/group05/images/View.png'/>View</button>
                    <button name="btnAction" class="btn btn-secondary" type="submit" value="un-bar"><img height="16" width="16" title="un-bar" src='http://hive.csis.ul.ie/4065/group05/images/undo.png'/>Revoke Bar</button>
                </div>
            </fieldset>
        </form>
    </body>
</html>
