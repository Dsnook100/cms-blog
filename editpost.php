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
        $previousImage = mysqli_real_escape_string($connection, $_POST['previousImage']);

        date_default_timezone_set('America/New_York');
        $currentTime = time();
        $dateTime = strftime("%m-%d-%Y %I:%M %p",$currentTime);

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
            $editID = $_GET["edit"];
            if($image || isset($_POST['deleteImage'])) {
                 $query = "UPDATE admin_panel SET datetime='$dateTime', title='$title', category='$category', author='$author', image='$image', post='$post' WHERE id='$editID'";
            } else {
                 $query = "UPDATE admin_panel SET datetime='$dateTime', title='$title', category='$category', author='$author', post='$post' WHERE id='$editID'";
            }

            $execute = mysqli_query($connection, $query);
            
            if($image){
                move_uploaded_file($_FILES["Image"]["tmp_name"],$storeFile);  
                unlink("uploads/".$previousImage);
            } else if(isset($_POST['deleteImage'])) {
                unlink("uploads/".$previousImage);
            }

            if($execute) {
                $_SESSION["SuccessMessage"] = "Post successfully updated.";
                redirectTo("dashboard.php");
            } else {
                $_SESSION["ErrorMessage"] = "Something went wrong. Please try again!";
                redirectTo("dashboard.php");
            }
        }
    }
?>
<!DOCTYPE>
<html>
    <head>
        <title>Edit Post</title>
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
                    <h1 style="display: inline-block">Update Post</h1>
                    <a href="dashboard.php"><span class="glyphicon glyphicon-remove backtodash"></span></a>
                    <div> <?php echo message(); echo successMessage(); ?> </div>
                    <div>
                        <?php
                            global $connection;
                            global $connectToDB;
                            $searchQueryParam = $_GET["edit"];
                            $query = "SELECT * FROM admin_panel WHERE id='$searchQueryParam'";
                            $execute = mysqli_query($connection,$query);
                            while($dataRows = mysqli_fetch_array($execute)) {
                                $titleUpdate = $dataRows["title"];
                                $categoryUpdate = $dataRows["category"];
                                $imageUpdate = $dataRows["image"];
                                $postUpdate = $dataRows["post"];
                            }

                        ?>
                        <form action="editpost.php?edit=<?php echo $searchQueryParam; ?>" method="post" enctype="multipart/form-data">
                            <fieldset>
                                <div class="form-group">
                                    <label for="title"><span class="fieldInfo">Title:</span></label>
                                    <input value="<?php echo $titleUpdate ?>" class="form-control" type="text" name="Title" id="title" placeholder="Title">
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
                                        <option <?php if ($categoryName == $categoryUpdate) echo ' selected="selected"'; ?>><?php echo $categoryName; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="imageselect"><span class="fieldInfo">Select Image (Image will be overridden):</span></label>
                                    <input class="form-control" type="file" name="Image" id="imageselect">
                                    <label for="" style="margin-top:15px;"><span class="fieldInfo">Existing Image: </span></label>
                                    <?php
                                        if($imageUpdate) {
                                            echo '<img class="img-responsive" src="uploads/';
                                            echo $imageUpdate;
                                            echo '" alt="image" width="170px">';

                                            echo '<div class="checkbox" style="margin-top:15px;">';
                                            echo '<label style="font-weight:700;"><input type="checkbox" value="'.$imageUpdate.'">Delete existing image</label>';
                                            echo '<input type="hidden" name="previousImage" value="'.$imageUpdate.'">';
                                            echo '</div>';
                                        }
                                    ?>
                                    
                                    <br>
                                </div>
                                <div class="form-group">
                                    <label for="postarea"><span class="fieldInfo">Post:</span></label>
                                    <textarea class="form-control" name="Post" id="postarea"><?php echo $postUpdate; ?></textarea>
                                </div>

                                <br>
                                <input class="btn btn-success btn-block" type="Submit" name="Submit" value="Update Post">
                                <br>
                            </fieldset>
                        </form>
                    </div>
                </div> <!-- End of main area -->
            </div> <!-- End of row -->
        </div> <!-- End of container -->
    </body>
</html>