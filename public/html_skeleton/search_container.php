<?php
if (!empty(SITE_ROOT)) {
    $url_path = '/' . SITE_ROOT . '/';
} else {
    $url_path = '/';
} ?>
<div class="container">
    <div class="row justify-content-center">
        <form action="<?= $url_path ?>core/search.php" method="GET" class="card card-sm">
            <div class="card-body row no-gutters align-items-center">
                <div class="col-auto">
                    <i class="fas fa-search h4 text-body"></i>
                    </div>
                <div class="col">
                    <input type="text" name="q" class="form-control form-control-lg form-control-borderless " placeholder="Search in posts" required>
                </div>
                <div class="col-auto">
                    <input type="submit" class="btn btn-primary" value="Search">

                </div>
            </div>
        </form>
    </div>
</div>