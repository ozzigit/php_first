<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/crud.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/header.php';
?>
<div class="container">
    <div class="panel">
        <div class="panel-default text-center p-4"><h2>Blog project.</h2></div>
    </div>
<?php
$db = new Database();
$db->select('posts');
$numrows = $db->numRows();

$rowsperpage = PAGINATION;
$totalpages = ceil($numrows / $rowsperpage);
$page = 1;
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = (int) $_GET['page'];
}
if ($page > $totalpages) {
    $page = $totalpages;
}
if ($page < 1) {
    $page = 1;
}
$offset = ($page - 1) * $rowsperpage;
$db->select('posts', order: 'id', limit: $rowsperpage, offset: $offset);
if ($db->numRows() < 1) {
    echo '<div class="alert alert-info" role="alert">No post yet!</div>';
} else {
    $arr_results = $db->getResult();
    for ($i = 0; $i < count($arr_results); $i++) {
        $row = $arr_results[$i];
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
            $permalink = '/' . 'pslug/' . $id . '/' . $slug;
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
        if (isset($_SESSION['email'])) {
            if ($_SESSION['email'] == $author) {
                echo '<div class="d-flex justify-content-center m-4"> ';
                echo "<a type='button' class='btn btn-success ms-3' href=" .
                    $url_path .
                    "pages/edit_post.php?id=$id>Edit</a>";
                echo "<a type='button' class='btn btn-danger ms-3' href=" .
                    $url_path .
                    'core/del_post.php?id=' .
                    $id .
                    " onclick=\"return confirm('Are you sure you want to delete this post?');\">Delete</a>";
                echo '</div> ';
            }
        }
        echo '</div>';
    }
    //pagination
    echo '<nav aria-label="Page navigation">';
    echo '<ul class="pagination justify-content-center">';
    if ($page > 1) {
        echo '<li class="page-item">';
        echo "<a class='page-link' href='?page=1'>&laquo;</a>";
        echo '</li>';
        $prevpage = $page - 1;
        echo '<li class="page-item">';
        echo "<a class='page-link' href='?page=$prevpage'><</a>";
        echo '</li>';
    }
    for ($x = 0; $x <= $totalpages; $x++) {
        if ($x > 0 && $x <= $totalpages) {
            echo '<li class="page-item">';
            echo "<a class='page-link' href='?page=$x' >$x</a>";
            echo '</li>';
        }
    }
    if ($page != $totalpages) {
        $nextpage = $page + 1;
        // echo "<a href='?page=$nextpage' class='w3-button'>></a>";

        $nextpage = $page + 1;
        echo '<li class="page-item">';
        echo "<a class='page-link' href='?page=$nextpage'>></a>";
        echo '</li>';
        echo '<li class="page-item">';
        echo "<a class='page-link' href='?page=$totalpages' class='w3-btn'> <span aria-hidden='true'>&raquo;</span></a>";
        echo '</li>';
    }

    echo '</ul>';
    echo '</nav>';
}
echo '</div>';
include $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/footer.php';

