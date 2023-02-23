<?php
if (!empty(SITE_ROOT)) {
    $url_path = '/' . SITE_ROOT . '/';
} else {
    $url_path = '/';
} ?>
<div class="w3-container">

    <form action="<?= $url_path ?>core/search.php" method="GET" class="w3-container">
        <p>
            <input type="text" name="q" class="w3-input w3-border" placeholder="Search for anything" required>
        </p>
        <p>
        <input type="submit" class="w3-btn w3-teal w3-round" value="Search">
        </p>
    </form>
</div>