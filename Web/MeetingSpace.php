<!DOCTYPE html>
<html lang="en">
    <head>
        <title>meeting space</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <!--<div class="container-fluid">-->
        <div  class="col-sm-8">
            <h1>Meeting Space</h1>
        </div>
        <div class="col-sm-4"><button>Edit Profile</button><button>Logoff</button></div>
        <div class="col-sm-8"><h3>System Matches</h3>
            <?php
            require_once 'database_config.php';

            // Attempt select query execution
            $sql = "SELECT * FROM user_profile where is_administrator = false and is_automated = true";
            if ($result = mysqli_query($db_connection, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                      //  echo "<table class='table table-bordered table-striped'>";
                         echo '<img src="data:image/jpeg;base64,' . base64_encode($row['picture']) . '"/>'; 
                        echo "<tr><td>" . $row['first_name'] . "</td>";
                    }
                }
            }
            ?>
        </div>
        <div class="col-sm-4"><h3>Interested in Me</h3></div>
        <div class="col-sm-12"><p>Click on Photograph and do one of the followings</p>
            <button>View</button><button>Maybe</button><button>Goodbye</button><button>Report!</button>
            <button>Match Finder</button></div>    
        <!--</div>-->
    </body>
</html> 