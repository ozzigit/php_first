<?php

$table_users = 'users';
$table_posts = 'posts';

$create_users_table_query = "CREATE TABLE $table_users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50) NOT NULL,
    passord VARCHAR(30) NOT NULL
);";

$create_posts_table_query = "CREATE TABLE $table_posts (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50),
    content VARCHAR(250) NOT NULL,
    img BLOB
);";

try {
    //check connection
    $conn = new PDO(
        "mysql:host=$servername;dbname=$dbname",
        $username,
        $password
    );

    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    //  echo '<br>Connected successfully';
    echo "<script>console.log('Connected successfully' );</script>";
    try {
        //check existing needed tables
        $result = $conn
            ->query("SELECT 1 FROM {$table_users} LIMIT 1")
            ->fetchAll();
        //  echo isset($result[0]) ? $result[0] : null;
        //  echo '<br>Table is present in db';
        echo "<script>console.log('All tables is present in db' );</script>";
    } catch (Exception $e) {
        // We got an exception (table not found)
        //  echo '<br>No exist table';
        echo "<script>console.log('No exist table users' );</script>";
        echo "<script>console.log('Creating table users...' );</script>";
        $conn->query($create_users_table_query)->fetchAll();
    }
    try {
        $result = $conn
            ->query("SELECT 1 FROM {$table_posts} LIMIT 1")
            ->fetchAll();
    } catch (Exception $e) {
        echo "<script>console.log('No exist table posts' );</script>";
        echo "<script>console.log('Creating table posts...' );</script>";
        $conn->query($create_posts_table_query)->fetchAll();
    }
} catch (PDOException $e) {
    //  echo '<br>Connection failed: ' . $e->getMessage();
    echo "<script>console.log('Connection failed' );</script>";
}

?>
