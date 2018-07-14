<?php
session_start();
// redirect to the logon screen if the user is not logged in
if ($_SESSION['user_logged_in'] == 0) {
    header("Location: Logon.php");
}
require_once 'database_config.php';


$user_id = $_SESSION['user_id'];
$matching_user_id = $_SESSION['matching_user_id'];
//echo "session user " . $user_id;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "I am a post";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Matchfind</title>
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
            /* Add a black background color to the top navigation */
            .topnav {
                background-color: #333;
                overflow: hidden;
            }

            /* Style the links inside the navigation bar */
            .topnav a {
                float: left;
                color: #F0F8FF;
                text-align: center;
                padding: 14px 16px;
                text-decoration: none;
                font-size: 17px;
            }

            /* Change the color of links on hover */
            .topnav a:hover {
                background-color: #ddd;
                color: grey;
            }

            /* Add a color to the active/current link */
            .topnav a.active {
                background-color: #A9A9A9;
                color: white;
            }

            /* Right-aligned section inside the top navigation */
            .topnav-right {
                float: right;
            }
            iv.first {
                opacity: 0.1;
                filter: alpha(opacity=10); 
            }
        </style>
    </head>
    <body>
        <div class="topnav">
            <a class="active">FIND YOUR MATCH</a>
            <a href="MeetingSpace.php">Home</a>
            <div class="topnav-right">
                <a href="index.php">About</a>
                <a href="logout.php">Log Out</a>
            </div>
        </div>
        <<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <!------ Include the above in your HEAD tag ---------->

        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3" >

                    <form method="post" name="challenge"  class="form-horizontal" role="form" action="#" onSubmit="return submitForm()" AUTOCOMPLETE = "off" >
                        <fieldset class="landscape_nomargin" style="min-width: 0;padding:    .35em .625em .75em!important;margin:0 2px;border: 2px solid silver!important;margin-bottom: 10em;background-color:lavender; opacity: .8;">
                            <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Perfect Match Filter</legend>

                            <div class="form-group">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="text-align: right;">
                                    <span style="color: red">*</span> <span style="font-size: 8pt;">mandatory fields</span>
                                </div>
                            </div>	
                            <div class="form-group" style="margin-bottom: 0px;">
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-1"></div><div class="col-sm-8 col-md-8 col-lg-7 col-xs-10 mobilePad" id="message10" style=" font-size: 10pt;padding-left: 0px;"></div>                      

                            </div>				
                            <div class="form-group">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" padding-top: 7px; text-align: left;">
                                    Gender Preference <span style="color: red">*</span> :</div>

                                <div class="col-sm-7 col-md-7 col-lg-6 col-xs-9 input-group mobilePad" style="font-weight:600;">

                                    <input style="border-radius: 4px" type="text"  class="form-control" name="match" id="match" >                   

                                </div>
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" padding-top: 7px; text-align: left;">
                                    Preferred Location <span style="color: red">*</span> :</div>

                                <div class="col-sm-7 col-md-7 col-lg-6 col-xs-9 input-group mobilePad" style="font-weight:600;">

                                    <input style="border-radius: 4px" type="text"  class="form-control" name="match" id="match" >                   

                                </div>
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" padding-top: 7 px; text-align: left;">
                                    Relationship Type <span style="color: red">*</span> :</div>

                                <div class="col-sm-7 col-md-7 col-lg-6 col-xs-9 input-group mobilePad" style="font-weight:600;">

                                    <input style="border-radius: 4px" type="email"  class="form-control" name="fill" id="fill">                   

                                </div>
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            </div> 

                            <div class="form-group" style="margin-bottom: 5px;">
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-1"></div><div class="col-sm-8 col-md-8 col-lg-7 col-xs-10 mobilePad" id="message8" style=" font-size: 10pt;padding-left: 0px;"></div>                      

                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-1"></div><div class="col-sm-8 col-md-8 col-lg-7 col-xs-10 mobilePad" id="message" style=" font-size: 10pt;"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-1"></div><div class="col-sm-8 col-md-8 col-lg-7 col-xs-10 mobilePad" id="message2" style=" font-size: 10pt;"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-1"></div><div class="col-sm-8 col-md-8 col-lg-7 col-xs-10 mobilePad" id="message3" style=" font-size: 10pt;"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-1"></div><div class="col-sm-8 col-md-8 col-lg-7 col-xs-10 mobilePad" id="message4" style=" font-size: 10pt;"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-1"></div><div class="col-sm-8 col-md-8 col-lg-7 col-xs-10 mobilePad" id="message5" style=" font-size: 10pt;"></div> 
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-1"></div><div class="col-sm-8 col-md-8 col-lg-7 col-xs-10 mobilePad" id="message6" style=" font-size: 10pt;padding-left: 0px;"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-1"></div><div class="col-sm-8 col-md-8 col-lg-7 col-xs-10 mobilePad" id="message7" style=" font-size: 10pt;padding-left: 0px;"></div>                      

                            </div>
                            <div class="form-group">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" padding-top: 7px;text-align: left;">
                                    Interest 1 
                                    <span style="color: red">*</span> :</div>

                                <div class="col-sm-7 col-md-7 col-lg-6 col-xs-9 input-group mobilePad">

                                    <input type="interest" name="interest" id="interest" class="form-control">


                                </div>
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                <div class="col-sm-4 col-md-4 col-lg-5 col-xs-10 mobileLabel" style=" padding-top: 7px;text-align: left;">
                                    Interest 2 
                                    <span style="color: red">*</span> :</div>

                                <div class="col-sm-7 col-md-7 col-lg-6 col-xs-9 input-group mobilePad">

                                    <input type="interest" name="interest" id="interest" class="form-control">
                                    <br>
                                    <br>
                                </div>
                            </div>




                            <div class="form-group">
                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                                <div class="col-sm-11 col-md-11 col-lg-11 col-xs-10" style="text-align:center;">
                                    <button id="valuser" type="button" onclick="submitForm()"
                                            class="btn btn-success">
                                        Submit</button>
                                </div>

                                <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            </div>   
                            <div class="form-group" style="text-align:center;font-weight:bold">

                        </fieldset>

                    </form>
                </div>
            </div>

        </div>

    </body>
</html>