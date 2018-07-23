<?php
require_once 'database_config.php';
include 'group05_library.php';
session_start();
$user_id = $_SESSION['user_id'];
$matching_user_id = $_SESSION['matching_user_id'];
//echo "session user " . $user_id;
// Initialse variables
// -------------------
$myBio = "";
$picture = "";
$first_name = "";
$surname = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // check the button selected (these are at the end of this form
    if ($_POST['btnAction'] == "Save") { //save the detils
        // Get the form inputs
        // -------------------
        $myBio = trim($_POST['myBioInput']);

        // Get the uploaded picture
        if (isset($_FILES["fileToUpload"]["tmp_name"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                //echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
                $picture = addslashes(file_get_contents($_FILES['fileToUpload']['tmp_name']));
            } else {
                $message = "File is not an image";
                $valid = false;
                $uploadOk = 0;
            }
        }
        // Validate text inputs 
        // --------------------
        $valid = true;
        if (strlen($myBio) == 0) {
            $message = "Please provide a short bio for your profile";
            $valid = false;
        }

        // Do Update to User Profile
        // ---------
        if ($valid == true) {
            if ($picture == "") {
                $sql = "UPDATE user_profile SET my_bio = '" . $myBio . "' "
                        . " where id = " . $user_id;
            } else {

                $sql = "UPDATE user_profile SET picture = '" . $picture . "', my_bio = '" . $myBio . "' "
                        . " where id = " . $user_id;
            }
            //echo $sql;
            $result = execute_sql_update($db_connection, $sql);
        }
        // Remove all user Interests first
        $sql = "delete from user_interests where user_id = " . $user_id . ";";
        // echo $sql;
        $updateResult = execute_sql_update($db_connection, $sql);
       // mysql_free_result($result);
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
        //mysql_free_result($result);
        if ($valid == true) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['matching_user_id'] = $matching_user_id;
            header("Location: MeetingSpace.php");
            exit();
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
            $first_name = $row['first_name'];
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
        <meta charset="UTF-8">
        <title>Personal Details2</title>
        <meta name="viewport" content="width = device-width, initial-scale = 1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src = "https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity = "sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin = "anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js">
        </script>
        <style>
            body{color:#444;font:100%/1.4 sans-serif;}
            body {
                background-image:    url(backlit-bonding-casual-708392.jpg);
                background-size:     cover;                      /* <------ */
                background-repeat:   no-repeat;
                background-position: center center;              /* optional, center the image */
            }


        </style>
        <style>
            body {
                background-image:    url(images/backlit-bonding-casual-708392.jpg);
                background-size:     cover;                      /* <------ */
                background-repeat:   no-repeat;
                background-position: center center;              /* optional, center the image */
            }
            /* Add a black background color to the top navigation */
            .topnav {
                background-color: #333;
                overflow: hidden;
            }

            /* Style the links inside the navigation bar */
            .topnav a {
                float: left;
                color: #F0F8FF;
                text-align: center;
                padding: 14px 16px;
                text-decoration: none;
                font-size: 17px;
            }

            /* Change the color of links on hover */
            .topnav a:hover {
                background-color: #ddd;
                color: grey;
            }

            /* Add a color to the active/current link */
            .topnav a.active {
                background-color: #A9A9A9;
                color: white;
            }

            /* Right-aligned section inside the top navigation */
            .topnav-right {
                float: right;
            }
            iv.first {
                opacity: 0.1;
                filter: alpha(opacity=10); 
            }
        </style>
    </head>
    <body>
        <!--taken out 18.32 <div class="container">  es-->


        <div class="container-fluid">





            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                <? echo $message; ?>
                <div class="row">
                    <div class="col-sm-5 container border border-primary rounded bg-light text-dark ">
                        <h1>Personal Details Page 2</h1>
                        <div class="topnav">
                        </div>
                        </br>
                        <div class="container border border-primary rounded bg-light text-dark col-sm-6">
                            <div class="form-group">
<?php
if (strlen($picture) > 0) {
    //echo ("Current photo");
    echo '<img name = "pictureInput" class="portrait rounded-circle" src="data:image/jpeg;base64,' . $picture . '"/><i></i>';
} else {
    // echo ("No photo uploaded");
    echo ("<img class='portrait rounded-circle' src='camera-photo-7.png'/><i></i>'");
}
//        echo ("after photo shoot");
echo ("<figcaption>" . $first_name . " " . $first_name . "</figcaption>");
?>


                            </div>

                            </br>
                            <div class="form-group">
                                <label class="header">Profile Photo:</label>
                                <input type="file" name="fileToUpload" id="fileToUpload">
                            </div>
                        </div>           
                        </br>
                    </div>
                    <div class="col-sm-3 container border border-primary rounded bg-light text-dark ">

                        <div class="form-group">
                            <h3>Bio</h3>
                            <textarea rows="15" cols="50" class="form-control" name="myBioInput"><?php echo $myBio; ?></textarea>
                        </div>
                    </div>

                    <!--Lets try and do hobbies-->

                    <div class="col-sm-3 container border border-primary rounded bg-light text-dark ">

                        <div class="form-group">
                            <h3>Hobbies</h3>

                        </div>
<?php
// Get user Interests
// ------------------
$sql = "  SELECT  ud1.id, ud1.description , ui1.user_id "
        . " FROM interests ud1 "
        . " left join user_interests ui1 on ui1.interest_id = ud1.id "
        . " where ui1.user_id is null or ui1.user_id=" . $user_id . "; ";

//echo ("sql" . $sql);
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
                        <p align = "right">
                            <button class="btn btn-primary" name="btnAction" type="submit" value="Save">Save</button>
                        </p>

                    </div>
                </div>

            </form>

        </div>
    </body>

</html>