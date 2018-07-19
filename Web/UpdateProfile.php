    <?php
session_start();
// redirect to the logon screen if the user is not logged in
if ($_SESSION['user_logged_in'] == 0) {
    header("Location: Logon.php");
}
// Get a database connection
require_once 'database_config.php';

// Get the standard session parameters
$user_id = $_SESSION['user_id'];
$matching_user_id = $_SESSION['matching_user_id'];
//echo "session user " . $user_id;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // check the button selected (these are at the end of this form
    if ($_POST['btnAction'] == "Next") { // Call Edit Profile
        // Validate user inputs
        // --------------------
        $valid = true;
        $message = "";

        // Get the form inputs
        $firstname = $_POST['firstnameInput'];
        $surname = $_POST['surnameInput'];
        $DOB = $_POST['dateOfBirthInput'];
        $gender = $_POST['genderInput'];
        $preferredGender = $_POST['preferredGenderInput'];
        $ageSelection = $_POST['seekingAgeSelectionName'];
        $travelDistance = $_POST['travelDistanceSelection'];
        $relationshipType = $_POST['relationshipType'];

        if (strlen($firstname) == 0) { // validate first name
            $valid = false;
            $message = "First Name must be populated";
        }

        // are all inputs valid?
        if ($valid == true) {

            // Update database
            // ---------------
            $sql = "update user_profile set first_name = '" . $firstname . " "
                    // need to add other columns here
                    . "where id = " . $user_id . ";";

            // open User profile 2 page
            // ------------------------
            $_SESSION['user_id'] = $user_id;
            $_SESSION['matching_user_id'] = $matching_user_id;
            header("Location: UpdateProfile2.php");
            exit();
        }
    }
    if ($_POST['btnAction'] == "Cancel") { // cancel the update
        echo "Cancel pressed";
        header("Location: meetingspace.php");
        exit();
    }
} else {
    // prepare the page variables for presentation
    $message = "";
    $email = "";
    $firstname = "";
    $surname = "";
    $gender_name = "";
    $preferred_gender_name = "";
    $dob = "";
    $relationshipTypeLove = true;
    $relationshipTypeCasual = false;
    $relationshipTypeFriendship = false;
    $relationshipTypeRelationship = false;
    $sql = "SELECT up1.*, g1.gender_name, g2.gender_name as preferred_gender_name FROM user_profile up1 join gender g1 on g1.id = up1.gender_id join gender g2 on g2.id = up1.gender_preference_id where up1.id =" . $user_id . ";";
    if ($result = mysqli_query($db_connection, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $surname = $row['surname'];
                $email = $row['email'];
                $firstname = $row['first_name'];
                $gender_name = $row['gender_name'];
                $dob = $row['date_of_birth'];
                $preferred_gender_name = $row['preferred_gender_name'];
                $relationshipTypeId = $row['relationship_type_id'];
                
                  if ($relationshipTypeId == 1) {
                    $relationshipTypeLove = true;
                }
                if ($relationshipTypeId == 2) {
                    $relationshipTypeCasual = true;
                }
                if ($relationshipTypeId == 3) {
                    $relationshipTypeFriendship = true;
                }
                if ($relationshipTypeId == 4) {
                    $relationshipTypeRelationship = true;
                }
            }
        } else {
            $message = "Cannot find user profile";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Personal Details</title>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
            $(function () {
                $("#slider-range").slider({
                    echo "first part of slider"
                            range: true,
                    min: 18,
                    max: 120,
                    values: [18, 65],
                    slide: function (event, ui) {
                        $("#age").val("$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ]);
                    }
                });
                $("#age").val($("#slider-range").slider("values", 0) +
                        " - " + $("#slider-range").slider("values", 1));
            });
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

    </head>
    <body>
        <div class="container">

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="container border border-primary rounded bg-light text-dark col-sm-6">
                    <h1>Personal Details</h1>
                </div>
                </br>
                <div class="container border border-primary rounded bg-light text-dark col-sm-6">
                    <div class="form-group">
                        <label for="emailInput">Email</label>
                        <input type="text" class="form-control" name="emailInput" value=" <?php echo $email; ?> ">

                    </div>
                    <div class="form-group">
                        <label for="firstnameInput">First Name</label>
                        <input type="text" class="form-control" name="firstnameInput" value="<?php echo $firstname; ?>">

                    </div>                    
                    <div class="form-group">
                        <label for="surnameInput">Surname</label>
                        <input type="text" class="form-control" name="surnameInput" value=" <?php echo $surname; ?> ">

                    </div>
                    <div class="form-group">
                        <label for="dateOfBirthInput">Date of Birth</label>
                        <input type="text" class="form-control" name="dateOfBirthInput" value=" <?php echo $dob; ?> ">
                    </div>
                    <div class="form-group">
                        <label for="genderInput">Gender</label>
                        <input type="text" class="form-control" name="genderInput" value=" <?php echo $gender_name; ?> ">
                    </div>
                    <div class="form-group">
                        <label for="genderInput">Preferred Gender</label>
                        <input type="text" class="form-control" name="preferredGenderInput" value=" <?php echo $preferred_gender_name; ?> ">
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="seekingAgeSelection">Seeking Age Profile</label>
                            <input type="range" min="1" max="100" value="50" class="slider" name="seekingAgeSelectionName">
                        </div>

                        <div class="form-group">
                            <label for="travelDistanceSelection">Distance I will travel</label>
                            <input type="range" min="1" max="100" value="50" class="slider" name="travelDistanceSelection">
                        </div>
                        <label for="relationshipType">Relationship Type</label>
                        <br>
                        <input type="radio" value="Love" <?php if($relationshipTypeLove == true) echo 'checked'; ?> name="relationshipType">Love</input>&nbsp;
                        <input type="radio" value="Casual" <?php  if($relationshipTypeCasual == true) echo 'checked'; ?> name="relationshipType">Casual</input>&nbsp;
                        <input type="radio" value="Friendship" <?php  if($relationshipTypeFriendship == true) echo 'checked'; ?> name="relationshipType">Friendship</input>&nbsp;
                        <input type="radio" value="Relationship" <?php  if($relationshipTypeRelationship == true) echo 'checked'; ?> name="relationshipType">Relationship</input>
                        <b/>
                        <p > <?php echo $message ?></p>
                    </div>

                    <p align="middle">
                        <button class="btn btn-primary" name="btnAction" type="submit" value="Next">Next</button>
                        <button class="btn btn-warning" name="btnAction" type="submit" value="Cancel">Cancel</button>
                    </p>
                </div>

            </form>

        </div>
    </body>

</html>