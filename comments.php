<?php
    require_once("includes/DB.php");
    require_once("includes/sessions.php");
    require_once("includes/functions.php");
    confirmLogin();
?>
<!DOCTYPE>
<html>
    <head>
        <title>Manage Comments</title>
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
                    <h1>Manage Unapproved Comments</h1>

                    <div class="table-responsive">
                        <table class="table table-stripped table-hover">
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Comment</th>
                                <th>Approve Comment</th>
                                <th>Delete Comment</th>
                                <th>Blog Post</th>
                            </tr>
                            <?php 
                                $connection;
                                $connectToDb;
                                $query = "SELECT * FROM comments WHERE status='waiting' ORDER BY datetime desc";
                                $execute = mysqli_query($connection, $query);
                                while($dataRows = mysqli_fetch_array($execute)){
                                    $commentID = $dataRows['id'];
                                    $commentDate = $dataRows['datetime'];
                                    $commentAuthor = $dataRows['name'];
                                    $comment = $dataRows['comment'];
                                    $commentPostId = $dataRows['admin_panel_id'];

                                    if(strlen($comment) > 15) {
                                        $comment = substr($comment, 0, 15). '...';
                                    }
                                    if(strlen($commentAuthor) > 10) {
                                        $commentAuthor = substr($commentAuthor, 0, 10). '...';
                                    }
                            ?>

                            <tr>
                                <td><?php echo htmlentities($commentAuthor); ?></td>
                                <td><?php echo htmlentities($commentDate); ?></td>
                                <td><?php echo htmlentities($comment); ?></td>
                                <td><a href="approvecomments.php?id=<?php echo $commentID ?>"><span class="btn btn-success">Approve</span></a></td>
                                <td><a href="deletecomments.php?id=<?php echo $commentID ?>"><span class="btn btn-danger">Delete</span></a></td>
                                <td><a href="fullpost.php?id=<?php echo $commentPostId ?>" target="_blank"><span class="btn btn-primary">View Article</span></a></td>
                            
                            </tr>

                            <?php } ?>
                        </table>    
                    </div>

                </div> <!-- End of main area -->
            </div> <!-- End of row -->
        </div> <!-- End of container -->
    </body>
</html>