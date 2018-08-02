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

$message = "";
$msg = "";
$match_id = $_SESSION['match_id'];
$matching_user_id = $_SESSION['matching_user_id'];

$lastCommunicationId = 0;
$first_name = "";
$surname = "";
$sql = "SELECT first_name, surname FROM user_profile where id = " . $user_id . ";";
//echo $sql;
$result = execute_sql_query($db_connection, $sql);
if ($result == null) {
    echo "ERROR: Cannot find profile for user id " . $user_id;
} else {
    while ($row = mysqli_fetch_array($result)) {
        $first_name = $row['first_name'];
        $surname = $row['surname'];
    }
}
$ChattingWith = "";
$sql = "SELECT first_name, surname FROM user_profile where id = " . $matching_user_id . ";";
//echo $sql;
$result = execute_sql_query($db_connection, $sql);
if ($result == null) {
    echo "ERROR: Cannot find profile for user id " . $user_id;
} else {
    while ($row = mysqli_fetch_array($result)) {
        $ChattingWith = $row['first_name'] . " " . $row['surname'];
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['btnAction'] == "Send") { // 
        $last_communication_id = $_SESSION['last_communication_id'];
        $match_id = $_SESSION['match_id'];

        $msg = $_POST['msg'];

        $valid = true;
        if (strlen($msg) > 140) {
            $valid = false;
            $message = "You cannot post a message longer than 140 characters";
        }
        if (strlen($msg) == 0) {
            $valid = false;
            $message = "You must enter a message";
        }
        if ($valid = true) {
            $blackListed = 0;
            $blackListedDate = null;
            $badWordsResult = get_bad_words_in_text($db_connection, $msg);
            $blackListedWordId = 0;
            if ($badWordsResult != null) {
                foreach ($badWordsResult as $key => $word) {
                    $blackListed = 1;
                    $blackListedWordId = $key;
                    $blackListedDate = date("Y-m-d h:i:sa");
                    $msg = str_replace($badWordsResult, '<b>' . $word . '</b>', $msg);
                    $message = "We have recorded an instance of offensive language in your post, the word in question is <b>" . $word . "</b>. Please note the following <br>"
                            . "1) This message will not be transmitted to the receiving person"
                            . "<br>2) If we record more than 5 instances then we will suspend your account for 1 week";
                }
            }
            if ($blackListed == 1)
                $sql = "insert into user_communication(from_user_id, to_user_id, message, status_id, replying_to_communication_id, communication_datetime,"
                        . " black_listed, black_listed_date, black_listed_word_id)"
                        . " values('$user_id', '$matching_user_id', '$msg', '11', " . $last_communication_id . ", now(),1,now()," . $blackListedWordId . ")";
            else
                $sql = "insert into user_communication(from_user_id, to_user_id, message, status_id, replying_to_communication_id, communication_datetime)"
                        . " values('$user_id', '$matching_user_id', '$msg', '11', " . $last_communication_id . ", now())";

            //echo $sql . "<br>";
            $result = execute_sql_update($db_connection, $sql);
            $msg = "";
            // See if user needs to be blacklisted
            $sql = "select id, user_status_id, (select count(1) cnt from user_communication uc where uc.black_listed = true and uc.communication_datetime > up.user_status_date and from_user_id = " . $user_id . ") BadListChatCount"
                    . " from user_profile up where id = " . $user_id;
            echo $sql;
            if ($result = mysqli_query($db_connection, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        if ($row['BadListChatCount'] >= 5 || $row['user_status_id'] == 5) {
                            $sql = "update user_profile set user_status_id = 3, user_status_date = now(), suspended_until_date = DATE_ADD(now(), INTERVAL 7 DAY) where id = " . $user_id;
                            //echo $sql;
                            $result = execute_sql_update($db_connection, $sql);
                            $message = "You have used offensive language 5 times, your account has been suspended for one month. You will not be able to log onto this system again until your suspension is served!";
                            $_SESSION['session_hash'] = "";
                        }
                    }
                }
            }

            if ($last_communication_id == 0) {
                $sql = "select max(id) maxid from user_communication where from_user_id =" . $user_id;
                if ($result = mysqli_query($db_connection, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            $last_communication_id = $row['maxid'];
                        }
                    }
                }
                if ($last_communication_id > 0) {
                    $sql = "update match_table set communication_id = " . $last_communication_id . " where id = " . $match_id;
                    echo $sql . "<br>";
                    $result = execute_sql_update($db_connection, $sql);
                } else {
                    $message = "Problem updating the Last Communication Id, cannot find entries in communication table for user id " . $user_id;
                }
            }

            //   echo $result;
            //   header("Location: ChatLine.php");
        }
    }
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
        <link rel="stylesheet" href="StyleSheet.css">




        <style>
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
        <div class="topnav">
            <a class="active">CHAT LINE</a>
            <a href="MeetingSpace.php" title="Meeting Space">
<?php echo $first_name . " " . $surname ?>

            </a>
            <div class="topnav-right">
                <a href="UpdateProfile.php" title="Edit your User Profile"><img height="16" width="16"  src='http://hive.csis.ul.ie/4065/group05/images/Edit.png'/>Edit Profile</a>
                <a href="MatchFind.php" title="Find People"><img height="16" width="16"   src='http://hive.csis.ul.ie/4065/group05/images/Find.png'/>Match Finder</a>
                <a href="RemoveAccount.php" title="Remove your User Profile"><img height="16" width="16"  src='http://hive.csis.ul.ie/4065/group05/images/Delete.png'/>Remove Profile</a>
                <a href="Logout.php" title="Log out of the system"><img height="16" width="16"  src='http://hive.csis.ul.ie/4065/group05/images/Logoff.png'/>Logoff</a>
            </div>
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
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-3" >
                        <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .15em .625em .15em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: .5em;background-color:lavender; opacity: .8;">
                            <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Chatting With <?php echo $ChattingWith ?></legend>
                            <div class="form-group"></div>
<?php
$result = get_communications_thread($db_connection, $user_id, $matching_user_id);
if ($result == null)
    echo "no thread found";
else {
    while ($row = mysqli_fetch_array($result)) {
        if ($row['from_user_id'] != $user_id && $row['black_listed'] == 1)
            $showMessage = "<b>Message black listed, offensive language detected</b>";
        else
            $showMessage = $row['message'];
        if ($row['from_user_id'] == $user_id)
            echo "<div class='float-sm-left col-sm-6 container border border-primary rounded text-dark bg-success'>";
        else
            echo "<div class='float-sm-right col-sm-6 container border border-primary rounded text-dark bg-info'>";
        echo "<i>" . $row['communication_datetime'] . "</i><br>" . $showMessage . "<br>";
        echo "</div>";
        echo "<br>";
        $lastCommunicationId = $row['id'];
    }
}
$_SESSION['last_communication_id'] = $lastCommunicationId;
?>
                        </fieldset> 
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="challenge"  class="form-group" role="form" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >        
                            <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .35em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .8;">
                                <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Send Message</legend>                                
                                <textarea name = "msg" placeholder = "Type to send message...."
                                          class = "form-control"><?php echo $msg; ?></textarea><br>
<?php
if (strlen($message) > 0) {
    echo "<div class='alert alert-danger'>";
    echo "<p>" . $message . "</p>";
    echo "</div>";
}
?>                                          
                                <button name="btnAction" class="btn btn-success" type="submit" value="Send"><img height="24" width="24"  title="Chat" src='http://hive.csis.ul.ie/4065/group05/images/send.png'/>Send</button>
                                <button name="btnAction" class="btn btn-info" type="submit" value="Refresh"><img height="24" width="24"  title="Refresh" src='http://hive.csis.ul.ie/4065/group05/images/refresh.png'/>Refresh</button>
                            </fieldset>
                        </form>
                    </div>
                </div>

            </div>            
        </div>
    </body>
</html> 
