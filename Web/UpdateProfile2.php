<?php
session_start();
require_once 'database_config.php';
include 'group05_library.php';
$user_id = $_SESSION['user_id'];
$session_hash = $_SESSION['session_hash'];

if (validate_logon($db_connection, $user_id, $session_hash) == false) {
    // User is not correctly logged on, route to Logon screen
    Echo "Logon issue " . $session_hash;
    //  header("Location: Logon.php");
}

$user_id = $_SESSION['user_id'];
$matching_user_id = $_SESSION['matching_user_id'];
//echo "session user " . $user_id;
// Initialse variables
// -------------------
$myBio = "";
$picture = "";
$first_name = "";
$surname = "";
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // check the button selected (these are at the end of this form
    if ($_POST['btnAction'] == "Save") { //save the detils
        // Get the form inputs
        // -------------------
        $myBio = trim($_POST['myBioInput']);
        $valid = true;
        // Get the uploaded picture
        if (isset($_FILES["fileToUpload"]["tmp_name"])) {
            $sizeInBytes = filesize($_FILES["fileToUpload"]["tmp_name"]);
            $maxImageSize = 204800;
            if (strlen($_FILES["fileToUpload"]["tmp_name"]) > 0) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                $imageType = $check['mime'];
                if ($sizeInBytes > $maxImageSize) {
                    $message = "File must be smaller than 200kb";
                    $valid = false;
                } else {
                    $picture = addslashes(file_get_contents($_FILES['fileToUpload']['tmp_name']));
                    $sql = "UPDATE user_profile SET picture = '" . $picture . "', my_bio = '" . $myBio . "' "
                            . " where id = " . $user_id;
                    $result = execute_sql_update($db_connection, $sql);
                }
            }
        }
        // Validate text inputs 
        // --------------------
        if (strlen($myBio) == 0) {
            $message = "Please provide a short bio for your profile";
            $valid = false;
        }

        // Do Update to User Profile
        // ---------
        if ($valid == true) {
            try {
                $stmt = $db_connection->prepare("UPDATE user_profile SET my_bio = ? where id = ?;");
                $stmt->bind_param("si", $myBio, $user_id);
                $stmt->execute();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        // Remove all user Interests first
        $sql = "delete from user_interests where user_id = " . $user_id . ";";
        // echo $sql;
        $updateResult = execute_sql_update($db_connection, $sql);
        // mysql_free_result($result);
        if (isset($_POST['check_list'])) {
            foreach ($_POST['check_list'] as $key => $value) {
                $sql = "select count(1) cnt from user_interests where user_id = " . $user_id . " and interest_id = " . $key . ";";
                //echo $sql . "<br>";
                $result = execute_sql_query($db_connection, $sql);
                if ($result == null) {
                    $message = "ERROR: problem updating User Interests, user_id = " . $user_id . " and interest_id = " . $key;
                    $valid = false;
                } else {
                    while ($row = mysqli_fetch_array($result)) {
                        if ($row['cnt'] == 0) {
                            $sql = "insert into user_interests (user_id, interest_id) values (" . $user_id . ", " . $key . ");";
                            //echo $sql . "<br>";
                            $updateResult = execute_sql_update($db_connection, $sql);
                        }
                        // No action required if count is greater than zero, already set
                    }
                }
            }
        }
        if ($valid == true) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['matching_user_id'] = $matching_user_id;
            //   header("Location: MeetingSpace.php");
            //  exit();
        }
    }
    if ($_POST['btnAction'] == "Cancel") { // Call Edit Profile
        echo "Cancel pressed";
        header("Location: MeetingSpace.php");
        exit();
    }
}

// Load the user profile for this user
// -----------------------------------
$sql = "SELECT first_name, surname, my_bio, picture from user_profile where id = " . $user_id . ";";

//echo $sql;
if ($result = mysqli_query($db_connection, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $myBio = $row['my_bio'];
            $picture = base64_encode($row["picture"]);
            $firstname = $row['first_name'];
            $surname = $row['surname'];
        }
    } else {
        $message = "Cannot find user profile";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
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
        <link rel="stylesheet" href="StyleSheet.css">

    </head>
    <body>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-group" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >        
            <div class="topnav">
                <a class="active">UPDATE PROFILE 2</a>
                <a href="MeetingSpace.php" title="Meeting Space">
                    <?php echo $firstname . " " . $surname ?>
                </a>
                <div class="topnav-right">
                    <a href="RemoveAccount.php" title="Remove your User Profile"><img height="16" width="16"  src='http://hive.csis.ul.ie/4065/group05/images/Delete.png'/>Delete Profile</a>
                    <a href="Logout.php" title="Log out of the system"><img height="16" width="16"  src='http://hive.csis.ul.ie/4065/group05/images/Logoff.png'/>Logoff</a>
                </div>
            </div>
            <div class="row">
                <div class ="container">
                    <div class="col-md-10 col-md-offset-1" >
                        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                            <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .75em .75em .75em;margin:0 2px;border: 2px solid silver;margin-bottom: 0em;background-color:lavender; opacity: .9;">
                                <legend style="border-bottom: none;width: inherit;;padding:inherit;" class="legend">Profile Update Part 2</legend>

                                <div class="form-group">
                                    <div class="col-sm-8 col-md-8 col-lg-12 col-xs-10 mobileLabel" ></div>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4 col-xs-10 mobileLabel">
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-horizontal" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >
                                        <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .75em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:whitesmoke; opacity: .9;">
                                            <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Photo</legend>
                                            <div class="form-group">
                                                <?php
                                                if (strlen($picture) > 0) {
                                                    //echo ("Current photo");
                                                    echo '<img name = "pictureInput" class="portrait rounded-circle" height="200" width="200" src="data:image/jpeg;base64,' . $picture . '"/><i></i>';
                                                } else {
                                                    // echo ("No photo uploaded");
                                                    echo ("<img class='portrait rounded-circle' height='200' width='200' src='http://hive.csis.ul.ie/4065/group05/images/camera-photo-7.png'/><i></i>");
                                                }
//        echo ("after photo shoot");
                                                echo ("<figcaption>" . $first_name . " " . $first_name . "</figcaption>");
                                                ?>

                                            </div>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>

                                            <div class="form-group">
                                <label class="header">Profile Photo:</label>
                                <input type="file" name="fileToUpload" id="fileToUpload">
                            </div>
                                        </fieldset>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" >
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-horizontal" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >
                                        <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .75em .75em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:whitesmoke; opacity: .9;">
                                            <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Profile Bio</legend>
                                            <div class="form-group">
                                                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-10 mobileLabel">
                                                    <textarea rows="18" cols="30" class="form-control" name="myBioInput"><?php echo $myBio; ?></textarea>
                                                    <br>
                                                    <br>
                                                </div>
                                            </div>

                                        </fieldset>
                                    </form>    
                                </div>
                                <!--// Get user Interests   -->

                                <div class="col-sm-3 col-md-3 col-lg-3 col-xs-10 mobileLabel">
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-horizontal" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >
                                        <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .75em .75em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:whitesmoke; opacity: .9;">
                                            <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Interests</legend>
                                            <?php
                                            $sql = "  SELECT ud1.id, ud1.description , ui1.user_id FROM interests ud1 "
                                                    . " left join (select * from user_interests where user_id = " . $user_id . ") ui1 on ui1.interest_id = ud1.id ";

                                            $result = execute_sql_query($db_connection, $sql);
                                            if ($result == null) {
                                                $message = "ERROR: No interests found " . $user_id;
                                            } else {
                                                $checkBoxNumber = 0;
                                                while ($row = mysqli_fetch_array($result)) {
                                                    if ($row['user_id'] != null)
                                                        $isChecked = "checked";
                                                    else
                                                        $isChecked = "";
                                                    echo "<div class='checkbox'>";
                                                    echo "    <p align='middle>'";
                                                    echo "        <label><input type='checkbox' name='check_list[" . $row['id'] . "]' " . $isChecked . "  value=''>" . $row['description'] . "</label>";
                                                    echo "</div>";
                                                }
                                            }
                                            ?>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                        </fieldset>
                                    </form>   
                                </div>

                                <div class="col-sm-10 col-md-10 col-lg-12 col-xs-10" style = "text-align: right">
                                    <div class="form-group">
                                        <button class="btn btn-primary" name="btnAction" type="submit" value="Save">Save</button> 
                                        <button class="btn btn-warning" name="btnAction" type="submit" value="Cancel">Cancel</button>
                                    </div>
                                </div>
                            </fieldset>
                    </div>
                </div>
            </div>
    </body>

</html>