<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/crud.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/header.php';
session_start();
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
$description = $result['content'];
$author = $result['author'];
$time = $result['created_at'];

echo '<div class="w3-container w3-sand w3-card-4">';

echo "<h3>$title</h3>";
echo '<div class="w3-panel w3-leftbar w3-rightbar w3-border w3-sand w3-card-4">';
echo "$description<br>";
echo '<div class="w3-text-grey">';
echo 'Posted by: ' . $author . '<br>';
echo "$time</div>";
?>


<?php
if (isset($_SESSION['email'])) { ?>
    <div class="w3-text-green"><a href="<?= $url_path ?>edit.php?id=<?php echo $row[
    'id'
]; ?>">[Edit]</a></div>
    <div class="w3-text-red">
        <a href="<?= $url_path ?>del.php?id=<?php echo $row['id']; ?>"
           onclick="return confirm('Are you sure you want to delete this post?'); ">[Delete]</a></div>
    <?php }
echo '</div></div>';


include $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/footer.php';

