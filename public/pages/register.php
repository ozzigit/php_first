<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/crud.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/config.php';
echo '<h2 class="w3-container w3-teal">Login</h2>';

if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $passwd = $_POST['passwd'];
    $db = new Database();
    $db->select('users', where: "email=$email");

    $result = $db->getResult();
    $row_count = $db->numRows();
    if ($row_count == 0) {
        if (strlen($passwd) > PASSWD_MIN_LEN) {
            $db->insert('users', [
                'email' => $email,
                'passwd' => password_hash($passwd, PASSWORD_DEFAULT),
            ]);
            if ($db->getResult() > 0) {
                $_SESSION['email'] = $email;
                header('location: /');
            } else {
                echo "<div class='w3-panel w3-pale-red w3-display-container'>Err in data save.</div>";
            }
        } else {
            echo "<div class='w3-panel w3-pale-red w3-display-container'>Incorrect password (too short).</div>";
        }
    } else {
        echo "<div class='w3-panel w3-pale-red w3-display-container'>Incorrect username.</div>";
    }
}
?>

    <form action="" method="POST" class="w3-container w3-padding">
        <label>Username </label>
        <input type="text" name="email"  value="<?php if (
            isset($_POST['email'])
        ) {
            echo strip_tags($_POST['email']);
        } ?>" class="w3-input w3-border" required>
        <label>Password</label>
        <input type="password" name="passwd" class="w3-input w3-border" required>
        <p><input type="submit" name="register" value="Register" class="w3-btn w3-teal"></p>
    </form>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/footer.php';
