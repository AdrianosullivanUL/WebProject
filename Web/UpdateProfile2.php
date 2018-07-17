<?php
require_once 'database_config.php';

session_start();
$user_id = $_SESSION['user_id'];
$matching_user_id = $_SESSION['matching_user_id'];
//echo "session user " . $user_id;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "I am a post";
    // check the button selected (these are at the end of this form
    echo "EditPRofile call";
    if ($_POST['btnAction'] == "Save") { //save the detils
        echo "Saved";
        $_SESSION['user_id'] = $user_id;
        $_SESSION['matching_user_id'] = $matching_user_id;
        header("Location: UpdateProfile2.php");
        exit();
    }
    if ($_POST['btnAction'] == "Cancel") { // Call Edit Profile
        echo "Cancel pressed";
        header("Location: index.php");
        exit();
    } else {
        echo "I am called from another form";
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
        <div class="container">

            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="container border border-primary rounded bg-light text-dark col-sm-6">
                    <h1>Personal Details Page 2</h1>
                    <div class="topnav">
                    </div>
                    </br>
                    <div class="container border border-primary rounded bg-light text-dark col-sm-6">
                        <?php
                        $picture = "";
                     //   $sql = "SELECT up1.*, g1.gender_name, g2.gender_name as preferred_gender_name FROM user_profile up1 join gender g1 on g1.id = up1.gender_id join gender g2 on g2.id = up1.gender_preference_id where up1.id =" . $user_id . ";";
                     // $sql = "SELECT up1.*, i1.interest_id FROM user_profile up1 join user_interests i1 where up1.id =" . $user_id . ";";
                       $sql = " SELECT up1.*, ui1.interest_id, ud1.description FROM user_profile up1 join user_interests ui1 join interests ud1 where up1.id;=" . $user_id . " AND ui1.interest_id = ud1;";
                        echo ("sql" . $sql);


                        if ($result = mysqli_query($db_connection, $sql)) {
                            echo "Result", mysqli_num_rows($result);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    if ($row['id'] == $user_id) {
                                        echo('<div class="form-group">');
                                        if (strlen($row['picture']) > 0) {
                                            echo '<img class="portrait rounded-circle" src="data:image/jpeg;base64,' . base64_encode($row['picture']) . '"/><i></i>';
                                        } else {
                                            echo ("<img class='portrait rounded-circle' src='camera-photo-7.png'/><i></i>'");
                                        }
                                        echo ("<figcaption>" . $row['first_name'] . " " . $row['surname'] . "</figcaption>");
                                        echo ("");


                                        echo('</div>');
                                    }
                                }
                            }
                        }
                        ?>

                        </br>
                        </br>
                        </br>
                        <div class="form-group">
                            <label class="header">Profile Photo:</label>
                            <input id="image" type="file" name="profile_photo" placeholder="Photo" required="" capture>
                        </div>


                    </div>           

                    </br>
                    </br>
                    </br>



                </div>
                <!--Lets try and do hobbies-->

                <div class="container border border-primary rounded bg-light text-dark col-sm-6">

                    <div class="form-group">
                        <p align="middle">
                            <label for="hobbies">Hobbies</label>

                    </div>

                    <div class="checkbox">
                        <p align="middle">
                            <label><input type="checkbox" value="">Sport</label>
                    </div>
                    <div class="checkbox">
                        <p align="middle">
                            <label><input type="checkbox" value="">Music</label>
                    </div>
                    <div class="checkbox">
                        <p align="middle">
                            <label><input type="checkbox" value="">Outdoors</label>
                    </div>
                    <div class="checkbox">
                        <p align="middle">
                            <label><input type="checkbox" value="">Reading</label>
                    </div>

                    <!------ Include the above in your HEAD tag ---------->

                    <
                    <p align="right">

                        <input class="btn btn-default" type="submit" value="Save">


                </div>

            </form>

        </div>
    </body>

</html>