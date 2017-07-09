<?php
    require_once("includes/sessions.php");
    require_once("includes/functions.php");

    $_SESSION["Username"] = null;
    session_destroy();
    redirectTo("login.php");

?>