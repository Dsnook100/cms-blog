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

        <div class="container main-area">
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
                        } else if(isset($_GET["category"])) {
                            $category = $_GET["category"];
                            $viewQuery = "SELECT * FROM admin_panel
                                          WHERE category LIKE '%$category%' ";

                        } else if (isset($_GET["page"])) {
                            $page = $_GET["page"];

                            if($page <= 0) {
                                $showPost = 0;
                            } else {
                                $showPost = ($page * 3) - 3;  
                            }

                            $viewQuery = "SELECT * FROM admin_panel ORDER BY datetime desc LIMIT $showPost,3";

                        } else {
                            $page = 1;
                            
                            if($page <= 0) {
                                $showPost = 0;
                            } else {
                                $showPost = ($page * 3) - 3;  
                            }

                            $viewQuery = "SELECT * FROM admin_panel ORDER BY datetime desc LIMIT $showPost,3";
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
                                        $dateTime = substr($dateTime,0,strpos($dateTime, ' '));
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
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h2 class="panel-title">Categories</h2>
                        </div>
                        <div class="panel-body">
                            <?php
                                $connection;

                                $viewQuery = "SELECT * FROM category";
                                $execute = mysqli_query($connection, $viewQuery);
                                while($dataRows = mysqli_fetch_array($execute)) {
                                    $id = $dataRows['id'];
                                    $category = $dataRows['name'];
                            ?>
                            <a href="blog.php?category=<?php echo $category; ?>"><span id="heading" style="font-size:14px;"><?php echo $category ?></span></a><br>

                            <?php } ?>

                        </div>
                    </div>

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h2 class="panel-title">Recent Posts</h2>
                        </div>
                        <div class="panel-body">
                            <?php
                                $connection;
                                $viewQuery = "SELECT * FROM admin_panel ORDER BY datetime desc LIMIT 0,5";
                                $execute = mysqli_query($connection, $viewQuery);
                                while($dataRows = mysqli_fetch_array($execute)) {
                                    $id = $dataRows['id'];
                                    $title = $dataRows['title'];
                                    $datetime = $dataRows['datetime'];
                            ?>
                            <a href="fullpost.php?id=<?php echo $id; ?>"><p id="heading" style="font-size:14px;"><?php echo htmlentities($title); ?></p></a>

                            <?php } ?>
                        </div>
                    </div>
                </div> <!-- End of sidebar -->
            </div> <!-- End of row -->
        </div> <!-- End of main area container -->


        <nav style="text-align: center;">
                <ul class="pagination pagination-lg">
                    <?php
                        if(isset($page)) {
                            if($page > 1) {
                    ?>
                        <li><a href="blog.php?page=<?php echo $page - 1; ?>">&lsaquo;</a></li>
                    <?php 
                            } 
                        } 
                    ?>

                    <?php 
                        global $connection;

                        $queryPagination = "SELECT COUNT(*) FROM admin_panel";
                        $execute = mysqli_query($connection, $queryPagination);
                        $row = mysqli_fetch_array($execute);
                        $totalPosts = array_shift($row);

                        $pages = ceil($totalPosts / 3);

                        for($i=1; $i <= $pages; $i++) {
                            if(isset($page)) {    
                                if($i == $page) {
                    ?>
                    
                        <li class="active"><a href="blog.php?page=<?php echo $i ?>"><?php echo $i ?></a></li>  

                    <?php
                                } else {
                    ?>

                        <li><a href="blog.php?page=<?php echo $i ?>"><?php echo $i ?></a></li>
                    
                    <?php 
                                } 
                            }
                        }
                        if(isset($page)) {
                            if($page < $pages) {
                    ?>
                        <li><a href="blog.php?page=<?php echo $page + 1; ?>">&rsaquo;</a></li>
                    <?php 
                            } 
                        } 
                    ?>
                </ul>
            </nav>
        <div id="footer">
            <hr><p>Example Blog with CMS - &copy; 2017 Douglas Snook</p>
            <a style="color:white;text-decoration:none;cursor:pointer;font-weight:bold;" href="https://dougsnook.com"></a>
            <p>This is just an example site.</p>
            <hr>
        </div>
        
    </body>
</html>