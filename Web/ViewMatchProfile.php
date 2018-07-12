<!DOCTYPE html>
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


        </style>
    </head>
    <body>
        <form action="/ProcessMeetingSpace.php" method="Post">
            <div class="container-fluid">
                <div class="row">

                    <div  class="col-sm-6 container border border-primary rounded bg-light text-dark" >
                        <h1>Matched Profile</h1>
                    </div>

                </div>
                <br>
                <?php
                $user_id = $_GET["userid"];

                require_once 'database_config.php';
                ?>
                <div class ="row">
                    <div class="col-sm-6 container border border-primary rounded bg-light text-dark">
                        <?php
                        $sql = "SELECT * FROM user_profile where id =" . $user_id . ";";
                        echo($sql);
                        if ($result = mysqli_query($db_connection, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    echo("<h3>" . $row['first_name']" *$row['sur_name']</h3>");
                                    echo("<br>");
                                    if (strlen($row['picture']) > 0) {
                                            echo '<img class="portrait rounded-circle" src="data:image/jpeg;base64,' . base64_encode($row['picture']) . '"/><i></i>';
                                        } else {
                                            echo ("<img class='portrait rounded-circle' src='images/camera-photo-7.png'/><i></i>'");
                                        }
                                    //echo ("<img class='portrait rounded-circle' src='images/camera-photo-7.png'/><i></i>'");
                                    //echo("<p>My Bio</p>");
                                    //echo("<p>Slider Age Gap</p>");
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class ="row">
                    <div class ="col">
                        <?php
                        echo("<h3>Interests/Hobbies</h3>");
                        echo("<p>Check Box Interest 1</p>");
                        echo("<p>Check Box Interest 2</p>");
                        echo("<p>Check Box Interest 3</p>");
                        echo("<p>Check Box Interest 4</p>");
                        ?>
                    </div>
                </div>



            </div>

        </form>

    </body>
</html> 