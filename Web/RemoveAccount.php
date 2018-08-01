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

$message = '';
// Check and see if a post has been requested (this does not happen when screen initially opens
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // check the button selected (these are at the end of this form
    if ($_POST['btnAction'] == "Cancel") { // Process cancel request
        header("Location: MeetingSpace.php"); /* Redirect browser */
        exit();
    }
    if ($_POST['btnAction'] == "Return") { // After delete, allow user to return to index page
        header("Location: index.php"); /* Redirect browser */
        exit();
    }
    // Process the removal of the account
    if ($_POST['btnAction'] == "Remove") {
        if ($user_id == 0) {
            $user_id = 0;
            $message = "User ID not populated, cannot delete this account";
        } else {
            // Remove Communications entries
            $sql = "delete  from user_communication where from_user_id =" . $user_id . " or to_user_id = " . $user_id . ";";
            $result = execute_sql_update($db_connection, $sql);

            // Remove User Interests
            $sql = "delete from user_interests where user_id =" . $user_id . ";";
            $result = execute_sql_update($db_connection, $sql);

            // Remove User Interests
            $sql = "delete from match_table where match_user_id_1 =" . $user_id . " or match_user_id_2 = " . $user_id . ";";
            $result = execute_sql_update($db_connection, $sql);
            $sql = "delete from user_profile where id =" . $user_id . ";";
            $result = execute_sql_update($db_connection, $sql);

            // Tell the user that the profile has been deleted
            $message = "User Profile deleted";

            // only show return button
            $account_removed = true;
        }
    }
} else {
    // First time screen is loaded so hide the return button and present a message

    $account_removed = false;
    if ($user_id == 0) {
        $message = "User ID not populated, cannot delete this account";
    } else {
        $message = "<p>Please note, if you remove your account then all information related to you and your account will be removed and cannot be recovered."
                . "This includes your profile, images, communications history and matches."
                . "<br><br>If you are happy with this, press the Remove button below. "
                . "If you would like to keep your account, press Cancel.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang = "en">
    <head>
        <title>remove account</title>
        <meta charset = "utf-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1">
        <link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity = "sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin = "anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  
        <link rel="stylesheet" href="StyleSheet.css">
    </head>
    <body>
        <?php
        require_once 'database_config.php';
        ?>
        <div class="topnav">
            <a class="active">REMOVE ACCOUNT</a>
        </div>        
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3" >
                        <form method="post" name="challenge"  class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" >
                            <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .35em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .8;">

                                <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Account Details</legend>

                                <div class="form-group"></div>
                                <div class="col-sm-12">
                                    <?php
                                    $sql = "SELECT * FROM user_profile where id =" . $user_id . ";";
                                    //   echo $sql;
                                    if ($result = mysqli_query($db_connection, $sql)) {
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_array($result)) {
                                                if ($row['id'] == $user_id) {
                                                    echo("<h3>" . $row['first_name'] . " " . $row['surname'] . "</h3>");
                                                    echo("<p>(" . $row['email'] . ")</p>");
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </div>                    

                                <br>
                                <?php
                                if (strlen($message) > 0) {
                                    echo "<div class='alert alert-danger'>";
                                    echo "<p>" . $message . "</p>";
                                    echo "</div>";
                                }
                                ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?php
                                        if ($account_removed == true) {
                                            echo '<button  class="btn btn-danger" name="btnAction" type="submit" value="Return">Return</button>';
                                        } else {
                                            echo '<button  class="btn btn-danger" name="btnAction" type="submit" value="Remove">Remove Account</button>';
                                            echo "&nbsp;";
                                            echo '<button class="btn btn-secondary" name="btnAction" type="submit" value="Cancel">Cancel</button>';
                                        }
                                        ?>
                                        <br>
                                    </div>
                                    <br>
                                </div>
                            </fieldset> 
                        </form>
                    </div>
                </div>
            </div> 
        </form>
    </body>
</html> 