<?php
    require_once("includes/DB.php");
    require_once("includes/sessions.php");
?>

<?php
    function redirectTo($newLocation) {
        header("Location:". $newLocation);
        exit;
    }

    function loginAttempt($username, $password) {
        global $connection;
        global $connectToDB;
        $query = "SELECT * FROM registration WHERE username='$username'";

        $execute = mysqli_query($connection,$query);

        if($admin=mysqli_fetch_assoc($execute)) {
            if(password_verify($password, $admin['password'])) {
                return $admin;
            } else {
                return null;
            }  
        } else {
            return null;
        }
    }

    function login() {
        if(isset($_SESSION["Username"])) {
            return true;
        }
    }

    function confirmLogin() {
        if(!login()) {
            $_SESSION["ErrorMessage"] = "Login Required.";
            redirectTo("login.php");
        }
    }
?>