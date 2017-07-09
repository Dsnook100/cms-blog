<?php 
    $directoryURI = $_SERVER['REQUEST_URI'];
    $path = parse_url($directoryURI, PHP_URL_PATH);
    $components = explode('/', $path);
    $urlEnd = end($components);
?>


<nav class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="blog.php">
                <img src="img/brand.png" alt="" width=200; height=30; style="margin-top:-5px;">
            </a>
        </div>
        <div class="collapse navbar-collapse" id="collapse">
            <form action="blog.php" class="navbar-form navbar-right">
                <div class="form-group">
                    <input class="form-control" type="text" name="Search" placeholder="Search">
                </div>
                <button class="btn btn-default" name="searchButton">Go</button>
            </form>
        </div>
    </div> <!-- End of nav container -->
</nav> <!-- End of nav -->