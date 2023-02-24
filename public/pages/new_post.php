<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/crud.php';
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $slug = $_POST['slug'];
    $author = $_SESSION['email'];
    if (strlen($_FILES['img']['tmp_name']) > 0) {
        $handler = fopen($_FILES['img']['tmp_name'], 'r');
        $img = fread($handler, filesize($_FILES['img']['tmp_name']));
    } else {
        $img = null;
    }
    $db = new Database();
    $db->insert('posts', [
        'title' => $title,
        'content' => $content,
        'author' => $author,
        'slug' => $slug,
        'img' => $img,
    ]);

    if ($db->numRows() == 0) {
        die('failed to post');
    }
    $id = array_values($db->getResult())[0];
    if (strlen($slug) > 0) {
        $permalink = '/' . 'pslug/' . $id . '/' . $slug;
    } else {
        $permalink = '/pages/view_post.php?id=' . $id;
    }

    printf(
        "<div class='container alert alert-info' >Posted successfully. 
        <meta http-equiv='refresh' content='2; url=%s'/></div>",
        $permalink
    );
} else {
     ?>
    <div class="container">
        <div class="card m-2 p-3">
            <h2 class='fw-bold mb-2 text-center'>New Post</h2>
            <form class="mb-3 mt-md-4" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class='form-label'>Title</label>
                    <input type="text" class="form-control" name="title" required>
                </div>
                <div class="mb-3">
                    <label  class='form-label'>Description</label>
                    <textarea id = "description" rows="15"  class="form-control" name="content" required></textarea>
                </div>
                <div class="mb-3">
                    <label  class='form-label'>Plesase choose jpeg file to upload</label>
                    <input type="file" class='form-control' name="img" id="img" accept="image/jpeg">
                </div>
                <div class="mb-3">
                    <label class='form-label'>Slug (SEO URL)</label>
                    <input type="text" class="form-control" name="slug" value="">
                </div>
                <div class="mb-3">
                    <input type="submit" class='btn btn-success ms-3' name="submit" value="Post">
                </div>
            </form>

        </div>
    </div>

    <?php
}
include $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/footer.php';
