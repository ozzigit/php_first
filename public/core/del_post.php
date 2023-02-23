<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/core/crud.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/security.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $db = new Database();
    $db->delete('posts', where: "id=$id");
    // $sql = "DELETE FROM posts WHERE id = '$id'";
    $result = $db->getResult();

    if ($result) {
        header('location: index.php');
    } else {
        echo 'Failed to delete.';
    }
}
