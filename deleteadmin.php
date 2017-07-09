<?php
    require_once("includes/DB.php");
    require_once("includes/sessions.php");
    require_once("includes/functions.php");
    confirmLogin();
?>
<?php
    if(isset($_GET["id"])) {
        global $connection;
        global $connectToDB;
        $deleteID = $_GET["id"];
        $query = "DELETE FROM registration WHERE id='$deleteID'";
        $execute = mysqli_query($connection, $query);

        if($execute) {
            $_SESSION["SuccessMessage"] = "Admin deleted successfully.";
            redirectTo("admins.php");
        } else {
            $_SESSION["ErrorMessage"] = "Something went wrong. Please try again!";
            redirectTo("admins.php");
        }
        
    }
?>