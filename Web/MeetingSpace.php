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
    if ($_POST['btnAction'] == "Like" || $_POST['btnAction'] == "Maybe" || $_POST['btnAction'] == "Report" || $_POST['btnAction'] == "View") { // Get Match view row for subsequent buttons
        $_SESSION['user_id'] = $user_id;
        if (isset($_POST['selected_user']))
            $matchId = $_POST['selected_user'];
        else
            $matchId = 0;
        $sql = 'select match_user_id_1, match_user_id_2 from match_table where id = ' . $matchId . ";";
        $result = execute_sql_query($db_connection, $sql);
        if ($result == null) {
            echo "ERROR: Cannot find match entry to update status with, id =" . $matchId;
        } else {
            while ($row = mysqli_fetch_array($result)) {
                if ($row['match_user_id_1'] == $user_id)
                    $_SESSION['matching_user_id'] = $row['match_user_id_2'];
                else
                    $_SESSION['matching_user_id'] = $row['match_user_id_1'];
                $matchId = $row['id'];
                if ($_POST['btnAction'] == "View") { // View Profile
                    header("Location: ViewMatchProfile.php");
                    exit();
                }
                if ($_POST['btnAction'] == "Like") { // Update Status
                    if ($row['match_user_id_1'] == $user_id)
                        $updateUserStatus = 1;
                    else
                        $updateUserStatus = 2;
                    update_match_status($matchId, 'Like', $updateUserStatus);
                }
                if ($_POST['btnAction'] == "Maybe") { // Update Status
                    update_match_status($_SESSION['matching_user_id'], 'Maybe');
                }
                if ($_POST['btnAction'] == "Report") { // Update Status
                    update_match_status($_SESSION['matching_user_id'], 'Report');
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
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  


        <style>
            body{color:#444;font:100%/1.4 sans-serif;}
            body {
                background-image:    url(backlit-bonding-casual-708392.jpg);
                background-size:     cover;                      /* <------ */
                background-repeat:   no-repeat;
                background-position: center center;              /* optional, center the image */
            }


            /* CUSTOM RADIO & CHECKBOXES
               http://stackoverflow.com/a/17541916/383904 */
            .rad,
            .ckb{
                cursor: pointer;
                user-select: none;
                -webkit-user-select: none;
                -webkit-touch-callout: none;
            }
            .rad > input,
            .ckb > input{ /* HIDE ORG RADIO & CHECKBOX */
                visibility: hidden;
                position: absolute;
            }
            /* RADIO & CHECKBOX STYLES */
            .rad > i,
            .ckb > i{     /* DEFAULT <i> STYLE */
                display: inline-block;
                vertical-align: middle;
                width:  16px;
                height: 16px;
                border-radius: 50%;
                transition: 0.2s;
                box-shadow: inset 0 0 0 8px #fff;
                border: 1px solid gray;
                background: gray;
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
        </style>
    </head>
    <body>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

            <div class="container-fluid">

                <div class="row">

                    <div  class="col-sm-6 container border border-primary rounded bg-light text-dark" >
                        <h1>Meeting Space</h1>
                    </div>
                    <div class="col-sm-2 container border border-primary rounded bg-light text-dark">
                        <br>
                        <?php
                        $sql = "SELECT first_name, surname FROM user_profile where id = " . $user_id . ";";
//echo $sql;
                        $result = execute_sql_query($db_connection, $sql);
                        if ($result == null) {
                            echo "ERROR: Cannot find profile for user id " . $user_id;
                        } else {
                            while ($row = mysqli_fetch_array($result)) {
                                echo '<h3>' . $row['first_name'] . '&nbsp' . $row['surname'] . '</h3>';
                            }
                        }
                        ?>
                        <button class="btn btn-primary" name="btnAction" type="submit" value="EditProfile"><img height="32" width="32"  title="Edit Profile" src='/images/Edit.png'/></button>
                        <button name="btnAction" class="btn btn-secondary" type="submit" value="MatchFinder"><img height="32" width="32"  title="Match Finder" src='/images/Find.png'/></button></button>                        
                        <button name="btnAction" class="btn btn-warning" type="submit" value="Logoff"><img height="32" width="32"  title="Logoff" src='/images/Logoff.png'/></button></button>
                        <button name="btnAction" class="btn btn-danger" type="submit" value="RemoveAccount"><img height="32" width="32"  title="Remove Account" src='/images/Delete.png'/></button></button>
                        <br>
                    </div>

                </div>
                <br>  
                <?php if (strlen($message) > 0) echo "<p><font color='red'>" . $message . "</font></p>" ?>
                <div class="row">
                    <div class="col-sm-11 container border border-primary rounded bg-light text-dark">
                        <h3>Chatting with</h3>

                        <?php
                        echo "<ul>";
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
                                echo ("<li>");
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
                                echo "    </li>";
                                echo "</ul>";
                            }
                        }
                        ?>
                        <div class="col-sm-12">
                            <p>Click on Photograph and do one of the following:</p>
                            <button name="btnAction" class="btn btn-success" type="submit" value="Chat"><img height="32" width="32"  title="Chat" src='/images/Chat.png'/></button>
                            <button name="btnAction" class="btn btn-info" type="submit" value="View"><img height="32" width="32"  title="View" src='/images/View.png'/></button>
                            <button name="btnAction" class="btn btn-warning" type="submit" value="Goodbye"><img height="32" width="42"  title="Goodbye"  src='/images/Goodbye.png'/></button>
                            <button name="btnAction" class="btn btn-danger" type="submit" value="Report"><img height="32" width="32"  title="Report"  src='/images/Report.png'/></button>

                        </div>

                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-7 container border border-primary rounded bg-light text-dark">
                        <h3>System Matches</h3>
                        <?php
                        echo "<ul>";
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
                                    echo "        <input type='radio' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                    echo "        <label for='radio" . $pictureIndex . "'>";
                                    echo "        <label >" . $row['user_profile_2_first_name'] . " " . $row['user_profile_2_surname'] . "</label>";
                                    echo "<br>";
                                    if (strlen($row['user_profile_2_picture']) > 0)
                                        echo "<img class='rounded-circle' height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_2_picture"]) . "'/>";
                                    else
                                        echo ("<img height='100' width='100' src='camera-photo-7.png'/><i></i>'");
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
                                    echo "</label>";
                                }
                                echo "    </li>";
                                echo "</ul>";
                            }
                        }
                        ?>
                        <div class="col-sm-12">
                            <p>Click on Photograph and do one of the following:</p>

                            <button name="btnAction" class="btn btn-success" type="submit" value="Like"><img height="32" width="32"  title="Like" src='/images/Like.png'/></button>
                            <button name="btnAction" class="btn btn-info" type="submit" value="View"><img height="32" width="32"  title="View" src='/images/View.png'/></button>
                            <button name="btnAction" class="btn btn-primary" type="submit" value="Maybe"><img height="32" width="32"  title="Maybe" src='/images/Maybe.png'/></button>
                            <button name="btnAction" class="btn btn-warning" type="submit" value="Goodbye"><img height="32" width="32"  title="Goodbye" src='/images/Goodbye.png'/></button>
                            <button name="btnAction" class="btn btn-danger" type="submit" value="Report"><img height="32" width="32"  title="Report" src='/images/Report.png'/></button>
                        </div>    
                    </div>

                    <div class="col-sm-3 container border border-primary rounded bg-light text-dark" ><h3>Interested in Me</h3>
                        <?php
                        $sql = "SELECT * FROM matches_view where system_generated_match = false and (match_user_id_1 =" . $user_id
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
        </div>
        <br>

    </form>        
</body>
</html> 