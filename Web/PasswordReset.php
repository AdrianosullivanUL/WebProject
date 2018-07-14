<?PHP
require_once("./include/membersite_config.php");

$emailsent = false;
if (isset($_POST['submitted'])) {
    if ($fgmembersite->EmailResetPasswordLink()) {
        $fgmembersite->RedirectToURL("reset-pwd-link-sent.html");
        exit;
    }
}
?>
<head>
    <title>Password Reset</title>
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
        <a class="active">PASSWORD RESET</a>
        <a href="MeetingSpace.php">Home</a>
        <div class="topnav-right">
            <a href="UpdateProfile.php">About</a>
            <a href="#logout">Log In</a>
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
                        <legend style="border-bottom: none;width: inherit;padding:inherit;" class="legend">Password Reset</legend>

                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="text-align: right!important;">
                                <span style="color: red">*</span> <span style="font-size: 8pt;">mandatory fields</span>
                            </div>
                        </div>	
                        <div class="form-group" style="margin-bottom: 0px;">
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-1"></div><div class="col-sm-8 col-md-8 col-lg-7 col-xs-10 mobilePad" id="message10" style=" font-size: 10pt;padding-left: 0px;"></div>                      

                        </div>				
                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            <div class="col-sm-3 col-md-3 col-lg-4 col-xs-10 mobileLabel" style=" padding-top: 7px; text-align: right;">
                                Username <span style="color: red">*</span> :</div>

                            <div class="col-sm-7 col-md-7 col-lg-6 col-xs-9 input-group mobilePad" style="font-weight:600;">

                                <input style="border-radius: 4px" type="text"  class="form-control" name="username" id="username" >                   

                            </div>
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            <div class="col-sm-3 col-md-3 col-lg-4 col-xs-10 mobileLabel" style=" padding-top: 7px; text-align: right;">
                                Your Email <span style="color: red">*</span> :</div>

                            <div class="col-sm-7 col-md-7 col-lg-6 col-xs-9 input-group mobilePad" style="font-weight:600;">

                                <input style="border-radius: 4px!important;" type="email"  class="form-control" name="yourEmail" id="yourEmail">                   

                            </div>
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                        </div> 

                        <div class="form-group">
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-1"></div>
                            <div class="col-sm-8 col-md-8 col-lg-7 col-xs-10 mobilePad"  data-toggle="collapse" data-target="#passPolicy" style="font-weight: bold;font-size: 10pt;padding-left: 0px;color: black;cursor: pointer;text-decoration: underline;">Check Password Policy<span class="caret"></span>
                            </div>  
                        </div>
                        <div class="form-group" style="margin-bottom: 0px;">
                            <div class="col-sm-4 col-md-4 col-lg-5 col-xs-1"></div>
                            <div id="passPolicy" class="col-sm-8 col-md-8 col-lg-7 col-xs-10 collapse mobilePad" style="padding-right: 17px;">
                                <ul type="disc" style="padding-left: 0px;">
                                    <li>Your Password must have minimum 6 characters.</li>
                                    <li>Your Password must contain at least one number, one uppercase, lowercase & special character.</li>
                                    <li>Your Password must not contain your Username.</li>
                                    <li>Your Password must not contain Character or Number repetition.</li>

                                </ul> 
                            </div>
                        </div>   
                        <div class="form-group " style="margin-bottom: 5px;">
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                            <div class="col-sm-3 col-md-3 col-lg-4 col-xs-10 mobileLabel" style=" padding-top: 7px;text-align: right;">
                                Your Password <span style="color: red">*</span> :</div>

                            <div class="col-sm-7 col-md-7 col-lg-6 col-xs-9 input-group mobilePad">

                                <input type="password" onkeyup="passwordChecker()" name="password" id="password" class="form-control">
                                                 

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
                            <div class="col-sm-3 col-md-3 col-lg-4 col-xs-10 mobileLabel" style=" padding-top: 7px;text-align: right;">
                                Confirm Your Password 
                                <span style="color: red">*</span> :</div>

                            <div class="col-sm-7 col-md-7 col-lg-6 col-xs-9 input-group mobilePad">

                                <input type="password" name="verifypassword" id="verifypassword" class="form-control">
                                                   

                            </div>
                            <div class="col-sm-1 col-md-1 col-lg-1 col-xs-1"></div>
                        </div>	
                        <div class="form-group">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" id="message1" style="font-weight: bold; text-align: center;font-size: 10pt;">
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