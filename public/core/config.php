<?php
if (!defined('PAGINATION')) {
    define('PAGINATION', 5); // Pagination results per page
}
if (!defined('SITE_ROOT')) {
    define('SITE_ROOT', ''); // If installed on a sub-folder, replace the empty constant with the folder's name
}
if (!defined('DB_HOST')) {
    define('DB_HOST', 'first_php_db'); // Change as required
}
if (!defined('DB_USER')) {
    define('DB_USER', 'docker'); // Change as required
}
if (!defined('DB_PASS')) {
    define('DB_PASS', 'secret'); // Change as required
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'php_base'); // Change as required
}
if (!defined('PASSWD_MIN_LEN')) {
    define('PASSWD_MIN_LEN', 4); // Change as required
}
