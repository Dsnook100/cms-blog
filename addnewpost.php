<?php
    require_once("includes/DB.php");
    require_once("includes/sessions.php");
    require_once("includes/functions.php");
    confirmLogin();
?>
<?php
    if(isset($_POST["Submit"])) {
        global $connection;
        $title = mysqli_real_escape_string($connection, $_POST['Title']);
        $category = mysqli_real_escape_string($connection, $_POST['Category']);
        $post = mysqli_real_escape_string($connection, $_POST['Post']);

        date_default_timezone_set('America/New_York');
        $currentTime = time();
        $dateTime = strftime("%B-%d-%Y %H:%M:%S",$currentTime);

        $author = $_SESSION["Username"];
        $image = $_FILES["Image"]['name'];
        $storeFile = "uploads/". basename($_FILES["Image"]['name']);

        if(empty($title)) {
            $_SESSION["ErrorMessage"] = "Title can't be empty";
            redirectTo("addnewpost.php");
        } elseif(strlen($title) < 2) {
            $_SESSION["ErrorMessage"] = "Title should be at least 2 characters";
            redirectTo("addnewpost.php");
        } elseif(empty($post)) {
            $_SESSION["ErrorMessage"] = "Post needs to be filled out";
            redirectTo("addnewpost.php");
        } else {
            global $connectToDB;
            $query = "INSERT INTO admin_panel (datetime,title,category,author,image,post) VALUES ('$dateTime','$title','$category','$author','$image','$post')";
            $execute = mysqli_query($connection, $query);
            
            move_uploaded_file($_FILES["Image"]["tmp_name"],$storeFile);
            if($execute) {
                $_SESSION["SuccessMessage"] = "Post added successfully.";
                redirectTo("addnewpost.php");
            } else {
                $_SESSION["ErrorMessage"] = "Something went wrong. Please try again!";
                redirectTo("addnewpost.php");
            }
        }
    }
?>
<!DOCTYPE>
<html>
    <head>
        <title>Add New Post</title>
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
                    <h1>Add New Post</h1>
                    <div> <?php echo message(); echo successMessage(); ?> </div>
                    <div>
                        <form action="addnewpost.php" method="post" enctype="multipart/form-data">
                            <fieldset>
                                <div class="form-group">
                                    <label for="title"><span class="fieldInfo">Title:</span></label>
                                    <input class="form-control" type="text" name="Title" id="title" placeholder="Title">
                                </div>
                                <div class="form-group">
                                    <label for="categoryselect"><span class="fieldInfo">Category:</span></label>
                                    <select class="form-control" id="categoryselect" name="Category">
                                        <?php
                                            global $connection;
                                            global $connectToDB;
                                            $viewQuery = "SELECT * FROM category ORDER BY datetime desc";
                                            $execute = mysqli_query($connection, $viewQuery);

                                            while($dataRows = mysqli_fetch_array($execute)) {
                                                $id = $dataRows["id"];
                                                $categoryName = $dataRows["name"];
                                            
                                            
                                        ?>
                                        <option><?php echo $categoryName; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="imageselect"><span class="fieldInfo">Select Image:</span></label>
                                    <input class="form-control" type="file" name="Image" id="imageselect">
                                </div>
                                <div class="form-group">
                                    <label for="postarea"><span class="fieldInfo">Post:</span></label>
                                    <textarea class="form-control" name="Post" id="postarea"></textarea>
                                </div>

                                <br>
                                <input class="btn btn-success btn-block" type="Submit" name="Submit" value="Add New Post">
                                <br>
                            </fieldset>
                        </form>
                    </div>
                </div> <!-- End of main area -->
            </div> <!-- End of row -->
        </div> <!-- End of container -->
    </body>
</html>