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
    if ($_POST['btnAction'] == "Submit") { // Call Edit Profile
        // Validate user inputs
        // --------------------
        $valid = true;
        $message = "";

        // Get the form inputs
        $preferredGender = $_POST['preferredGenderInput'];
        $ageSelection = $_POST['seekingAgeSelectionName'];
        $travelDistance = $_POST['travelDistanceSelection'];
        $relationshipType = $_POST['relationshipType'];

        if (strlen($firstname) == 0) { // validate first name
            $valid = false;
            //$message = "First Name must be populated";
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
    $preferred_gender_name = "";
    $relationshipTypeLove = true;
    $relationshipTypeCasual = false;
    $relationshipTypeFriendship = false;
    $relationshipTypeRelationship = false;
    $sql = "SELECT up1.*, g1.gender_name, g2.gender_name as preferred_gender_name FROM user_profile up1 join gender g1 on g1.id = up1.gender_id join gender g2 on g2.id = up1.gender_preference_id where up1.id =" . $user_id . ";";
    if ($result = mysqli_query($db_connection, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $email = $row['email'];

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
        <title>Matchfind</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  
        <style>
            body {
                background-image:    url(backlit-bonding-casual-708392.jpg);
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
        <div class="topnav">
            <a class="active">FIND YOUR MATCH</a>
            <a href="MeetingSpace.php">Home</a>
            <div class="topnav-right">
                <a href="index.php">About</a>
                <a href="logout.php">Log Out</a>
            </div>
        </div>
        <<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <!------ Include the above in your HEAD tag ---------->

        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2" >
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-horizontal" role="form" action="#" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >
                        <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .35em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .8;">
                            <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Perfect Match Filter</legend>

                            <div class="form-group">
                                <div class="col-sm-8 col-md-8 col-lg-8 col-xs-8" style="text-align: right;">
                                    <span style="color: red">*</span> <span style="font-size: 8pt;">mandatory fields</span>
                                </div>
                            </div>	
                            <div class="form-group" style="margin-bottom: 0px;">
                                <div class="col-sm-8 col-md-8 col-lg-8 col-xs-8"></div>
                                <div class="col-sm-8 col-md-8 col-lg-7 col-xs-10 mobilePad" id="message10" style=" font-size: 15pt;padding-left: 0px;"></div>                      

                            </div>				
                            <div class="form-group">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 15pt; padding-top: 3px; text-align: left;">
                                    Gender Preference <span style="color: red">*</span> :</div>
                                <div class="col-sm-7 col-md-7 col-lg-6 col-xs-10 mobileLabel">
                                    <select class="selectpicker form-control"style=" font-size:15pt;height: 40px;"">
                                        <option>Female</option>
                                        <option>Male</option>
                                        <option>TransGender</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 15pt;padding-top: 7px; text-align: left;">
                                    Preferred Location <span style="color: red">*</span> :</div>
                                <div class="col-sm-7 col-md-7 col-lg-6 col-xs-10 mobileLabel">
                                    <select class="selectpicker form-control"style=" font-size:15pt;height: 40px;background-color: whitesmoke!important;">
                                        <option>Limerick</option>
                                        <option>Galway</option>
                                        <option>Cork</option>
                                        <option>Waterford</option>
                                        <option>Dublin</option>
                                        <option>Belfast</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 15pt;padding-top: 7px; text-align: left;">
                                    Relationship Type <span style="color: red">*</span> :</div>
                                <div class="col-sm-7 col-md-7 col-lg-6 col-xs-10 mobileLabel">
                                    <select class="selectpicker form-control"style=" font-size:15pt;height: 40px;">
                                        <option>Love</option>
                                        <option>Casual</option>
                                        <option>Friendship</option>
                                        <option>relationship</option>
                                    </select>
                                </div>
                            </div> 


                            <div class="form-group">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                <div class="col-sm-3 col-md-3 col-lg-4 col-xs-8 mobileLabel" style=" font-size: 15pt;padding-top: 7px; text-align: left;">
                                    Interests <span style="color: red">*</span> :</div>
                                <div class="col-sm-8 col-md-8 col-lg-7 col-xs-10 mobileLabel">
                                    <div class="checkbox" style=" font-size: 15pt;width: auto;">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" value="">Sports
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" value="">Outdoors
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" value="">Reading
                                        </label>
                                         <label class="checkbox-inline">
                                            <input type="checkbox" value="">Fun
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                    <div class="col-sm-11 col-md-11 col-lg-11 col-xs-10" style="text-align:center;">
                                        <button id="valuser" type="button" onclick="submitForm()"
                                                class="btn btn-success">
                                            Submit</button>
                                    </div>

                                    <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                </div>   
                                <div class="form-group" style="text-align:center;font-weight:bold">

                                    </fieldset>

                                    </form>

                                </div>

                            </div>

                            </body>
                            </body>
                            </html>

                            </html>