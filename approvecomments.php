<?php
    require_once("includes/DB.php");
    require_once("includes/sessions.php");
    require_once("includes/functions.php");
?>

<?php
    if(isset($_GET["id"])) {
        $urlID = $_GET["id"];
    }

    global $connectToDb;

    $query = "UPDATE comments SET status='approved' WHERE id='$urlID'";
    $execute = mysqli_query($connection, $query);

    if($execute) {
        $_SESSION["SuccessMessage"] = "Comment has been approved.";
        redirectTo("comments.php");
    } else {
        $_SESSION["ErrorMessage"] = "Something went wrong. Please try again.";
        redirectTo("comments.php");
    }

?>