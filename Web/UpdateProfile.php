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
        if (isset($_POST['genderInput']))
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


        ECHO " ageSelectionFrom " . $ageSelectionFrom
        . " ageSelectionTo " . $ageSelectionTo
        . " travelDistance " . $travelDistance
        . " relationshipType " . $relationshipType;



        // Validate user inputs
        // --------------------
        $valid = true;
        $message = "";

        // Validate Email
        if (strlen($email) == 0) { // validate first name
            $valid = false;
            $message = "You must provide an email address";
        }
        if ((strpos($email, '.')) && (strpos($email, '@'))) {
            
        } else {
            $valid = 0;
            $message = 'Invalid email address';
        }
        if (strlen($firstname) == 0) { // validate first name
            $valid = false;
            $message = "First Name must be populated";
        }

        // Validate First name
        if (strlen($firstname) == 0) { // validate first name
            $valid = false;
            $message = "You must provide a First Name";
        }
        if (strlen($firstname) > 50) { // validate first name
            $valid = false;
            $message = "First Name Cannot be longer than 50 characters";
        }
        // Validate Surname
        if (strlen($surname) == 0) {
            $valid = false;
            $message = "You must provide a Surname";
        }
        if (strlen($surname) > 100) {
            $valid = false;
            $message = "Surname Cannot be longer than 100 characters";
        }

        // Validate DOB
        //Create a DateTime object using the user's date of birth.
        $checkkDOB = new DateTime($dob);
        $now = new DateTime();
        $difference = $now->diff($checkkDOB);
        $age = $difference->y;

        if ($age < 18) {
            $valid = false;
            $message = "You must be over 18 to use this site";
        }
        if ($age > 130) {
            $valid = false;
            $message = "Please check your age, it appears you are over 130?";
        }
        // Validate Surname
        if (strlen($city) == 0) {
            $valid = false;
            $message = "Please select the City/Town nearest to you";
        }
        // Validate gender
        if (strlen($gender) == 0) {
            $valid = false;
            $message = "Please select your gender";
        }
        // Validate gender preference
        if (strlen($preferredGender) == 0) {
            $valid = false;
            $message = "Please select your gender preference";
        }

        if ($ageSelectionFrom > $ageSelectionTo) {
            $valid = false;
            $message = "Age Profile from cannot be greater than Age Profile To";
        }
        // Relationship Type
        if (strlen($relationshipType) == 0) {
            $valid = false;
            $message = "Please select a relationship type";
        }

        // are all inputs valid?
        // ---------------------
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

            // Generate new matches based on updates to this users profile
            // Note: This closes matches older than 1 month that are stale
            $sql = "CALL generate_matches(" . $user_id . ", " . $user_id . ");";
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
            . " fROM user_profile up "
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
                $ageSelectionFrom = $row['from_age'];
                $ageSelectionTo = $row['to_age'];
                $travelDistance = $row['travel_distance'];
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
        
        <title>Personal Details</title>
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
        <div class="topnav">
            <a class="active">UPDATE PROFILE</a>
            <a href="MeetingSpace.php" title="Meeting Space">
                <?php echo $firstname . " " . $surname ?>

            </a>
            <div class="topnav-right">
                <a href="RemoveAccount.php" title="Remove your User Profile"><img height="16" width="16"  src='/images/Delete.png'/>Delete Profile</a>
                <a href="Logout.php" title="Log out of the system"><img height="16" width="16"  src='/images/Logoff.png'/>Logoff</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3" >
                <form method="post" name="challenge"  class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" AUTOCOMPLETE = "off" >
                    <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .35em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: 0.95;">
                        <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Edit Profile</legend>

                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 10pt; padding-top: 8px; text-align: left;">
                                Email <span style="color: red">*</span> :</div>   
                            <!--<label for="emailInput">Email</label>-->
                            <div class="col-sm-6 col-md-6 col-lg-5 col-xs-9 mobileLabel">
                                <input style="border-radius: 4px" type="text"  class="form-control" name="emailInput" value= "<?php echo $email; ?>">

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 10pt; padding-top: 8px; text-align: left;">
                                First Name <span style="color: red">*</span> :</div>  
                            <div class="col-sm-6 col-md-6 col-lg-5 col-xs-9 mobileLabel">
                                <input style="border-radius: 4px" type="text"  class="form-control" name="firstnameInput" value= "<?php echo $firstname; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 10pt; padding-top: 8px; text-align: left;">
                                Surname <span style="color: red">*</span> :</div>  
                            <div class="col-sm-6 col-md-6 col-lg-5 col-xs-9 mobileLabel">
                                <input style="border-radius: 4px" type="text"  class="form-control" name="surnameInput" value= "<?php echo ($surname); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 10pt; padding-top: 8px; text-align: left;">
                                Surname <span style="color: red">*</span> :</div>  
                            <div class="col-sm-6 col-md-6 col-lg-5 col-xs-9 mobileLabel">
                                <input style="border-radius: 4px" type="date"  class="form-control" name="dateOfBirthInput" value= "<?php echo $dob; ?>" min="1900-01-01" max="<?php echo (new \DateTime())->format('Y-m-d'); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 10pt; padding-top: 8px; text-align: left;">
                                Nearest City/Town <span style="color: red">*</span> :</div>  
                            <div class="col-sm-6 col-md-6 col-lg-5 col-xs-9 mobileLabel">
                                <select name="cityInput" class="selectpicker form-control"style=" font-size:10pt;height: 40px;">
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
                        </div>

                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 10pt; padding-top: 8px; text-align: left;">
                                Gender <span style="color: red">*</span> :</div>  
                            <div class="col-sm-6 col-md-6 col-lg-5 col-xs-9 mobileLabel">
                                <select name="genderInput" class="selectpicker form-control"style=" font-size:10pt;height: 40px;">
                                    <?php
                                    $sql = "select gender_name from gender order by gender_name";
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
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 10pt; padding-top: 8px; text-align: left;">
                                Preferred Gender <span style="color: red">*</span> :</div>  
                            <div class="col-sm-6 col-md-6 col-lg-5 col-xs-9 mobileLabel">
                                <select name="preferredGenderInput" class="selectpicker form-control"style=" font-size:10pt;height: 40px;">
                                    <?php
                                    $sql = "select gender_name  from gender order by gender_name";
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
                        </div>

                        <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12"></div>
                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 10pt;padding-top: 7px; text-align: left;">
                                Seeking Age Profile <span style="color: red">*</span> :</div>
                            <div class="col-sm-2 col-md-2 col-lg-2 col-xs-8 mobileLabel">
                                        <input name= "seekingAgeToSelection" type="range" min="18" max="100" value="<?php echo $ageSelectionFrom; ?>" step="2" list="tickmarks" id="rangeInput" oninput="output.value = rangeInput.value">
                                        <datalist id="tickmarks">
                                            <option value="18 to 100">18</option>
                                            <option>18</option>
                                            <option>40</option>
                                            <option>60</option>
                                            <option>80</option>
                                            <option>100</option>
                                        </datalist>
                                        <output id="output" for="rangeInput"> Min Age: <?php echo $ageSelectionFrom; ?></output> <!-- Just to display selected Age -->
                                    </div>
                                    <div class="col-sm-2 col-md-2 col-lg-2 col-xs-8 mobileLabel">
                                        <input name= "seekingAgeToSelection" type="range" min="18" max="100" value="<?php echo $ageSelectionTo; ?>" step="2" list="tickmarks" id="rangeInput2" oninput="output2.value = rangeInput2.value">
                                        <datalist id="tickmarks">
                                            <option value="18 to 100">100</option>
                                            <option>20</option>
                                            <option>40</option>
                                            <option>60</option>
                                            <option>80</option>
                                            <option>100</option>
                                        </datalist>
                                        <output id="output2" for="rangeInput2"> Max Age: <?php echo $ageSelectionTo; ?></output> <!-- Just to display selected Age -->
                                    </div>
                        </div>

                        
                        
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12"></div>
                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 10pt;padding-top: 7px; text-align: left;">
                                Distance I Will Travel <span style="color: red">*</span> :</div>
                            <div class="col-sm-6 col-md-6 col-lg-5 col-xs-8 mobileLabel">
                                <input name= "travelDistanceSelection" type="range" min="0" max="500" value="0" step="50" list="tickmarks" id="rangeInput3" oninput="output3.value = rangeInput3.value">
                                   <datalist id="tickmarks">
                                    <option value="0 to 500">0</option>
                                    <option>0</option>
                                    <option>50</option>
                                    <option>100</option>
                                    <option>150</option>
                                    <option>200</option>
                                    <option>250</option>
                                    <option>300</option>
                                    <option>350</option>
                                    <option>400</option>
                                    <option>450</option>
                                    <option>500</option>
                                   </datalist>
                                <output id="output3" for="rangeInput3"> Distance : 0</output> <!-- Just to display selected Age -->
                            </div>
                        </div>




                        <!-- ******************************************************************
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
                        <!--<label for="seekingAgeSelection">From</label>
                        <input type="range" min="18" max="65" value="<?php echo $ageSelectionFrom; ?>" class="slider" name="seekingAgeFromSelection">
                        <label for="seekingAgeSelection">To</label>
                        <input type="range" min="18" max="120" value="35"
                               value="<?php echo $ageSelectionTo; ?>" class="slider" data-show-value="true" name="seekingAgeToSelection">-->

                        <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12"></div>
                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 10pt;padding-top: 7px; text-align: left;">
                                Relationship Type <span style="color: red">*</span> :</div>

                        <div class="col-sm-6 col-md-6 col-lg-5 col-xs-8 mobileLabel">
                        <input type="radio" value="Love" <?php if ($relationshipType == "love") echo 'checked'; ?> name="relationshipTypeInput">Love</input>&nbsp;
                        <input type="radio" value="Casual" <?php if ($relationshipType == "casual") echo 'checked'; ?> name="relationshipTypeInput">Casual</input>&nbsp;
                        <input type="radio" value="Friendship" <?php if ($relationshipType == "friendship") echo 'checked'; ?> name="relationshipTypeInput">Friendship</input>&nbsp;
                        <input type="radio" value="Relationship" <?php if ($relationshipType == "relationship") echo 'checked'; ?> name="relationshipTypeInput">Relationship</input>
                        <?php
                        if (strlen($message) > 0) {
                            echo "<div class='alert alert-danger'>";
                            echo "<p>" . $message . "</p>";
                            echo "</div>";
                        }
                        ?>
                        </div>
                            </div>

                        <p align="middle">
                            <button class="btn btn-primary" name="btnAction" type="submit" value="Next">Next</button>
                            <button class="btn btn-warning" name="btnAction" type="submit" value="Cancel">Cancel</button>
                        </p>

                    </fieldset> 

                </form>
            </div>
        </div>
    </div>      
</body>

</html>