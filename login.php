<?php
    require_once("includes/DB.php");
    require_once("includes/sessions.php");
    require_once("includes/functions.php");
?>
<?php
    if(isset($_POST["Submit"])) {
        global $connection;
        $username = mysqli_real_escape_string($connection, $_POST['Username']);
        $password = mysqli_real_escape_string($connection, $_POST['Password']);

        if(empty($username) || empty($password)) {
            $_SESSION["ErrorMessage"] = "All fields must be filled out.";
            redirectTo("login.php");
        } else {
            $accountExists = loginAttempt($username, $password);
            $_SESSION["Username"] = $accountExists["username"];
            if ($accountExists){
                $_SESSION["SuccessMessage"] = "Welcome {$_SESSION["Username"]}";
                redirectTo("dashboard.php");
            } else {
                $_SESSION["ErrorMessage"] = "Invalid Username or Password.";
                redirectTo("login.php");
            }
        }
    }
?>
<!DOCTYPE>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/dashboard.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <link rel="icon" type="image/png" href="img/favicon.png">
        <style>
            body {
                background-color: #fff;
            }
            .col-sm-2 {
                margin-top:150px;
            }
        </style>
    </head>

    <body>

        <nav class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="blog.php">
                        <img src="img/brand.png" alt="" width=200; height=30; style="margin-top:-5px;">
                    </a>
                </div>
            </div> <!-- End of nav container -->
        </nav> <!-- End of nav -->

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-offset-5 col-sm-2">
                    <div> <?php echo message(); echo successMessage(); ?> </div>
                    <h1 style="text-align:center;">Admin Login</h1>
                    <div>
                        <form action="login.php" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <label for="username"><span class="fieldInfo">Username:</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                        <input class="form-control" type="text" name="Username" id="username" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password"><span class="fieldInfo">Password:</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                                        <input class="form-control" type="password" name="Password" id="password" placeholder="">
                                    </div>
                                </div>
                                <br>
                                <input class="btn btn-info btn-block" type="Submit" name="Submit" value="Login">
                                <br>
                            </fieldset>
                        </form>
                    </div>
    
                </div> <!-- End of main area -->
            </div> <!-- End of row -->
        </div> <!-- End of container -->
    </body>
</html>