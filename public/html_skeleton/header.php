<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/crud.php';
if (!empty(SITE_ROOT)) {
    $url_path = '/' . SITE_ROOT . '/';
} else {
    $url_path = '/';
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width ,initial-scale=1">
    <meta http-equiv="X-UA-Compatble" content="IE=edge">
    <title>PHP Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

</head>
<body>

<header class="container">
    <h1 class="d-flex justify-content-center">PHP Blog</h1>

    <div class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item active">
                        <a href="/<?= SITE_ROOT ?>" class="nav-link"  >Home</a>
                    </li>
                    <?php if (isset($_SESSION['email'])) {
                        echo '<li class="nav-item">';
                        echo "<a href='" .
                            $url_path .
                            'pages/' .
                            "new_post.php' class='nav-link'>New Post</a>";
                        echo '</li>';
                        echo '<li class="nav-item">';
                        echo "<a href='" .
                            $url_path .
                            'core/' .
                            "logout.php' class='nav-link'>Logout</a>";
                        echo '</li>';
                        echo "<div class='nav-link disabled'><span class='d-flex justify-content-end'> Logged as " .
                            $_SESSION['email'] .
                            '</span> </div>';
                    } else {

                        echo '<li class="nav-item">';
                        echo "<a href='" .
                            $url_path .
                            'pages/' .
                            "login.php' class='nav-link' >Login</a>";
                        echo '</li>';
                        echo '<li class="nav-item">';
                        echo "<a href='" .
                            $url_path .
                            'pages/' .
                            "register.php' class='nav-link' >Register</a>";
                        echo '</li>';
                    } ?>
                </ul>
            </div>
        </div>
    </div>

</header>
<?php if ($_SERVER['REQUEST_URI'] == '/') {
    include $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/search_container.php';
}
