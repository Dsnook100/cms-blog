<?php
    require_once("includes/DB.php");
    require_once("includes/sessions.php");
    require_once("includes/functions.php");
    confirmLogin();
?>
<?php
    if(isset($_POST["Submit"])) {
        global $connection;
        $username = mysqli_real_escape_string($connection, $_POST['Username']);
        $password = mysqli_real_escape_string($connection, $_POST['Password']);
        $confirmPass = mysqli_real_escape_string($connection, $_POST['ConfirmPass']);

        date_default_timezone_set('America/New_York');
        $currentTime = time();
        $dateTime = strftime("%B-%d-%Y %H:%M:%S",$currentTime);

        $author = $_SESSION["Username"];

        if(empty($username) || empty($password) || empty($confirmPass)) {
            $_SESSION["ErrorMessage"] = "All fields must be filled out.";
            redirectTo("admins.php");
        } elseif(strlen($password) < 6) {
            $_SESSION["ErrorMessage"] = "Password must be at least 8 characters long.";
            redirectTo("admins.php");
        } elseif($password !== $confirmPass) {
            $_SESSION["ErrorMessage"] = "Passwords must match.";
            redirectTo("admins.php");
        } else {
            global $connectToDB;
            $password = password_hash(mysqli_real_escape_string($connection, $_POST['Password']), PASSWORD_BCRYPT);
            $query = "INSERT INTO registration (datetime,username,addedby,password) VALUES ('$dateTime','$username','$author','$password')";
            $execute = mysqli_query($connection, $query);

            if($execute) {
                $_SESSION["SuccessMessage"] = "Admin added successfully.";
                redirectTo("admins.php");
            } else {
                $_SESSION["ErrorMessage"] = "Failed to add admin. Please try again.";
                redirectTo("admins.php");
            }
        }
    }
?>
<!DOCTYPE>
<html>
    <head>
        <title>Manage Admins</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/dashboard.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <link rel="icon" type="image/png" href="img/favicon.png">
    </head>

    <body>
        <?php include ("includes/nav.php"); ?>
        
        <div class="container-fluid">
            <div class="row">

                <?php include ("includes/dashboardnav.php"); ?>
                
                <div class="col-sm-10">
                    <h1>Manage Admins</h1>
                    <div> <?php echo message(); echo successMessage(); ?> </div>
                    <div>
                        <form action="admins.php" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <label for="username"><span class="fieldInfo">Username:</span></label>
                                    <input class="form-control" type="text" name="Username" id="username" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="password"><span class="fieldInfo">Password:</span></label>
                                    <input class="form-control" type="password" name="Password" id="password" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="confirmpass"><span class="fieldInfo">Confirm Password:</span></label>
                                    <input class="form-control" type="password" name="ConfirmPass" id="confirmpass" placeholder="">
                                </div>
                                <br>
                                <input class="btn btn-success btn-block" type="Submit" name="Submit" value="Add Admin">
                                <br>
                            </fieldset>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>ID</th>
                                <th>Date & Time</th>
                                <th>Admin Name</th>
                                <th>Added By</th>
                                <th>Action</th>
                            </tr>
                            <?php
                                global $connection;
                                global $connectToDB;
                                $viewQuery = "SELECT * FROM registration ORDER BY datetime desc";
                                $execute = mysqli_query($connection, $viewQuery);
                                $number = 0;

                                while($dataRows = mysqli_fetch_array($execute)) {
                                    $id = $dataRows["id"];
                                    $dateTime = $dataRows["datetime"];
                                    $username = $dataRows["username"];
                                    $addedby = $dataRows["addedby"];
                                    $number++;
                                
                            ?>

                            <tr>
                                <td><?php echo $number; ?></td>
                                <td><?php echo $dateTime; ?></td>
                                <td><?php echo $username; ?></td>
                                <td><?php echo $addedby; ?></td>
                                <td><a href="deleteadmin.php?id=<?php echo $id; ?>"><span class="btn btn-danger">Delete</span></a></td>
                            </tr>  
                            <?php } ?>     
                        </table>
                    </div>
                </div> <!-- End of main area -->
            </div> <!-- End of row -->
        </div> <!-- End of container -->
    </body>
</html>