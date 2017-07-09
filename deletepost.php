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

        $image = $_FILES["Image"]['name'];
        $storeFile = "uploads/". basename($_FILES["Image"]['name']);

        
        global $connectToDB;
        $deleteID = $_GET["delete"];
        $query = "DELETE FROM admin_panel WHERE id='$deleteID'";
        $execute = mysqli_query($connection, $query);
        
        move_uploaded_file($_FILES["Image"]["tmp_name"],$storeFile);
        if($execute) {
            $_SESSION["SuccessMessage"] = "Post deleted successfully.";
            redirectTo("dashboard.php");
        } else {
            $_SESSION["ErrorMessage"] = "Something went wrong. Please try again!";
            redirectTo("dashboard.php");
        }
        
    }
?>
<!DOCTYPE>
<html>
    <head>
        <title>Delete Post</title>
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
                    <h1 style="display: inline-block">Delete Post</h1>
                    <a href="dashboard.php"><span class="glyphicon glyphicon-remove backtodash"></span></a>
                    <div> <?php echo message(); echo successMessage(); ?> </div>
                    <div>
                        <?php
                            global $connection;
                            global $connectToDB;
                            $searchQueryParam = $_GET["delete"];
                            $query = "SELECT * FROM admin_panel WHERE id='$searchQueryParam'";
                            $execute = mysqli_query($connection,$query);
                            while($dataRows = mysqli_fetch_array($execute)) {
                                $titleUpdate = $dataRows["title"];
                                $categoryUpdate = $dataRows["category"];
                                $imageUpdate = $dataRows["image"];
                                $postUpdate = $dataRows["post"];
                            }

                        ?>
                        <form action="deletepost.php?delete=<?php echo $searchQueryParam; ?>" method="post" enctype="multipart/form-data">
                            <fieldset>
                                <div class="form-group">
                                    <label for="title"><span class="fieldInfo">Title:</span></label>
                                    <input disabled value="<?php echo $titleUpdate ?>" class="form-control" type="text" name="Title" id="title" placeholder="Title">
                                </div>
                                <div class="form-group">
                                    <label for="categoryselect"><span class="fieldInfo">Category:</span></label>
                                    <select disabled class="form-control" id="categoryselect" name="Category">
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
                                    <label for="imageselect"><span class="fieldInfo">Select Image:</span></label>
                                    <input disabled class="form-control" type="file" name="Image" id="imageselect">
                                    <label for=""><span class="fieldInfo">Existing Image: </span></label>
                                    <?php
                                        if($imageUpdate) {
                                            echo '<img class="img-responsive" src="uploads/';
                                            echo $imageUpdate;
                                            echo '" alt="image" width="170px">';
                                        }
                                    ?>
                                    <br>
                                </div>
                                <div class="form-group">
                                    <label for="postarea"><span class="fieldInfo">Post:</span></label>
                                    <textarea disabled class="form-control" name="Post" id="postarea"><?php echo $postUpdate; ?></textarea>
                                </div>

                                <br>
                                <input class="btn btn-danger btn-block" type="Submit" name="Submit" value="Delete Post">
                                <br>
                            </fieldset>
                        </form>
                    </div>
                </div> <!-- End of main area -->
            </div> <!-- End of row -->
        </div> <!-- End of container -->
    </body>
</html>