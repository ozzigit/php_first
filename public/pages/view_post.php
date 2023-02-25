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
?>
<div class="container">
<div class="card m-2 p-3">

<h3 class='card-title text-center'><?php echo $title; ?></h3>
<?php
echo "<h5 class='card-text ms-3'>$content </h5>";

if (!is_null($img)) {
    echo '<img src="data:image/jpeg;base64, ' .
        base64_encode($img) .
        '" class="card-img-top embed-responsive-item" alt="...">';
}
?>
<div class="w3-text-grey">

        <p class='card-text ms-3'>Posted by <strong><?php echo $author; ?></strong></p>
        <p class='card-text ms-3'>Created at <?php echo $created_at; ?> </p>
        <p class='card-text ms-3'>Updated at <?php echo $updated_at; ?> </p>
       </div>
</div>


<?php
if (isset($_SESSION['email']))  {
   if ($_SESSION['email']== $author ){?>

    <div class="d-flex justify-content-center m-4">
        <a type="button" class="btn btn-success ms-3" href=<?= $url_path ?>pages/edit_post.php?id=<?php echo $id; ?>"> Edit </a>
        <a type="button" class="btn btn-danger ms-3" href=<?= $url_path ?>core/del_post.php?id=<?php echo $id; ?>" onclick="return confirm('Are you sure you want to delete this post?'); ">Delete</a>
    </div>
    <?php }}
echo '</div></div>';

include $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/footer.php';

