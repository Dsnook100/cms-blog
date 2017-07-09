<?php 
    $directoryURI = $_SERVER['REQUEST_URI'];
    $path = parse_url($directoryURI, PHP_URL_PATH);
    $components = explode('/', $path);
    $urlEnd = end($components);
?>
<div class="col-sm-2">
    <ul id="side-menu" class="nav nav-pills nav-stacked">
        <li class="<?php if ($urlEnd=="dashboard.php") {echo "active"; } else  {echo "noactive";}?>"><a href="dashboard.php"><span class="glyphicon glyphicon-th"></span>&nbsp;Dashboard</a></li>
        <li class="<?php if ($urlEnd=="addnewpost.php") {echo "active"; } else  {echo "noactive";}?>"><a href="addnewpost.php"><span class="glyphicon glyphicon-list-alt"></span>&nbsp;Add New Post</a></li>
        <li class="<?php if ($urlEnd=="categories.php") {echo "active"; } else  {echo "noactive";}?>"><a href="categories.php"><span class="glyphicon glyphicon-tags"></span>&nbsp;Categories</a></li>
        <li class="<?php if ($urlEnd=="admins.php") {echo "active"; } else  {echo "noactive";}?>"><a href="admins.php"><span class="glyphicon glyphicon-user"></span>&nbsp;Manage Admins</a></li>
        <li class="<?php if ($urlEnd=="comments.php") {echo "active"; } else  {echo "noactive";}?>"><a href="#"><span class="glyphicon glyphicon-comment"></span>&nbsp;Comments</a></li>
        <li><a href="blog.php" target="_blank"><span class="glyphicon glyphicon-equalizer"></span>&nbsp;Live Blog</a></li>
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Logout</a></li>

    </ul>
</div>