<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/crud.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/config.php';
?>

<div class="d-flex justify-content-center align-items-center">
    <div class="container">
         <div class="row d-flex justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card bg-white shadow-lg">
                    <div class="card-body p-5">
<?php
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
                echo '<div class="alert alert-danger" role="alert">Err in data save.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">Incorrect password (too short).</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Incorrect username.</div>';
    }
}
?>
                        <form action="" method="POST" class="mb-3 mt-md-4">
                            <h2 class="fw-bold mb-2 text-center text-uppercase">Register</h2>
                            <div class="mb-3">
                                <label for="email" class="form-label ">Email</label>
                                <input type="text" name="email"  value="<?php if (
                                    isset($_POST['email'])
                                ) {
                                    echo strip_tags($_POST['email']);
                                } ?>" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label ">Password</label>
                                <input type="password" name="passwd" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <input type="submit" name="register" value="Register" class="btn btn-outline-dark">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/html_skeleton/footer.php';
