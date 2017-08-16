<?php
    require_once("includes/DB.php");
    require_once("includes/sessions.php");
    require_once("includes/functions.php");
    confirmLogin();
?>
<!DOCTYPE>
<html>
    <head>
        <title>Admin Dashboard</title>
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
                    <div style="margin-top:10px;"> <?php echo message(); echo successMessage(); ?> </div>
                    <h1>Admin Dashboard</h1>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Post Title</th>
                                <th>Date Posted</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Image</th>
                                <th>Comments</th>
                                <th>Action</th>
                                <th>Details</th>
                            </tr>
                            <?php
                                global $connection;
                                global $connectToDb;
                                $viewQuery = "SELECT * FROM admin_panel ORDER BY datetime desc;";
                                $execute = mysqli_query($connection,$viewQuery);
                                while($dataRows = mysqli_fetch_array($execute)) {
                                    $id = $dataRows["id"];
                                    $dateTime = $dataRows["datetime"];
                                    $title = $dataRows["title"];
                                    $category = $dataRows["category"];
                                    $author = $dataRows["author"];
                                    $image = $dataRows["image"];
                                    $post = $dataRows["post"];
                            ?>

                            <tr>
                                <td>
                                    <?php 
                                        if(strlen($title)>20) {
                                            $title = substr($title,0,20)."..";
                                        }
                                        echo $title; 
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        if(strlen($dateTime)>12) {
                                            $dateTime = substr($dateTime,0,12);
                                        }
                                        echo $dateTime; 
                                    ?>
                                </td>
                                <td><?php echo $author; ?></td>
                                <td>
                                    <?php 
                                        if(strlen($category)>8) {
                                            $category = substr($category,0,8)."..";
                                        }
                                        echo $category; 
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        if($image) {
                                            echo '<img class="img-responsive img-rounded" src="uploads/';
                                            echo $image;
                                            echo '" alt="image" width="170px" height="50px">';
                                        }
                                    ?>
                                </td>
                                <td>Processing</td>
                                <td>
                                    <a href="editpost.php?edit=<?php echo $id; ?>"><span class="btn btn-warning">Edit</span></a>
                                    <a href="deletepost.php?delete=<?php echo $id; ?>"><span class="btn btn-danger">Delete</span></a>
                                </td>
                                <td><a href="fullpost.php?id=<?php echo $id; ?>" target="_blank"><span class="btn btn-primary">Live Preview</span></a></td>
                            </tr>

                            <?php } ?>
                        </table>
                    </div>

                </div> <!-- End of main area -->
            </div> <!-- End of row -->
        </div> <!-- End of container -->
    </body>
</html>