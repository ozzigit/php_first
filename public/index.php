<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/crud.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/header.php';

// $db = new Database();
/*  
$db->sql('SELECT * FROM users');
$db->sql("insert into users (email, passwd) values('ozi@ukr.net','password');");
$db->select('users');
$db->insert('users',array('email'=>'Nam','passwd'=>'pppppssssss'));
$db->delete('users','id=11');
$db->sql( 'SELECT * FROM users');
$db->sql( 'DESCRIBE users');
$db->update('users', ['email' => 'name4@email.com'], 'id=8');
$db->update('users',array('email'=>"name@email.com",'passwd'=>"com"),'id="83"');
*/

// $db->insert('users', ['email' => 'Na', 'passwd' => 'pppppssssss']);
// $res = $db->getResult();
// echo $db->numRows() . '<br/>';

// foreach ($res as $output) {
// echo json_encode($output) . '<br/>';
// }
// echo DB_HOST;
?>
<div class="w3-panel">
    <p>Blog project.</p>
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
    echo '<div class="w3-panel w3-pale-red w3-card-2 w3-border w3-round">No post yet!</div>';
} else {
    $arr_results = $db->getResult();
    for ($i = 0; $i < count($arr_results); $i++) {
        // foreach ($db->getResult() as $row) {
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
        echo '<div class="w3-panel w3-sand w3-card-4">';
        echo "<h3><a href='$permalink'>$title</a></h3><p>";

        echo substr($content, 0, 100);
        if (!is_null($img)) {
            echo '<img src="data:image/jpeg;base64, ' .
                base64_encode($img) .
                // base64_encode($img->load()) .
                '" class="card-img-top" alt="...">';
        }
        echo '<div class="w3-text-teal">';
        echo "<a href='$permalink'>Read more...</a></p>";
        echo '</div>';

        echo "<div class='w3-text-grey'>Posted by $author </div>";
        echo "<div class='w3-text-grey'>Created at $created_at </div>";
        echo "<div class='w3-text-grey'>Updated at $updated_at </div>";
        echo '</div>';
    }
    echo "<p><div class='w3-bar w3-center'>";

    if ($page > 1) {
        echo "<a href='?page=1'>&laquo;</a>";
        $prevpage = $page - 1;
        echo "<a href='?page=$prevpage' class='w3-btn'><</a>";
    }
    $range = 5;
    for ($x = $page - $range; $x < $page + $range + 1; $x++) {
        if ($x > 0 && $x <= $totalpages) {
            if ($x == $page) {
                echo "<div class='w3-teal w3-button'>$x</div>";
            } else {
                echo "<a href='?page=$x' class='w3-button w3-border'>$x</a>";
            }
        }
    }
    if ($page != $totalpages) {
        $nextpage = $page + 1;
        echo "<a href='?page=$nextpage' class='w3-button'>></a>";
        echo "<a href='?page=$totalpages' class='w3-btn'>&raquo;</a>";
    }
    echo '</div></p>';
}
include $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/footer.php';

