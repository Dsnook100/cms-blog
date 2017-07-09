<?php
    require_once("includes/DB.php");
    require_once("includes/sessions.php");
    require_once("includes/functions.php");
    confirmLogin();
?>
<?php
    if(isset($_POST["Submit"])) {
        global $connection;
        $category = mysqli_real_escape_string($connection, $_POST['Category']);

        date_default_timezone_set('America/New_York');
        $currentTime = time();
        $dateTime = strftime("%B-%d-%Y %H:%M:%S",$currentTime);

        $author = $_SESSION["Username"];

        if(empty($category)) {
            $_SESSION["ErrorMessage"] = "All Fields must be filled out";
            redirectTo("categories.php");
        } elseif(strlen($category) > 99) {
            $_SESSION["ErrorMessage"] = "Category name is too long.";
            redirectTo("categories.php");
        } else {
            global $connectToDB;
            $query = "INSERT INTO category (datetime,name,creatorname) VALUES ('$dateTime','$category','$author')";
            $execute = mysqli_query($connection, $query);

            if($execute) {
                $_SESSION["SuccessMessage"] = "Category added successfully.";
                redirectTo("categories.php");
            } else {
                $_SESSION["ErrorMessage"] = "Category failed to add.";
                redirectTo("categories.php");
            }
        }
    }
?>
<!DOCTYPE>
<html>
    <head>
        <title>Categories</title>
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
                    <h1>Manage Categories</h1>
                    <div> <?php echo message(); echo successMessage(); ?> </div>
                    <div>
                        <form action="categories.php" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <label for="categoryname"><span class="fieldInfo">Name:</span></label>
                                    <input class="form-control" type="text" name="Category" id="categoryname" placeholder="Name">
                                </div>
                                <br>
                                <input class="btn btn-success btn-block" type="Submit" name="Submit" value="Add New Category">
                                <br>
                            </fieldset>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Number</th>
                                <th>Date & Time</th>
                                <th>Category Name</th>
                                <th>Creator Name</th>
                            </tr>
                            <?php
                                global $connection;
                                global $connectToDB;
                                $viewQuery = "SELECT * FROM category ORDER BY datetime desc";
                                $execute = mysqli_query($connection, $viewQuery);
                                $number = 0;

                                while($dataRows = mysqli_fetch_array($execute)) {
                                    $id = $dataRows["id"];
                                    $dateTime = $dataRows["datetime"];
                                    $categoryName = $dataRows["name"];
                                    $creatorName = $dataRows["creatorname"];
                                    $number++;
                                
                            ?>

                            <tr>
                                <td><?php echo $number; ?></td>
                                <td><?php echo $dateTime; ?></td>
                                <td><?php echo $categoryName; ?></td>
                                <td><?php echo $creatorName; ?></td>
                            </tr>  
                            <?php } ?>     
                        </table>
                    </div>
                </div> <!-- End of main area -->
            </div> <!-- End of row -->
        </div> <!-- End of container -->
    </body>
</html>