<!DOCTYPE html>
<html lang="en">
    <head>
        <title>remove account</title>
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
        <?php
        $user_id = $_GET["userid"];

        require_once 'database_config.php';
        ?>
        <form action="/ProcessRemoveAccount.php?userid=<?php echo $user_id ?>" method="Post">

            <div class="container">
                <br>
                <div class="container border border-primary rounded bg-light text-dark col-sm-6">
                    <div class="row">
                        <div class="col-sm-12">
                            <h1>Remove Account</h1>
                        </div>
                        <div class="col-sm-12">
                            <?php
                            $sql = "SELECT * FROM user_profile where id =" . $user_id . ";";
                            if ($result = mysqli_query($db_connection, $sql)) {
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_array($result)) {
                                        if ($row['id'] == $user_id) {
                                            echo("<h3>" . $row['first_name'] . " " . $row['surname'] . "</h3>");
                                            echo("<p>(" . $row['email'] . ")</p>");
                                        }
                                    }
                                }
                            }
                            ?>
                        </div>                    
                        <div class="col-sm-12">
                            <p>Please note, if you remove your account then all information related to you and your account will be removed and cannot be recovered.
                                This includes your profile, images, communications history and matches. 
                                <br><br>If you are happy with this, press the Remove button below.
                                If you would like to keep your account, press Cancel.</p>
                        </div>
                        <br>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <button  class="btn btn-danger" name="btnAction" type="submit" value="Remove">Remove Account</button>
                            <button class="btn btn-secondary" name="btnAction" type="submit" value="Cancel">Cancel</button>
                            <br>

                        </div>
                        <br>
                    </div>
                    <br>
                </div>
            </div>
        </form>
    </body>
</html> 