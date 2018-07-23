<?php
require_once 'database_config.php';

session_start();

$_SESSION['user_id'] = 0;
$_SESSION['matching_user_id'] = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // check the button selected (these are at the end of this form
    if ($_POST['btnAction'] == "MeetingSpace") { // Call Edit Profile
        header("Location: MeetingSpace.php");
        exit();
    }
    if ($_POST['btnAction'] == "ViewMatchingProfile") { // Call Edit Profile
        header("Location: ViewMatchProfile.php");
        exit();
    }
    if ($_POST['btnAction'] == "UpdateProfile") { // Call Edit Profile
        header("Location: UpdateProfile.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Chance Dating</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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


        </style>
    </head>
    <body >
        <div  class="col-sm-6 container border border-primary rounded bg-light text-dark" >
            <?php
            include 'group05_library.php';
            $text = "This is a piece of text with bad  arse fuck words";
            $badWords = [];
            echo $text;
            echo "<br>";
            $words = str_word_count($text, 1);

            echo "<br>";
             // TODO fix problem with f..k not being treated as a word, need to use space as seperator
            $badWordsResult = get_bad_words_in_text($db_connection, $text);
            if ($badWordsResult != null) {
                foreach ($badWordsResult as &$word) {
                    echo "bad word result " . $word . "<br>";
                }
            }
            ?>
        </div>
    </body>
</html> 