<?php
session_start();
// redirect to the logon screen if the user is not logged in
if ($_SESSION['user_logged_in'] == 0) {
    header("Location: Logon.php");
}
// Get a database connection
require_once 'database_config.php';
include 'group05_library.php';

// Get the standard session parameters
$user_id = $_SESSION['user_id'];
$matching_user_id = $_SESSION['matching_user_id'];
//echo "session user " . $user_id;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // check the button selected (these are at the end of this form
    if ($_POST['btnAction'] == "Next") { // Call Edit Profile
        // Get the form inputs
        // -------------------
        $firstname = trim($_POST['firstnameInput']);
        $surname = trim($_POST['surnameInput']);
        $dob = $_POST['dateOfBirthInput'];
        $gender = $_POST['genderInput'];
        $city = $_POST['cityInput'];
        if (isset($_POST['preferredGenderInput']))
            $preferredGender = $_POST['preferredGenderInput'];
        else
            $preferredGender = "";
        $ageSelectionFrom = $_POST['seekingAgeFromSelection'];
        $ageSelectionTo = $_POST['seekingAgeToSelection'];
        $travelDistance = $_POST['travelDistanceSelection'];
        if (isset($_POST['relationshipTypeInput']))
            $relationshipType = $_POST['relationshipTypeInput'];
        else
            $relationshipType = '';
        $email = trim($_POST['emailInput']);

        // Validate user inputs
        // --------------------
        $valid = true;
        $message = "";
        if (strlen($firstname) == 0) { // validate first name
            $valid = false;
            $message = "First Name must be populated";
        }
        if (strlen($firstname) > 50) { // validate first name
            $valid = false;
            $message = "First Name Cannot be longer than 50 characters";
        }        

        // are all inputs valid?
        if ($valid == true) {

            // Update database
            // ---------------
            $sql = "update user_profile set first_name = '" . $firstname . "', "
                    . " surname = '" . $surname . "',"
                    . " date_of_birth = '" . $dob . "',"
                    . " gender_id  = (select id from gender where gender_name ='" . $gender . "'),"
                    . " gender_preference_id  = (select id from gender where gender_name ='" . $preferredGender . "'),"
                    . " from_age = '" . $ageSelectionFrom . "',"
                    . " to_age = '" . $ageSelectionTo . "',"
                    . " travel_distance = '" . $travelDistance . "',"
                    . " relationship_type_id  = (select id from relationship_type where relationship_type ='" . $relationshipType . "'),"
                    . " email = '" . $email . "', "
                    . " city_id = (select id from city where city = '" . $city . "')"
                    . " where id = " . $user_id . ";";
            //echo $sql;
            $result = execute_sql_update($db_connection, $sql);

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
    $city = "";
    $dob = "";

    $sql = "SELECT up.*, DATE_FORMAT(up.date_of_birth,'%d/%m/%Y') as formatted_dob, g1.gender_name, g2.gender_name as preferred_gender_name, rt.relationship_type, c.city "
            . " FROM user_profile up "
            . " left join gender g1 on g1.id = up.gender_id "
            . " left join gender g2 on g2.id = up.gender_preference_id "
            . " left join relationship_type rt on rt.id = up.relationship_type_id "
            . " left join city c on c.id = up.city_id "
            . " where up.id =" . $user_id . ";";
    //echo $sql;
    if ($result = mysqli_query($db_connection, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $surname = $row['surname'];
                $email = $row['email'];
                $firstname = $row['first_name'];
                $gender = $row['gender_name'];
                $preferredGender = $row['preferred_gender_name'];
                $dob = $row['date_of_birth'];
                $relationshipType = $row['relationship_type'];
                $ageSelectionFrom = $row['From_age'];
                $ageSelectionTo = $row['to_age'];
                $travelDistance = $row['Travel_distance'];
                $city = $row['city'];
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
                    max: 65,
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
                        <input type="text" class="form-control" name="emailInput" value="<?php echo $email; ?>">

                    </div>
                    <div class="form-group">
                        <label for="firstnameInput">First Name</label>
                        <input type="text" class="form-control" name="firstnameInput" value="<?php echo $firstname; ?>">

                    </div>                    
                    <div class="form-group">
                        <label for="surnameInput">Surname</label>
                        <input type="text" class="form-control" name="surnameInput" value="<?php echo trim($surname); ?>">

                    </div>
                    <div class="form-group">
                        <label for="dateOfBirthInput">Date of Birth</label>
                        <input type="date" class="form-control" name="dateOfBirthInput" value="<?php echo $dob; ?>" min="1900-01-01" max="<?php echo (new \DateTime())->format('Y-m-d'); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="city Label">Nearest City/Town</label>
                        <select name="cityInput" class="selectpicker form-control"style=" font-size:15pt;height: 40px;">
                            <?php
                            $sql = "select city  from city";
                            if ($result = mysqli_query($db_connection, $sql)) {
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_array($result)) {
                                        if ($row['city'] == $city) {
                                            echo "<option selected value ='" . $row['city'] . "'>" . $row['city'] . "</option>";
                                        } else {
                                            echo "<option value ='" . $row['city'] . "'>" . $row['city'] . "</option>";
                                        }
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="genderLabel">Gender</label>
                        <select name="genderInput" class="selectpicker form-control"style=" font-size:15pt;height: 40px;">
                            <?php
                            $sql = "select gender_name  from gender";
                            if ($result = mysqli_query($db_connection, $sql)) {
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_array($result)) {
                                        if ($row['gender_name'] == $gender) {
                                            echo "<option selected value ='" . $row['gender_name'] . "'>" . $row['gender_name'] . "</option>";
                                        } else {
                                            echo "<option value ='" . $row['gender_name'] . "'>" . $row['gender_name'] . "</option>";
                                        }
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="genderInput">Preferred Gender</label>
                        <select name="preferredGenderInput" class="selectpicker form-control"style=" font-size:15pt;height: 40px;">
                            <?php
                            $sql = "select gender_name  from gender";
                            if ($result = mysqli_query($db_connection, $sql)) {
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_array($result)) {
                                        if ($row['gender_name'] == $preferredGender) {
                                            echo "<option selected value ='" . $row['gender_name'] . "'>" . $row['gender_name'] . "</option>";
                                        } else {
                                            echo "<option value ='" . $row['gender_name'] . "'>" . $row['gender_name'] . "</option>";
                                        }
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="seekingAgeSelection">Seeking Age Profile</label>
                            <!-- TODO add a single slider later -->
                    <!-- MM
                    <label for="seekingAgeSelection">From</label>
                            <input type="range" min="18" max="120" value="<?php echo $ageSelectionFrom; ?>" class="slider" data-show-value="true" name="seekingAgeFromSelection">
                            <label for="seekingAgeSelection">To</label>
                            <input type="range" min="18" max="120" value="<?php echo $ageSelectionTo; ?>" class="slider" data-show-value="true" name="seekingAgeToSelection">
                    -->
                            <label for="seekingAgeSelection">From</label>
                            <input type="range" min="18" max="65" value="<?php echo $ageSelectionFrom; ?>" class="slider" name="seekingAgeFromSelection">
                                            <label for="seekingAgeSelection">To</label>
                            <input type="range" min="18" max="120" value="35"
                                   value="<?php echo $ageSelectionTo; ?>" class="slider" data-show-value="true" name="seekingAgeToSelection">
                        </div>                       

                        <div class="form-group">
                            <label for="travelDistanceSelection">Distance I will travel</label>
                            <input type="range" min="0" max="500" value="<?php echo $travelDistance; ?>" class="slider" name="travelDistanceSelection">
                        </div>
                        <label for="relationshipType">Relationship Type</label>
                        <br>
                        <input type="radio" value="Love" <?php if ($relationshipType == "Love") echo 'checked'; ?> name="relationshipTypeInput">Love</input>&nbsp;
                        <input type="radio" value="Casual" <?php if ($relationshipType == "Casual") echo 'checked'; ?> name="relationshipTypeInput">Casual</input>&nbsp;
                        <input type="radio" value="Friendship" <?php if ($relationshipType == "Friendship") echo 'checked'; ?> name="relationshipTypeInput">Friendship</input>&nbsp;
                        <input type="radio" value="Relationship" <?php if ($relationshipType == "Relationship") echo 'checked'; ?> name="relationshipTypeInput">Relationship</input>
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