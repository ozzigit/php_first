<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/core/crud.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/security.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/functions.php';

$id = (int) $_GET['id'];
if ($id < 1) {
    header('location: index.php');
}

$db = new Database();
$db->select('posts', where: "id=$id");
$result = $db->getResult();
if ($db->numRows() == 0) {
    header('location: index.php');
}
$row = $result[0];
$id = $row['id'];
$title = $row['title'];
$content = $row['content'];
$slug = $row['slug'];

// if shug is null rout to id of post
if (!is_null($slug)) {
    $permalink = 'p/' . $id . '/' . $slug;
} else {
    $permalink = '/pages/view_post.php?id=' . $id;
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $slug = generate_slug($_POST['slug']);

    $db->update(
        'posts',
        params: [
            'title' => $title,
            'content' => $content,
            'slug' => $slug,
        ],
        where: "id=$id"
    );

    if ($db->numRows() > 0) {
        echo '<meta http-equiv="refresh" content="0">';
    } else {
        echo 'failed to edit.';
    }
}
?>

    <div class="w3-container">
    <div class="w3-card-4">

        <div class="w3-container w3-teal">
            <h2>Edit Post - </h2>
        </div>
            <h4 class="w3-container"><a href="<?= $permalink ?>">Goto post</a> </h4>

        <form action="" method="POST" class="w3-container">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <p>
                <label>Title</label>
                <input type="text" class="w3-input w3-border" name="title" value="<?php echo $title; ?>">
            <p>
            <p>
                <label>Description</label>
                <textarea class="w3-input w3-border" id="description" name="content"><?php echo $content; ?> </textarea>
            </p>
            <p>
                <label>Slug (SEO URL)</label>
                <input type="text" class="w3-input w3-border" name="slug" value="<?php echo $slug; ?>">
            </p>
            <p>
                <input type="submit" class="w3-btn w3-teal w3-round" name="update" value="Save post">
            </p>

            <p>
            <div class="w3-text-red">
                <a href="<?= $url_path ?>/core/del_post.php?id=<?php echo $id; ?>"
                   onclick="return confirm('Are you sure you want to delete this post?'); ">Delete Post</a></div>
            </p>
        </form>
    </div>
    </div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/footer.php';
