<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/crud.php';

if (isset($_GET['q'])) {
    $q = $_GET['q'];
    $db = new Database();
    // $sql = "SELECT * FROM posts WHERE title LIKE '%{$q}%' OR content LIKE '%{$q}%'";
    // $db->sql($sql);
    $db->select('posts', where: "title LIKE %$q% OR content LIKE %$q%");
    $result = $db->getResult();
    if ($db->numRows() < 1) {
        echo 'Nothing found.';
    } else {
        echo "<div class='w3-container w3-padding'>Showing results for $q</div>";
        foreach ($db->getResult() as $row) {
            $id = htmlentities($row['id']);
            $title = htmlentities($row['title']);
            $content = htmlentities($row['content']);

            if (is_null($row['slug'])) {
                $slug = null;
            } else {
                $img = htmlentities($row['slug']);
            }
            if (is_null($row['img'])) {
                $img = null;
            } else {
                $img = htmlentities($row['img']);
            }
            $created_at = htmlentities($row['created_at']);
            $updated_at = htmlentities($row['updated_at']);
            // if shug is null rout to id of post
            if (!is_null($slug)) {
                $permalink = 'p/' . $id . '/' . $slug;
            } else {
                $permalink = '/pages/view_post.php?id=' . $id;
            }
            echo '<div class="w3-panel w3-sand w3-card-4">';
            echo "<h3><a href='$permalink'>$title</a></h3><p>";

            echo substr($content, 0, 100);

            echo '<div class="w3-text-teal">';
            echo "<a href='$permalink'>Read more...</a></p>";

            echo '</div>';
            echo "<div class='w3-text-grey'>Created at $created_at </div>";
            echo "<div class='w3-text-grey'>Updated at $updated_at </div>";
            echo '</div>';
        }
    }
}

include $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/footer.php';
