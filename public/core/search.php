<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/crud.php';
?>
<div class="container">
    <div class="panel">
        <div class="panel-default text-center p-4"><h2>Blog project.</h2></div>
    </div>
<?php
if (isset($_GET['q'])) {
    $q = $_GET['q'];
    $db = new Database();
    $db->select('posts', where: "title LIKE %$q% OR content LIKE %$q%");
    $result = $db->getResult();
    if ($db->numRows() < 1) {
        echo '<div class="alert alert-info" role="alert">Nothing found.</div>';
    } else {
        echo '<div class="alert alert-info" role="alert">Showing results for $q</div>';
        foreach ($db->getResult() as $row) {
            $id = htmlentities($row['id']);
            $title = htmlentities($row['title']);
            $content = htmlentities($row['content']);
            $author = htmlentities($row['author']);
            if (is_null($row['slug'])) {
                $slug = null;
            } else {
                $slug = htmlentities($row['slug']);
            }
            if (is_null($row['img'])) {
                $img = null;
            } else {
                $img = $row['img'];
            }
            $created_at = htmlentities($row['created_at']);
            $updated_at = htmlentities($row['updated_at']);
            // if shug is null rout to id of post
            if (!is_null($slug)) {
                $permalink = '/' .'pslug/' . $id . '/' . $slug;
            } else {
                $permalink = '/pages/view_post.php?id=' . $id;
            }
            echo '<div class="card m-2">';
            echo "<h3 class='card-title text-center'><a class='link-dark text-decoration-none' href='$permalink'>$title</a></h3>";
            $sub_content = substr($content, 0, 100);
            echo "<h5 class='card-text ms-3'>$sub_content </h5>";

            if (!is_null($img)) {
                echo '<img src="data:image/jpeg;base64, ' .
                    base64_encode($img) .
                    '" class="card-img-top embed-responsive-item" alt="...">';
            }
            echo '<div class="card-text ms-3 mb-3">';
            echo "<a href='$permalink'>Read more...</a>";
            echo '</div>';

            echo "<p class='card-text ms-3'>Posted by <strong>$author </strong></p>";
            echo "<p class='card-text ms-3'>Created at $created_at </p>";
            echo "<p class='card-text ms-3'>Updated at $updated_at </p>";
            echo '</div>';
        }
    }
}

include $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/footer.php';

