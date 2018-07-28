<?php
session_start();
// redirect to the logon screen if the user is not logged in
require_once 'database_config.php';
include 'group05_library.php';
$user_id = $_SESSION['user_id'];
$session_hash = $_SESSION['session_hash'];
if (validate_logon($db_connection, $user_id, $session_hash) == false) {
    // User is not correctly logged on, route to Logon screen
    header("Location: Logon.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['btnAction'] == "Cancel") { // Back to appropriate screen
        if ($_SESSION['is_administrator'] == 1) {
            header("Location: AdminScreen.php");
            exit();
        } else {
            header("Location: MeetingSpace.php");
            exit();
        }
    }
    $_SESSION['user_id'] = 0;
    header("Location: Logon.php");
    exit;
}
?>
<head>
    <title>Logout</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>  
    <link rel="stylesheet" href="StyleSheet.css">
</style>
</head>
<body>
    <div class="topnav">
        <a class="active">LOG OUT</a>
        <a href="MeetingSpace.php">Home</a>
        <div class="topnav-right">
            <a href="index.php">About</a>
            <a href="logon.php">Log In</a>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3" >
                <form method="post" name="challenge"  class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" AUTOCOMPLETE = "off" >
                    <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .35em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .8;">
                        <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Log Out</legend>

                        <h4>Are you sure you want to Logout ?  </h4>
                        <br>
                        <br>
                        <a href="Logon.php" class="btn btn-info btn-lg">
                            <span class="glyphicon glyphicon-log-out"></span> Log out
                        </a>
                        <button name="btnAction" class="btn btn-info btn-lg" type="submit" value="Cancel">Cancel</button>
                        </p> 

                    </fieldset>

                </form>
            </div>
        </div>

    </div>

</body>
</html>