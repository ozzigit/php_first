<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/crud.php';
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $slug = null;

    $author = $_SESSION['email'];

    $db = new Database();
    $db->insert('posts', [
        'title' => $title,
        'content' => $content,
        'author' => $author,
    ]);

    $id = array_values($db->getResult())[0];
    if ($db->numRows() == 0) {
        die('failed to post' . mysqli_connect_error());
    }
    if (!is_null($slug)) {
        $permalink = 'p/' . $id . '/' . $slug;
    } else {
        $permalink = '/pages/view_post.php?id=' . $id;
    }

    printf(
        "Posted successfully. <meta http-equiv='refresh' content='2; url=%s'/>",
        $permalink
    );
} else {
     ?>
    <div class="w3-container">
        <div class="w3-card-4">
            <div class="w3-container w3-teal">
                <h2>New Post</h2>
            </div>

            <form class="w3-container" method="POST">

                <p>
                    <label>Title</label>
                    <input type="text" class="w3-input w3-border" name="title" required>
                </p>

                <p>
                    <label>Description</label>
                    <textarea id = "description" row="30" cols="50" class="w3-input w3-border" name="content" required/></textarea>
                </p>
                <p>
                    <input type="submit" class="w3-btn w3-teal w3-round" name="submit" value="Post">
                </p>
            </form>

        </div>
    </div>

    <?php
}
include $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/footer.php';
