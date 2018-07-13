<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Personal Details</title>

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
        <div class="container">

            <form action="UpdateProfile2.php" method = "post">
                <?php
                $user_id = $_GET["userid"];

                require_once 'database_config.php';
                ?>
                <div class="container border border-primary rounded bg-light text-dark col-sm-6">
                    <h1>Personal Details</h1>
                </div>
                <div class="container border border-primary rounded bg-light text-dark col-sm-6">
                    <?php
                    $surname = "";
                   $sql = "SELECT * FROM user_profile where id =" . $user_id . ";";
               //    echo ("sql" . $sql);
                   if ($result = mysqli_query($db_connection, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                                if ($row['id'] == $user_id) {
                               //     echo ($row['first_name']);
                                                        echo('<div class="form-group">');
                      echo('<label for="nameInput">Name</label>');
                       echo('<input type="text" class="form-control" id="firstnameInput" placeholder="'.$row['first_name'].'">');
                       $surname = $row['first_name'];
                    //     echo ($row['first_name']);
                     //    echo('</input>');
                   echo('</div>');
                                }
                            }
                        }
                   }
                                    ?>
                    <div class="form-group">
                        <label for="nameInput">Name</label>
                        <input type="text" class="form-control" id="surnameInput" placeholder="<?php $surname ?>">
                    </div>

                    <div class="form-group">
                        <label for="dateOfBirthInput">Date of Birth</label>
                        <input type="date" class="form-control" id="dateOfBirthInput">
                    </div>
                    <div class="form-group">
                        <label for="genderInput">Gender</label>
                        <select class="form-control" id="genderInput">
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="genderSelection">Preferred Partner Gender</label>
                        <select class="form-control" id="genderSelection">
                            <option>Male</option>
                            <option>Female</option>
                            <option>Etc.</option>
                            <!-- More options to add -->
                            <!-- Change to connect to DB -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="locationSelection">Preferred Location</label>
                        <select class="form-control" id="locationSelection">
                            <option>Limerick</option>
                            <option>Cork</option>
                            <option>Etc.</option>
                            <!-- More options to add -->
                            <!-- Change to connect to DB -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="seekingAgeSelection">Seeking Age Profile</label>
                        <input type="range" min="1" max="100" value="50" class="slider" id="seekingAgeSelection">
                    </div>
                    <div class="form-group">
                        <label for="travelDistanceSelection">Distance I will travel</label>
                        <input type="range" min="1" max="100" value="50" class="slider" id="travelDistanceSelection">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-heart "></i> Love
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fab fa-cuttlefish "></i> Casual
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Friendship
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-friends"></i> Relationship
                    </button>
                    <b/>
                </div>
        </div>
        <p align="middle">



            <button type="next" class="btn btn-success">Next</button>
            <button type="Delete" class="btn btn-success">Delete</button>



        </div>

    </form>

</div>
</body>

</html>