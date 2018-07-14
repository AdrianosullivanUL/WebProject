<!DOCTYPE html>
<?php
require_once 'database_config.php';

session_start();
$user_id = $_SESSION['user_id'];
$matching_user_id = $_SESSION['matching_user_id'];
?>
<html lang="en">
    <head>
        <title>view matching profile</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  
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
        <form action="/ViewMatchProfile.php" method="Post">
            <div class="topnav">
                <a class="active">MATCHED PROFILE</a>
                <a href="MeetingSpace.php">Home</a>
                <div class="topnav-right">
                    <a href="UpdateProfile.php">Update Profile</a>
                    <a href="logout.php">Log Out</a>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">

                </div>
                <br>
                <div class ="row">
                    <div class="col-xs-0 col-sm-1" style="background-color:transparent; opacity: 0.0;">
                        <p> " "/p>
                    </div>
                    <div class="col-xs-6 col-sm-4" style="background-color:whitesmoke; opacity: 0.9;">
                        <?php
                        $sql = "SELECT * FROM user_profile where id =" . $matching_user_id . ";";
                        $mibio = "";
                        $picture = "";
                        $first_name = "";
                        $surname = "";
                        if ($result = mysqli_query($db_connection, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    $mybio = $row['my_bio'];
                                    if (strlen($row['picture']) > 0) {
                                        $picture = base64_encode($row['picture']);
                                    } else {
                                        
                                    }
                                    $first_name = $row['first_name'];
                                    $surname = $row['surname'];
                                }
                            }
                        }
                        ?>
                        <h4><?php echo $first_name . " " . $surname ?> </h4>
                       <!-- Display Image -->
                        <?php
                        if (strlen($picture) > 0) {
                            echo '<img class="portrait"src="data:image/jpeg;base64,' . $picture . '"/><i></i>';
                        } else {
                            echo ("<img class='portrait' src='images/camera-photo-7.png'/><i></i>'");
                        }
                        ?>
                    </div>
                    <div class="col-xs-6 col-sm-6" style="background-color:lavender; opacity: 0.9;">
                        <h3><?php echo $first_name ?>'s bio</h3>
                        <h4><?php echo $mybio ?></h4>

                    </div>
                </div>
                <div class ="row">
                    <div class="col-sm-12" style="background-color:transparent; opacity: 0.0;">

                    </div>
                </div>

                <div class ="row">
                    <div class="col-xs-0 col-sm-1" style="background-color:transparent; opacity: 0.0;">
                        <p> " "/p>
                    </div>
                    <div class ="col-xs-4 col-sm-4"style="background-color:lavender; opacity: 0.9;">
                        <?php
                        echo "<h3> $first_name's Interests </h3> ";
                        $sql = "SELECT description
                       FROM interests
                       LEFT JOIN user_interests ON interest_id = interests.id
                       where user_id = " . $matching_user_id . ";";
                        $interest = "";
                       if ($result = mysqli_query($db_connection, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    $description = $row['description'];
                                    echo("<h4>. $description ");
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="col-xs-6 col-sm-6"style="background-color:whitesmoke; opacity: 0.9;text-align:right">
                        
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <button name="btnAction" class="btn btn-success" type="submit" value="Like">Like</button>
                        <button name="btnAction" class="btn btn-info" type="submit" value="View">View</button>
                        <button name="btnAction" class="btn btn-primary" type="submit" value="Maybe">Maybe</button>
                        <button name="btnAction" class="btn btn-warning" type="submit" value="Goodbye">Goodbye</button>
                        <button name="btnAction" class="btn btn-danger" type="submit" value="Report"> Report!</button>
                    </div>



                </div>

        </form>

    </body>
</html> 