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

if (is_null($row['img'])) {
    $img = null;
} else {
    $img = $row['img'];
}
$slug = $row['slug'];
if (!is_null($slug)) {
    $permalink = '/' . 'pslug/' . $id . '/' . $slug;
} else {
    $permalink = '/pages/view_post.php?id=' . $id;
}
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (strlen($_POST['slug']) > 0) {
        $slug = generate_slug($_POST['slug']);
    } else {
        $slug = null;
    }
    if (strlen($_FILES['img']['tmp_name']) > 0) {
        $handler = fopen($_FILES['img']['tmp_name'], 'r');
        $img = fread($handler, filesize($_FILES['img']['tmp_name']));
    } else {
        $img = null;
    }
    if (is_null($img)) {
        $db->update(
            'posts',
            params: [
                'title' => $title,
                'content' => $content,
                'slug' => $slug,
            ],
            where: "id=$id"
        );
    } else {
        $db->update(
            'posts',
            params: [
                'title' => $title,
                'content' => $content,
                'slug' => $slug,
                'img' => $img,
            ],
            where: "id=$id"
        );
    }
    if ($db->numRows() > 0) {
        echo '<meta http-equiv="refresh" content="0">';
    } else {
        echo '<div class="alert alert-info" role="alert">Failed to edit.</div>';
    }
}
?>

<div class="container">
    <div class="card m-2 p-3">
        <h2 class="fw-bold mb-2 text-center">Edit Post</h2>
        <h4 class="fw-bold mb-2 text-center"><a class="link-info" href="<?= $permalink ?>">Goto post</a> </h4>

        <form  method="POST" class="mb-3 mt-md-4"  enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="mb-3">
                <label class='form-label'>Title</label>
                <input type="text" class="form-control" name="title" value="<?php echo $title; ?>" required>
            </div>

            <div class="mb-3">
                <label  class='form-label'>Description</label>
                <textarea id = "description" rows="15"  class="form-control" name="content" required><?php echo $content; ?></textarea>
            </div>
            <div class="mb-3">
                <?php if ($img != null) {
                    echo '<img src="data:image/jpeg;base64, ' .
                        base64_encode($img) .
                        '" class="card-img-top" alt="...">';
                } ?>
                <label  class='form-label'>Plesase choose jpeg file to upload</label>
                <input type="file" class='form-control' name="img" id="img" accept="image/jpeg">
            </div>

            <div class="mb-3">
                <label class='form-label'>Slug (SEO URL)</label>
                <input type="text" class="form-control" name="slug" value="<?php echo $slug; ?>">
            </div>

            <div class="mb-3">
                <input type="submit" class='btn btn-success ms-3' name="update" value="Save post">
                <a type="button" class="btn btn-danger ms-3" href="<?= $url_path ?>/core/del_post.php?id=<?php echo $id; ?>"
                   onclick="return confirm('Are you sure you want to delete this post?'); ">Delete Post</a></div>
            </div>
        </form>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/footer.php';
