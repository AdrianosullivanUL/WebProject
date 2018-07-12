<!DOCTYPE html>
<html lang="en">
    <head>
        <title>register</title>
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
        <form action="UpdateProfile.php" method = "post">
            <div class="container">
                <div class="row">
                    <div class="container border border-primary rounded bg-light text-dark col-sm-6">
                        <h1>Register</h1>
                    </div>
                </div>

                <br>
                <div class="container border border-primary rounded bg-light text-dark col-sm-6">
                <div class="row">
                    <br>
                    <div class="col">email:</div>
                    <div class="col"><input type="text" name="email"></div>
                </div>
                <div class="row">
                    <br>
                    <div class="col">Password:</div>
                    <div class="col"><input type="password" name="password"></div>
                </div>                    

                    <div class="checkbox">
                        <label><input type="checkbox"> Remember me</label>
                    </div>
                    <button type="next" class="btn btn-success">Next</button>
                    </form>

                </div>

                </body>

                </html> </html> 