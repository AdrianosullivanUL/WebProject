<!DOCTYPE html>
<html lang="en">
    <head>
        <title>meeting space</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  


        <style>
            body{color:#444;font:100%/1.4 sans-serif;}
            body {
                background-image:    url(images/backlit-bonding-casual-708392.jpg);
                background-size:     cover;                      /* <------ */
                background-repeat:   no-repeat;
                background-position: center center;              /* optional, center the image */
            }


            /* CUSTOM RADIO & CHECKBOXES
               http://stackoverflow.com/a/17541916/383904 */
            .rad,
            .ckb{
                cursor: pointer;
                user-select: none;
                -webkit-user-select: none;
                -webkit-touch-callout: none;
            }
            .rad > input,
            .ckb > input{ /* HIDE ORG RADIO & CHECKBOX */
                visibility: hidden;
                position: absolute;
            }
            /* RADIO & CHECKBOX STYLES */
            .rad > i,
            .ckb > i{     /* DEFAULT <i> STYLE */
                display: inline-block;
                vertical-align: middle;
                width:  16px;
                height: 16px;
                border-radius: 50%;
                transition: 0.2s;
                box-shadow: inset 0 0 0 8px #fff;
                border: 1px solid gray;
                background: gray;
            }
            /* CHECKBOX OVERWRITE STYLES */
            .ckb > i {
                width: 25px;
                border-radius: 3px;
            }
            .rad:hover > i{ /* HOVER <i> STYLE */
                box-shadow: inset 0 0 0 3px #fff;
                background: gray;
            }
            .rad > input:checked + i{ /* (RADIO CHECKED) <i> STYLE */
                box-shadow: inset 0 0 0 3px #fff;
                background: orange;
            }
            /* CHECKBOX */
            .ckb > input + i:after{
                content: "";
                display: block;
                height: 12px;
                width:  12px;
                margin: 2px;
                border-radius: inherit;
                transition: inherit;
                background: gray;
            }
            .ckb > input:checked + i:after{ /* (RADIO CHECKED) <i> STYLE */
                margin-left: 11px;
                background:  orange;
            }

            label > input{ /* HIDE RADIO */
                visibility: hidden; /* Makes input not-clickable */
                position: absolute; /* Remove input from document flow */
            }
            label > input + img{ /* IMAGE STYLES */
                cursor:pointer;
                border:2px solid transparent;
            }
            label > input:checked + img{ /* (RADIO CHECKED) IMAGE STYLES */
                border:2px solid #f00;
            }

            img {
                max-width: 100%;
                max-height: 100%;
            }

            .portrait {
                height: 200px;
                width: 150px;
            }

            .landscape {
                height: 30px;
                width: 80px;
            }

            .square {
                height: 75px;
                width: 75px;
            }
        </style>
    </head>
    <body>
        <form action="/ProcessMeetingSpace.php" method="Post">
            <div class="container-fluid">
                <div class="row">

                    <div  class="col-sm-6 container border border-primary rounded bg-light text-dark" >
                        <h1>Meeting Space</h1>
                    </div>
                    <div class="col-sm-2 container border border-primary rounded bg-light text-dark">
                        <button class="btn btn-primary">Edit Profile</button>
                        <button class="btn btn-secondary">Logoff</button>
                    </div>

                </div>
                <br>    
                <div class="row">
                    <div class="col-sm-7 container border border-primary rounded bg-light text-dark">
                        <h3>System Matches</h3>

                        <?php
                        $user_id = $_GET["userid"];

                        require_once 'database_config.php';
                        ?>
                        <?php
                        $sql = "SELECT * FROM matches_view where system_generated_match = true and (match_user_id_1 =" . $user_id
                                . " or  match_user_id_2 =" . $user_id . ");";
                        if ($result = mysqli_query($db_connection, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    if ($row['match_user_id_1'] == $user_id) {
                                        echo ("<label class='rad'>");
                                        echo("<input type='radio' name='selected_user' value='" . $row['match_user_id_2'] . "'>");
                                        echo("");
                                        if (strlen($row['user_profile_2_picture']) > 0) {
                                            echo '<img class="portrait rounded-circle" src="data:image/jpeg;base64,' . base64_encode($row['user_profile_2_picture']) . '"/><i></i>';
                                        } else {
                                            echo ("<img class='portrait rounded-circle' src='images/camera-photo-7.png'/><i></i>'");
                                        }
                                        echo ("<figcaption>" . $row['user_profile_2_first_name'] . " " . $row['user_profile_2_surname'] . "</figcaption>");
                                        echo ("");
                                        echo ("</label>");
                                    } else {
                                        echo ("<figcaption>" . $row['user_profile_1_first_name'] . "</figcaption>");
                                        echo("<input type='radio' name='selected_user' value='" . $row['match_user_id_1'] . "'>");
                                        if (strlen($row['user_profile_2_picture']) > 0) {
                                            echo ("<img class='portrait  rounded-circle' src='data:image/jpeg;base64," . base64_encode($row['user_profile_1_picture']) . "/><i></i>");
                                        } else {
                                            echo ("<img class='portrait  rounded-circle' src='images/camera-photo-7.png'/><i></i>'");
                                        }
                                        echo ("<figcaption>" . $row['user_profile_1_first_name'] . " " . $row['user_profile_1_surname'] . "</figcaption>");
                                        echo ("</label>");
                                    }
                                }
                            } else {
                                echo ("No matches found");
                            }
                        } else {
                            echo ("bad result");
                        }
                        ?>
                        <div class="col-sm-12">
                            <p>Click on Photograph and do one of the following:</p>
                            <button name="btnAction" class="btn btn-success" type="submit" value="Like">Like</button>
                            <button name="btnAction" class="btn btn-info" type="submit" value="View">View</button>
                            <button name="btnAction" class="btn btn-primary" type="submit" value="Maybe">Maybe</button>
                            <button name="btnAction" class="btn btn-warning" type="submit" value="Goodbye">Goodbye</button>
                            <button name="btnAction" class="btn btn-danger" type="submit" value="Report"> Report!</button>
                            <button name="btnAction" class="btn btn-secondary" type="submit" value="MatchFinder">Match Finder</button></div>    
                    </div>

                    <div class="col-sm-4 container border border-primary rounded bg-light text-dark" ><h3>Interested in Me</h3>
                        <?php
                        $sql = "SELECT * FROM matches_view where system_generated_match = false and (match_user_id_1 =" . $user_id
                                . " or  match_user_id_2 =" . $user_id . ");";
                        if ($result = mysqli_query($db_connection, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    if ($row['match_user_id_1'] == $user_id) {
                                        echo ("<label class='rad'>");
                                        echo("<input type='radio' name='selected_user' value='" . $row['match_user_id_2'] . "'>");
                                        if (strlen($row['user_profile_2_picture']) > 0) {
                                            echo "<img class='portrait  rounded-circle' src='data:image/jpeg;base64," . base64_encode($row['user_profile_2_picture']) . "'/><i></i>";
                                        } else {
                                            echo ("<img class='portrait' src='images/camera-photo-7.png'/><i></i>'");
                                        }
                                        echo ("<figcaption>" . $row['user_profile_2_first_name'] . " " . $row['user_profile_2_surname'] . "</figcaption>");
                                        echo ("");
                                        echo ("</label>");
                                    } else {
                                        echo ("<figcaption>" . $row['user_profile_1_first_name'] . "</figcaption>");
                                        echo("<input type='radio' name='selected_user' value='" . $row['match_user_id_1'] . "'>");
                                        if (strlen($row['user_profile_2_picture']) > 0) {
                                            echo ("<img class='portrait  rounded-circle' src='data:image/jpeg;base64," . base64_encode($row['user_profile_1_picture']) . "'/><i></i>");
                                        } else {
                                            echo ("<img class='portrait' src='images/camera-photo-7.png'/><i></i>'");
                                        }
                                        echo ("<figcaption>" . $row['user_profile_1_first_name'] . " " . $row['user_profile_1_surname'] . "</figcaption>");
                                        echo ("</label>");
                                    }
                                }
                            } else {
                                echo ("No matches found");
                            }
                        } else {
                            echo ("bad result");
                        }
                        ?>
                        <div class="col-sm-12 ">
                            <p>Click on Photograph and do one of the following:</p>
                            <button name="btnAction" class="btn btn-success" type="submit" value="Like">Like</button>
                            <button name="btnAction" class="btn btn-info" type="submit" value="View">View</button>
                            <button name="btnAction" class="btn btn-primary" type="submit" value="Maybe">Maybe</button>
                            <button name="btnAction" class="btn btn-warning" type="submit" value="Goodbye">Goodbye</button>
                            <button name="btnAction" class="btn btn-danger" type="submit" value="Report"> Report!</button>
                            <button name="btnAction" class="btn btn-secondary" type="submit" value="MatchFinder">Match Finder</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>

    </form>        
</body>
</html> 