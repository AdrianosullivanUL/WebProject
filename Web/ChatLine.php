<?php
session_start();
// redirect to the logon screen if the user is not logged in
if ($_SESSION['user_logged_in'] == 0) {
    header("Location: Logon.php");
}
require_once 'database_config.php';
include 'group05_library.php';
$user_id = $_SESSION['user_id'];
$match_id = $_SESSION['match_id'];
$matching_user_id = $_SESSION['matching_user_id'];
$lastCommunicationId = 0;
//echo "session user " . $user_id;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "I am a post";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>chatline</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  


        <style>

            body{color:#444;font:100%/1.4 sans-serif;}
            body {
                background-image:    url(backlit-bonding-casual-708392.jpg);
                background-size:     cover;                      /* <------ */
                background-repeat:   no-repeat;
                background-position: center center;              /* optional, center the image */
            }
            #main{
                height: 455px;
                background-color: white;
                margin-top: 70px;
            }
            .output{
                background-color: white;
                box-shadow: 0px 1px 1px #000;
                height: 250px;
                margin-bottom: 20px;
                overflow: scroll;		
            }

            ul{
                list-style:none;
            }

            input[type=submit]{
                width: 100px;
                box-sizing: border-box;
                border:4px solid #6495ed;
                border-radius: 4px;
            }

            textarea{
                background-color: #dcdcdc;
                width: 350px;
            }

        </style>

    </head>
    <body>


        <div  class="col-sm-6 container border border-primary rounded bg-light text-dark" >
            <h1>Chat Line</h1>
        </div>

        <div col-sm-6 container border border-primary rounded bg-light text-dark>
            <?php
            $name = '';
            $sql = "SELECT * FROM user_profile where id =" . $user_id . ";";
            if ($result = mysqli_query($db_connection, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        $name = $row['first_name'] . ' ' . $row['surname'];
                    }
                }
            } else {
                echo ("No matches found");
            }
            ?>




            <h1 style=" background-color: #6495ed;color: white;"><?php echo $name ?>-online</h1>
            <div class="output col-sm-6 container border border-primary rounded bg-light text-dark">

                <?php
                // force user id's just for testing:
                // $user_id = 24;
                // $matching_user_id = 15;
                $result = get_communications_thread($db_connection, $user_id, $matching_user_id);
                if ($result == null)
                    echo "no thread found";
                else {
                    while ($row = mysqli_fetch_array($result)) {
                        if ($row['from_user_id'] == $user_id)
                            echo "<div class='float-sm-left col-sm-6 container border border-primary rounded text-dark bg-success'>";
                        else
                            echo "<div class='float-sm-right col-sm-6 container border border-primary rounded text-dark bg-info'>";

                        echo $row['message'] . "<br>";
                        echo "</div>";
                        echo "<br>";
                        $lastCommunicationId = $row['id'];
                    }
                }
                $_SESSION['last_communication_id'] = $lastCommunicationId;
                ?>


            </div>
            <div class="output col-sm-6 container border border-primary rounded bg-light text-dark">
                <br>
                <form method="post" action="Send.php">
                    <textarea name="msg" placeholder="Type to send message...."
                              class="form-control"></textarea><br>
                    <input type="submit" value="Send">
                </form>
                <br>
                <form action="Logout.php">

                    <input stype="width: 100%;background-color: #6495ed;color:
                           white;font-size: 20px;" type="submit" value="Logout">
                </form>
            </div>
        </div>

    </body>
</html> 
