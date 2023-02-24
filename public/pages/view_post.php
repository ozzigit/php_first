<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/crud.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/header.php';
$id = (int) $_GET['id'];
if ($id < 1) {
    header("location: $url_path");
}
$db = new Database();
$db->select('posts', where: "id=$id");
$result = $db->getResult();

$invalid = $db->numRows();
if ($invalid == 0) {
    header("location: $url_path");
}
// if row exist in db get result(first) of query
$result = $result[0];

$id = $result['id'];
$title = $result['title'];
$content = $result['content'];
$author = $result['author'];
if (is_null($result['img'])) {
    $img = null;
} else {
    $img = $result['img'];
}
$slug = $result['slug'];
$created_at = $result['created_at'];
$updated_at = $result['updated_at'];

echo '<div class="w3-container w3-sand w3-card-4">';

echo "<h3>$title</h3>";
echo '<div class="w3-panel w3-leftbar w3-rightbar w3-border w3-sand w3-card-4">';
echo "$content<br>";

if (!is_null($img)) {
    echo '<img src="data:image/jpeg;base64, ' .
        base64_encode($img) .
        '" class="card-img-top" alt="...">';
}
echo '<div class="w3-text-grey">';
echo 'Posted by: ' . $author . '<br>';
echo "Created at $created_at<br>";
echo 'Updated at: ' . $updated_at . '<br>';
echo '</div>';
?>


<?php
if (isset($_SESSION['email'])) { ?>
    <div class="w3-text-green"><a href="<?= $url_path ?>pages/edit_post.php?id=<?php echo $id; ?>">[Edit]</a></div>
    <div class="w3-text-red">
        <a href="<?= $url_path ?>core/del_post.php?id=<?php echo $id; ?>"
           onclick="return confirm('Are you sure you want to delete this post?'); ">[Delete]</a></div>
    <?php }
echo '</div></div>';

include $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/footer.php';

