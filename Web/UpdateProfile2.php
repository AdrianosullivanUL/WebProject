<?php
require_once 'database_config.php';
include 'group05_library.php';
session_start();
$user_id = $_SESSION['user_id'];
$matching_user_id = $_SESSION['matching_user_id'];
//echo "session user " . $user_id;

$myBio = "";
$picture = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "I am a post";
// check the button selected (these are at the end of this form
    echo "EditPRofile call";
    if ($_POST['btnAction'] == "Save") { //save the detils
        // Get the form inputs
        // -------------------
        $myBio = trim($_POST['myBioInput']);

        // Get the uploaded picture
        if (isset($_POST['pictureInput'])) {
            $imgData = base64_encode(file_get_contents($img_file));
            $picture = 'data: ' . mime_content_type($img_file) . ';base64,' . $imgData;
        } else
            $picture = "";

        // Validate inputs 
        // ---------------
        $valid = true;
        if (strlen($myBio) == 0) {
            $message = "Please provide a short bio for your profile";
            $valid = false;
        }

        // Do Update
        // ---------
        if ($valid == true) {
            if ($picture = "") {
                $sql = "UPDATE user_profile SET my_bio ='" . $myBio . "' "
                        . " where id = " . $user_id;
            } else {
                $sql = "UPDATE user_profile SET picture='" . $picture . "', my_bio ='" . $myBio . "' "
                        . " where id = " . $user_id;
            }
            //echo $sql;
            $result = execute_sql_update($db_connection, $sql);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['matching_user_id'] = $matching_user_id;
            //header("Location: MeetingSpace.php");
        }
        // update data in  database  ds
//Ds EXIT
        exit();
    }
    if ($_POST['btnAction'] == "Cancel") { // Call Edit Profile
        echo "Cancel pressed";
        header("Location: MeetingSpace.php");
        exit();
    }
}
$myBio = "";
$picture = "";
$first_name = "";
$surname = "";
$sql = "SELECT first_name, surname, my_bio, picture from user_profile where id =" . $user_id . ";";
echo $sql;
if ($result = mysqli_query($db_connection, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            echo "found user";
            $myBio = $row['my_bio'];
            $picture = base64_encode($row["picture"]) ;
            $first_name = $row['first_name'];
            $surname = $row['surname'];
        }
    } else {
        echo "found user";
        $message = "Cannot find user profile";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Personal Details2</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
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





            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
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
                            <textarea rows="15" cols="50" class="form-control" name="myBioInput" value="<?php echo $myBio; ?>"></textarea>
                        </div>
                    </div>

                    <!--Lets try and do hobbies-->

                    <div class="col-sm-3 container border border-primary rounded bg-light text-dark ">

                        <div class="form-group">
                            <h3>Hobbies</h3>

                        </div>
                        <?php
                        // Get user Interests
                        $sql = "  SELECT  ud1.id, ud1.description , ui1.user_id "
                                . " FROM interests ud1 "
                                . " left join user_interests ui1 on ui1.interest_id = ud1.id "
                                . " where ui1.user_id is null or ui1.user_id=" . $user_id . "; ";

                        //echo ("sql" . $sql);
                        $result = execute_sql_query($db_connection, $sql);
                        if ($result == null) {
                            $message = "ERROR: No interests found " . $user_id;
                        } else {
                            while ($row = mysqli_fetch_array($result)) {
                                if ($row['user_id'] != null)
                                    $checked = "checked";
                                else
                                    $checked = "";
                                echo "<div class='checkbox'>";
                                echo "    <p align='middle>'";
                                echo "        <label><input type='checkbox' " . $checked . "  value=''>" . $row['description'] . "</label>";
                                echo "</div>";
                            }
                        }
                        ?>

                        <!------Include the above in your HEAD tag ---------->


                        <p align = "right">
                            <button class="btn btn-primary" name="btnAction" type="submit" value="Save">Save</button>
                        </p>

                    </div>
                </div>

            </form>

        </div>
    </body>

</html>