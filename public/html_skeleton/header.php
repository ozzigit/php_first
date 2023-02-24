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
    <meta name="viewport" content="width=device-width" ,initial-scale=1">
    <title>PHP Blog</title>

    <link rel="stylesheet" type="text/css" href="https://www.w3schools.com/w3css/4/w3.css">

</head>
<body>

<header class="w3-container w3-teal">
    <h1>PHP Blog</h1>
</header>

<div class="w3-bar w3-border">
    <a href="/<?= SITE_ROOT ?>" class="w3-bar-item w3-button w3-pale-green">Home</a>
    <?php if (isset($_SESSION['email'])) {
        echo "<a href='" .
            $url_path .
            'pages/' .
            "new_post.php' class='w3-bar-item w3-btn'>New Post</a>";
        echo "<a href='" .
            $url_path .
            'core/' .
            "logout.php' class='w3-bar-item w3-btn'>Logout</a>";
        echo "<div class='w3-bar-item'> Logged as " . $_SESSION['email'] . '</div>';
    } else {
        echo "<a href='" .
            $url_path .
            'pages/' .
            "login.php' class='w3-bar-item w3-pale-red' >Login</a>";
        echo "<a href='" .
            $url_path .
            'pages/' .
            "register.php' class='w3-bar-item w3-pale-red' >Register</a>";
    } ?>
</div>
<?php if ($_SERVER['REQUEST_URI'] == '/') {
    include $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/search_container.php';
}
