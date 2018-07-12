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

        <div class="container border border-primary rounded bg-light text-dark col-sm-6">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Account Removed</h1>
        <?php
        $user_id = $_GET["userid"];

        require_once 'database_config.php';

        // Remove Communications entries
        $sql = "delete  from user_communication where from_user_id =" . $user_id . " or to_user_id = " . $user_id . ";";
        
        // Remove User Interests
        $sql = "delete from user_interests where user_id =" . $user_id . ";";
                // Remove User Interests
        $sql = "delete from match_table where match__user_id_1 =" . $user_id . " or match_user_id_2 = " . $user_id . ";";
        $sql = "delete from user_profile where id =" . $user_id . ";";

        ?>
                    <br>
                    <h3>Goodbye</h3>
                </div>    



