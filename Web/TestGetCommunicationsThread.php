<?php
session_start();
require_once 'database_config.php';
include 'group05_library.php';
$user_id = $_SESSION['user_id'];
$matching_user_id = $_SESSION['matching_user_id'];


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<head></head>
<body>
    <?php
    // force user id's just for testing:
    $user_id = 24;
    $matching_user_id = 15;
    $result = get_communications_thread($db_connection, $user_id, $matching_user_id);
    if ($result == null)
        echo "no thread found";
    else {
        while ($row = mysqli_fetch_array($result)) {
            echo $row['id'] . " - " . $row['message'] . "<br>";
        }
    }
    ?>
</body>


