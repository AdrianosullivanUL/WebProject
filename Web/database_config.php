<?php
    //define('DB_SERVER', 'localhost');
    //define('DB_USERNAME', 'root');
    //define('DB_PASSWORD', '');
    //define('DB_NAME', 'group05'); 
    define('DB_SERVER', 'hive.csis.ul.ie');
    define('DB_USERNAME', '9119124');
    define('DB_PASSWORD', 'GMcpbtGp');
    define('DB_NAME', 'group05');
    // Attempt to connect to MySQL database
    $db_connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check connection
    if($db_connection === false){
        die("ERROR: Problem connecting to database, error details: " . mysqli_connect_error());
    }