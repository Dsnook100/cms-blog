<?php
    require_once("includes/DB.php");
    require_once("includes/sessions.php");
    require_once("includes/functions.php");
?>
<!DOCTYPE>
<html>
    <head>
        <title>Blog</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/blog.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <link rel="icon" type="image/png" href="img/favicon.png">
    </head>
    <body>
        <?php include ("includes/nav.php"); ?>

        <div class="container">
            <div class="blog-header">
                
            </div>
            <div class="row">
                <div class="col-sm-9"> <!-- Main area -->
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
                            $viewQuery = "SELECT * FROM admin_panel ORDER BY datetime desc";
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

                    <div class="blogpost">
                        <a href="fullpost.php?id=<?php echo $postID ?>"><h3 id="heading"><?php echo htmlentities($title); ?></h3></a>
                        <div class="desc-info">
                            <span class="description"><span class="glyphicon glyphicon-user"></span><?php echo $author; ?></span>
                            <span class="description">
                                <span class="glyphicon glyphicon-time"></span>
                                <?php 
                                    if(strlen($dateTime)>12) {
                                        $dateTime = substr($dateTime,0,12);
                                    }
                                    echo htmlentities($dateTime); 
                                ?>
                            </span>

                            <span class="description"><span class="glyphicon glyphicon-folder-close"></span><?php echo htmlentities($category); ?></span>
                        </div>
                        <?php
                            if($image) {
                                echo '<div class="col-md-4 img-container"><img class="img-responsive img-rounded" src="uploads/';
                                echo $image;
                                echo '" alt="image"></div>';
                            }
                        ?>
            

                        <div <?php if($image) { echo 'class="caption col-md-8"'; } else {  echo 'class="caption col-md-12"'; } ?>>
                            <p class="post">
                                <?php 
                                    if(strlen($post) > 250) {
                                        $post = substr($post, 0, 250).'...';
                                    }
                                    echo $post;
                                ?>
                            </p>
                             <a href="fullpost.php?id=<?php echo $postID ?>">
                                <span class="btn btn-info">Read More &rsaquo;</span>
                            </a>
                        </div>
                       
                    </div>

                    <?php } ?>
                </div> <!-- End of main area -->
                <div class="col-sm-3"> <!-- Sidebar -->
                    <h2>Test Header</h2>
                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus.</p>
                </div> <!-- End of sidebar -->
            </div> <!-- End of row -->
        </div> <!-- End of main area container -->

        <div id="footer">
            <hr><p>Example Blog with CMS - &copy; 2017 Douglas Snook</p>
            <a style="color:white;text-decoration:none;cursor:pointer;font-weight:bold;" href="https://dougsnook.com"></a>
            <p>This is just an example site.</p>
            <hr>
        </div>
        
    </body>
</html>