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
//echo "session user " . $user_id;
$city = "";
$first_name = "";
$surname = "";
$gender_name = "";
$preferred_gender_name = "";
$message = "";
$email = "";
$relationship_type = "";
$description = "";
$from_age="";
$to_age="";



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $preferred_gender_name = $_POST['preferredGenderInput'];
    $city = $_POST['selectedCity'];
    $relationship_type = $_POST['selectedRelationship'];
    $description = $_POST['selectedHobby'];
    $from_age = $_POST['fromAge'];
    $to_age = $_POST['toAge'];
    // check the button selected (these are at the end of this form
    if ($_POST['btnAction'] == "Cancel") { // cancel the update
        echo "Cancel pressed";
        header("Location: meetingspace.php");
        exit();
    }
} else {
    $sql = "SELECT up.*, c.city, r.relationship_type, g1.gender_name, g2.gender_name as preferred_gender_name "
            . " FROM user_profile up   "
            . "LEFT JOIN city c ON c.id = up.city_id   "
            . "LEFT JOIN city r ON r.id = up.relationship_type_id   "
            . "LEFT JOIN gender g1 ON g1.id = up.gender_preference_id  "
            . "left join gender g2 on g2.id = up.gender_id "
            . "left join user_interests ui on ui.user_id = up.id "
            . "left join interest i on i.id = ui_interests.id"
            . "where up.id = '" . $user_id . "'";
//echo $sql;
// 
    if ($result = mysqli_query($db_connection, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $city = $row['city'];
                $first_name = $row['first_name'];
                $surname = $row['surname'];
                $gender_name = $row['gender_name'];
                $preferred_gender_name = $row['preferred_gender_name'];
                echo "city " . $city;
                //echo "pref " . $preferred_gender_name;
                $email = $row['email'];
                $relationshipTypeId = $row['relationship_type_id'];
                $relationship_type = $row['relationship_type'];
                $description = $row['description'];
                $from_age = $row['from_age'];
                $to_age = $row['to_age'];
                
                
               // echo "relationship " . $relationship_type;
                //echo "hobby " . $description;
            }
        } else {
            $message = "Cannot find user profile";
        }
    }
//echo "I Am here" . $preferred_gender_name . " " . $city ;
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
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="StyleSheet.css">
        <!--<style>
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
            input[type='range'] {
                box-sizing: border-box;
                border: 0px solid transparent;
                padding: 0px;
                margin: 0px;
                width: 210px;
                height: 50px;
                cursor: pointer;
                background: -webkit-repeating-linear-gradient(90deg, #777, #777 1px, transparent 1px, transparent 40px) no-repeat 50% 50%;
                background: -moz-repeating-linear-gradient(90deg, #777, #777 1px, transparent 1px, transparent 40px) no-repeat 50% 50%;
                background: repeating-linear-gradient(90deg, #777, #777 1px, transparent 1px, transparent 40px) no-repeat 50% 50%;
                background-size: 122px 25px;
                font-size: 16px;
            }
            input[type='range'],
            input[type='range']::-webkit-slider-runnable-track,
            input[type='range']::-webkit-slider-thumb {
                -webkit-appearance: none;
            }
            input[type='range']::-webkit-slider-runnable-track {
                box-sizing: border-box;
                width: 200px;
                height: 5px;
                border-radius: 2px;
                background: #777;
            }
            input[type='range']::-moz-range-track {
                box-sizing: border-box;
                width: 200px;
                height: 5px;
                border-radius: 2px;
                padding: 0px;
                background: #777;
            }
            input[type='range']::-moz-range-thumb {
                box-sizing: border-box;
                padding: 0px;
                height: 20px;
                width: 10px;
                border-radius: 2px;
                border: 1px solid;
                background: #EEE;
            }
            input[type='range']::-ms-track {
                box-sizing: border-box;
                width: 210px;
                height: 5px;
                border-radius: 2px;
                padding: 0px;
                background: #777;
                color: #777;
            }
            input[type='range']::-webkit-slider-thumb {
                box-sizing: border-box;
                padding: 0px;
                height: 20px;
                width: 10px;
                border-radius: 2px;
                border: 1px solid;
                margin-top: -8px;
                background: #EEE;
            }
            input[type='range']::-ms-thumb {
                box-sizing: border-box;
                padding: 0px;
                height: 20px;
                width: 10px;
                border-radius: 2px;
                border: 1px solid;
                background: #EEE;
            }
            input[type="range"]::-ms-fill-lower {
                background: transparent;
            }
            input[type='range']:focus {
                outline: none;
            }
            /*input[type='range']:after{
              position: absolute;
              content: '20 40 60 80';
              padding: 25px 4035px;
              word-spacing: 20px;
              left: 0px;
              top: 0px;
            }*/

            .container:after {
                position: absolute;
                color: #777;
                content: '20 40 60 80';
                padding: 40px;
                word-spacing: 20px;
                left: 0px;
                top: 0px;
                z-index: -1;
            }
            .container {
                padding: 0px;
                position: relative;
            }

            /* Just for demo */

            output{
                display: block;
                margin-top: 10px;
                color: #777;
            }
            output:before{
                content:"Selected Age: ";
                font-weight: bold;
            }
            body {
                font-family:Arial;
            }


            .stackem div {
                width: 100%;
            }
        </style>-->
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

        <div class="container">
            <div class="row">
                <div class="col-md-12 col-md-offset-0.5" >
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-horizontal" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >
                        <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .35em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .9;">
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
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 15pt; padding-top: 10px; text-align: left;">
                                    Gender Preference <span style="color: red">*</span> :</div>
                                <div class="col-sm-6 col-md-6 col-lg-5 col-xs-9 mobileLabel">
                                    <select name="preferredGenderInput" class="selectpicker form-control"style=" font-size:15pt;height: 40px;">
                                        <option ></option>";
                                        <?php
                                        $sql = "select gender_name  from gender order by gender_name";
                                        if ($result = mysqli_query($db_connection, $sql)) {
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_array($result)) {
                                                    if ($preferred_gender_name == $row['gender_name'])
                                                        echo "<option selected='selected'>" . $row['gender_name'] . "</option>";
                                                    else
                                                        echo "<option>" . $row['gender_name'] . "</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 15pt;padding-top: 7px; text-align: left;">
                                    Preferred Location <span style="color: red">*</span> :</div>
                                <div class="col-sm-6 col-md-6 col-lg-5 col-xs-9 mobileLabel">
                                    <select name ="selectedCity" class="selectpicker form-control"style=" font-size:15pt;height: 40px;">
                                        <option ></option>";
                                        <?php
// get the user_profile record here
// 

                                        $sql = "select city from city";
                                        if ($result = mysqli_query($db_connection, $sql)) {
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_array($result)) {
                                                    //echo $city . " " . $row['city'];
                                                    if ($city == $row['city'])
                                                        echo "<option selected='selected'>" . $row['city'] . "</option>";
                                                    else
                                                        echo "<option>" . $row['city'] . "</option>";
                                                }
                                            }
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <!--Relationship Type drop down-->
                            <div class="form-group">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 15pt;padding-top: 7px; text-align: left;">
                                    Relationship Type <span style="color: red">*</span> :</div>
                                <div class="col-sm-6 col-md-6 col-lg-5 col-xs-9 mobileLabel">
                                    <select name= "selectedRelationship"class="selectpicker form-control"style=" font-size:15pt;height: 40px;">
                                        <option ></option>";
                                        <?php
                                        $sql = "select relationship_type from relationship_type";
                                        if ($result = mysqli_query($db_connection, $sql)) {
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_array($result)) {
                                                    if ($relationship_type == $row['relationship_type'])
                                                        echo "<option selected='selected'>" . $row['relationship_type'] . "</option>";
                                                    else
                                                        echo "<option>" . $row['relationship_type'] . "</option>";
                                                }
                                            }
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <!--Hobby Drop Down-->

                            <div class="form-group">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 15pt;padding-top: 7px; text-align: left;">
                                    Interests <span style="color: red">*</span> :</div>
                                <div class="col-sm-6 col-md-6 col-lg-5 col-xs-9 mobileLabel">
                                    <select name= "selectedHobby"class="selectpicker form-control"style=" font-size:15pt;height: 40px;">
                                        <option ></option>";
                                        <?php
                                        $sql = "select description from interests";
                                        if ($result = mysqli_query($db_connection, $sql)) {
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_array($result)) {
                                                    if ($description == $row['description'])
                                                        echo "<option selected='selected'>" . $row['rdescrition'] . "</option>";
                                                    else
                                                        echo "<option>" . $row['description'] . "</option>";
                                                }
                                            }
                                        }
                                        ?> 
                                    </select>
                                </div>

                                <?php
                                if (!empty($_POST['check_list'])) {
                                    foreach ($_POST['check_list'] as $check) {
                                        echo $check;
                                        //echoes the value set in the HTML form for each checked checkbox.
                                        //so, if I were to check 1, 3, and 5 it would echo value 1, value 3, value 5.
                                        //in your case, it would echo whatever $row['Report ID'] is equivalent to.
                                    }
                                }
                                ?>

                                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12"></div>

                                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12"></div>
                                <div class="form-group">
                                    <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                    <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" font-size: 15pt;padding-top: 7px; text-align: left;">
                                        Seeking Age Profile <span style="color: red">*</span> :</div>
                                    <div class="col-sm-2 col-md-2 col-lg-2 col-xs-8 mobileLabel">
                                        <input name= "fromAge" type="range" min="18" max="100" value="18" step="2" list="tickmarks" id="rangeInput" oninput="output.value = rangeInput.value">
                                        <datalist id="tickmarks">
                                            <option value="18 to 100">18</option>
                                            <option>20</option>
                                            <option>40</option>
                                            <option>60</option>
                                            <option>80</option>
                                            <option>100</option>
                                        </datalist>
                                        <output id="output" for="rangeInput"> Min Age : 18</output> <!-- Just to display selected Age -->
                                    </div>
                                    <div class="col-sm-2 col-md-2 col-lg-2 col-xs-8 mobileLabel">
                                        <input name= "toAge" type="range" min="18" max="100" value="100" step="2" list="tickmarks" id="rangeInput2" oninput="output2.value = rangeInput2.value">
                                        <datalist id="tickmarks">
                                            <option value="18 to 100">100</option>
                                            <option>20</option>
                                            <option>40</option>
                                            <option>60</option>
                                            <option>80</option>
                                            <option>100</option>
                                        </datalist>
                                        <output id="output2" for="rangeInput2"> Max Age : 100</output> <!-- Just to display selected Age -->
                                    </div>
                                </div>
                                 <div class="form-group">
                                    
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12"></div>




                                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12"></div>
                                <div class="form-group">
                                    <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                    <div class="col-sm-11 col-md-11 col-lg-11 col-xs-10" style="text-align:center;">
                                        <button class="btn btn-primary" id="valuser" type="submit" name="btnAction" name="btnAction" value="Submit" class="btn btn-success">
                                            Submit</button>
                                    </div>

                                    <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                </div>
                                <div class="form-group" style="text-align:center;font-weight:bold">


                                    <div class="row">
                                        <div class="col-md-12 col-md-offset-0.5" >
                                            <?php
                                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                                // check the button selected (these are at the end of this form
                                                if ($_POST['btnAction'] == "Submit") { // Call Edit Profile
                                                    // Sql to pull data from other users and match selected gender selectedcity and relationshiptyoe
                                                    $sql = "SELECT up.*, g1.gender_name, g2.gender_name as preferred_gender_name, c.city, i.description, r.relationship_type FROM user_profile up"
                                                            . " left join gender g1 on g1.id = up.gender_id "
                                                            . " left join gender g2 on g2.id = up.gender_preference_id  "
                                                            . " left join city c on c.id = up.city_id "
                                                            . " left join relationship_type r on r.id=up.relationship_type_id"
                                                            . " left join user_interests ui on ui.user_id = up.id "
                                                            . " left join interests i on i.id = ui.interest_id"
                                                            . " where is_administrator = FALSE AND black_listed_user = false ";
                                                    if (strlen($preferred_gender_name) > 0)
                                                        $sql = $sql . " and gender_id in (select id from gender where gender_name = '" . $preferred_gender_name . "')"
                                                                . " ";
                                                    if (strlen($city) > 0)
                                                        $sql = $sql . " And city_id in (select id from city where city = '" . $city . "')";

                                                    if (strlen($relationship_type) > 0)
                                                        $sql = $sql . " and relationship_type_id in (select id from relationship_type where relationship_type = '" . $relationship_type . "')";
                                                    if (strlen($description) > 0)
                                                        $sql = $sql . " And ui.interest_id = (select id from interests where description = '" . $description . "')";
                                                    if (strlen($from_age) > 0)
                                                        $sql = $sql . " And up.id in (select id from user_profile where from_age > '" . $from_age . "')";
                                                    if (strlen($to_age) > 0)
                                                        $sql = $sql . " And up.id in (select id from user_profile where to_age < '" . $to_age . "')";
                                                    
                                                    

                                                    //echo $sql;

                                                    //echo $sql; 
                                                    // need to add other columns here
                                                    //            . "where id = " . $user_id . ";";
                                                    $pictureIndex = 0;
                                                    $result = execute_sql_query($db_connection, $sql);
                                                    if ($result == null) {
                                                        echo "<br><p>No matches found</p>";
                                                    } else {
                                                        while ($row = mysqli_fetch_array($result)) {
                                                            $pictureIndex++;
                                                            //echo ("<li>");
                                                            // echo "<div class='container>";
                                                            echo "        <input type='radio' class='hideinput' name='selected_user' id='radio" . $pictureIndex . "' value='" . $row['id'] . "'/>";
                                                            echo "        <label for='radio" . $pictureIndex . "'>";
                                                            echo "        <label >" . $row['first_name'] . " " . $row['surname'] . "</label>";
                                                            echo "<br>";
                                                            if (strlen($row['picture']) > 0)
                                                                echo "<img class='rounded-circle selectimg'  height='100' width='100' src='data:image/jpeg;base64," . base64_encode($row["picture"]) . "'/>";
                                                            else
                                                                echo ("<img class='selectimg' height='100' width='100' src='../images/camera-photo-7.png'/><i></i>");
                                                            echo "</label>";
                                                        }
                                                    }


                                                    if ($result == null) {
                                                        $message = "ERROR: Cannot match entry " . $user_id;
                                                    } else {
                                                        while ($row = mysqli_fetch_array($result)) {
                                                            echo $row['first_name'] . " " . $row['city'] . " " . $row['gender_name'] . " " . $row['preferred_gender_name'] . "<br>";
                                                            if (strlen($row['picture']) > 0)
                                                                echo "<img class='rounded-circle'  height='32' width='32' src='data:image/jpeg;base64," . base64_encode($row["picture"]) . "'/>";
                                                            else
                                                                echo ("<img height='32' width='32' src='../images/camera-photo-7.png'/><i></i>");
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                            </fieldset>
                                            </form>
                                        </div>
                                    </div<!-- and up.id in (select user_id from user_interests where interest_id 
                                            //in (select id from interests where description in ('Music','Sport','Traveling')));-->
                                </div>

                                </body>
                                </html>