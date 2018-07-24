<?php
session_start();
// redirect to the logon screen if the user is not logged in
if ($_SESSION['user_logged_in'] == 0) {
    header("Location: Logon.php");
}

require_once 'database_config.php';
include 'group05_library.php';


$user_id = $_SESSION['user_id'];
$matching_user_id = $_SESSION['matching_user_id'];
//echo "session user " . $user_id;
echo " here";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
echo " here1";    
        $_SESSION['user_id'] = $user_id;
        if (isset($_POST['selected_user']))
            $matchId = $_POST['selected_user'];
        else
            $matchId = 0;
        
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
        <link rel="stylesheet" href="StyleSheet.css">
    </head>
    <body>

        <div class="container">
            <h1>Admin Screen</h1>
        </div>
 <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-group" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >        

        <div class="col-xs-12 col-sm-12 col-lg-4" style="border-style:solid; border-color: silver;background-color:white; opacity: 1;">
                                        <h3>Reported</h3>
                                        <?php
                                        $pictureIndex = 0;
                                        $sql = "SELECT * FROM matches_view where  "
                                                . " (user_profile_1_match_status = 'Report' or user_profile_2_match_status = 'Report')";
             //echo $sql;
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
                                                        echo "<img class='rounded-circle selectimg' height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_2_picture"]) . "'/>";
                                                    else
                                                        echo ("<img class='rounded-circle selectimg' height='100' width='100' src='../images/camera-photo-7.png'/><i></i>'");
                                                    echo "</label>";
                                                } else {
                                                    echo "        <input type='radio' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['match_id'] . "'/>";
                                                    echo "        <label for='radio" . $pictureIndex . "'>";
                                                    echo "        <label >" . $row['user_profile_1_first_name'] . " " . $row['user_profile_1_surname'] . "</label>";
                                                    echo "<br>";
                                                    if (strlen($row['user_profile_1_picture']) > 0)
                                                        echo "<img class='rounded-circle selectimg'  height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["user_profile_1_picture"]) . "'/>";
                                                    else
                                                        echo ("<img class='selectimg' height='100' width='100' src='../images/camera-photo-7.png'/><i></i>'");
                                                    echo "</label>";
                                                }
                                                echo "    </li>";
                                                echo "</ul>";
                                            }
                                        }
                                        ?>
                                        <p><b>Click on Photograph and do one of the following:</b></p>
                                        <button name="btnAction" class="btn btn-info" type="submit" value="View"><img height="16" width="16" title="View" src='/images/View.png'/>View</button>
                                        <button name="btnAction" class="btn btn-primary" type="submit" value="Suspend"><img height="16" width="16" title="Maybe" src='/images/Maybe.png'/>Suspend (1 Month)</button>
                                        <button name="btnAction" class="btn btn-warning" type="submit" value="Terminate"><img height="16" width="16" title="Goodbye" src='/images/Goodbye.png'/>Terminate</button>
                                      
                                    </div>
 </form> 
 </body>
</html> 