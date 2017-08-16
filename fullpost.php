<?php
    require_once("includes/DB.php");
    require_once("includes/sessions.php");
    require_once("includes/functions.php");
?>
<?php
    if(isset($_POST["Submit"])) {
        global $connection;
        $name = mysqli_real_escape_string($connection, $_POST['Name']);
        $email = mysqli_real_escape_string($connection, $_POST['Email']);
        $comment = mysqli_real_escape_string($connection, $_POST['Comment']);
        $postID = $_GET["id"];

        date_default_timezone_set('America/New_York');
        $currentTime = time();
        $dateTime = strftime("%B-%d-%Y %H:%M:%S",$currentTime);

        if(empty($name) || empty($email) || empty($comment)) {
            $_SESSION["ErrorMessage"] = "All fields must be filled out.";
        } elseif(strlen($comment) > 300) {
            $_SESSION["ErrorMessage"] = "Comments cannot be longer than 300 characters.";
        } else {
            global $connectToDB;
            $query = "INSERT INTO comments (datetime,name,email,comment,status,admin_panel_id) VALUES ('$dateTime','$name','$email','$comment','waiting','$postID')";
            $execute = mysqli_query($connection, $query);
            
            if($execute) {
                $_SESSION["SuccessMessage"] = "Comment submitted successfully.";
                redirectTo("fullpost.php?id={$postID}");
            } else {
                $_SESSION["ErrorMessage"] = "Something went wrong. Please try again!";
                redirectTo("fullpost.php?id={$postID}");
            }
        }
    }
?>
<!DOCTYPE>
<html>
    <head>
        <title>Blog Post</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/blog.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <link rel="icon" type="image/png" href="img/favicon.png">

        <style>
            .form-control {
                width:50%;
            }
        </style>
    </head>
    <body>
        <?php include ("includes/nav.php"); ?>

        <div class="container">
            <div class="blog-header">
    
            </div>
            
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8"> <!-- Main area -->
                    <div> <?php echo message(); echo successMessage(); ?> </div>
                    <?php
                        global $connection;
                        global $connectToDb;
                        if(isset($_GET["searchButton"])) {
                            $search = $_GET["Search"];
                            $viewQuery = "SELECT * FROM admin_panel
                                          WHERE datetime LIKE '%$search%' 
                                          OR title LIKE '%$search%' 
                                          OR category LIKE '%$search%'
                                          OR post LIKE '%$search%'";
                        } else {
                            $urlPostID = $_GET["id"];
                            $viewQuery = "SELECT * FROM admin_panel WHERE id = '$urlPostID' ORDER BY datetime desc";
                        }

                        $execute = mysqli_query($connection, $viewQuery);
                        while($dataRows = mysqli_fetch_array($execute)) {
                            $postID = $dataRows["id"];
                            $dateTime = $dataRows["datetime"];
                            $title = $dataRows["title"];
                            $category = $dataRows["category"];
                            $author = $dataRows["author"];
                            $image = $dataRows["image"];
                            $post = $dataRows["post"];

                    ?>

                    <div class="blogPost thumbnail">
                        <?php
                            if($image) {
                                echo '<img class="img-responsive img-rounded" src="uploads/';
                                echo $image;
                                echo '" alt="image">';
                            }
                        ?>
            

                        <div class="caption">
                            <h1 id="heading"><?php echo htmlentities($title); ?></h1>
                            <p class="description">Category: <?php echo htmlentities($category); ?></p>
                            <p class="description">Published on: <?php echo htmlentities($dateTime); ?></p>
                            <p class="post"><?php echo $post; ?> </p>
                        </div>
                    </div>

                    <?php } ?>
                    <span><h4>Comments</h4></span>

                    <?php 
                        $connectToDb;
                        $postID;
                        $getCommentsQuery = "SELECT * FROM comments WHERE admin_panel_id = '$postID';";
                        $execute = mysqli_query($connection, $getCommentsQuery);
                        while($dataRows = mysqli_fetch_array($execute)) {
                            $commentDate = $dataRows["datetime"];
                            $commentAge = floor((time() - strtotime($commentDate)) / (60 * 60 * 24));
                            $commentAuthor = $dataRows["name"];
                            $comment = $dataRows["comment"];
                        
                    ?>
                    <div class="blogComment">
                        <div>
                            <p class="commentAuthor"><?php echo $commentAuthor; ?></p>
                            <p class="commentAge"><?php if($commentAge > 1) { echo $commentAge . " days ago"; } else { echo " day ago"; } ?></p>
                        </div>
                        <div>
                            <p class="comment"><?php echo $comment; ?></p>
                        </div>
                    </div>

                    <?php } ?>
                    <span>Comment on this post!</span>
                    <div>
                        <form action="fullpost.php?id=<?php echo $postID ?>" method="post" enctype="multipart/form-data">
                            <fieldset>
                                <div class="form-group">
                                    <label for="name"><span class="fieldInfo">Name:</span></label>
                                    <input class="form-control" type="text" name="Name" id="name" placeholder="Name">
                                </div>
                                <div class="form-group">
                                    <label for="email"><span class="fieldInfo">Email:</span></label>
                                    <input class="form-control" type="email" name="Email" id="email" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <label for="commentarea"><span class="fieldInfo">Comment:</span></label>
                                    <textarea class="form-control" name="Comment" id="commentarea" style="width:75%;height:115px;"></textarea>
                                </div>

                                <br>
                                <input class="btn btn-primary" type="Submit" name="Submit" value="Submit">
                                <br>
                            </fieldset>
                        </form>
                    </div>
                </div> <!-- End of main area -->
            </div> <!-- End of row -->
        </div> <!-- End of main area container -->
    </body>
</html>