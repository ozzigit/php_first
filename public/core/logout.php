<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('location: /');
} else {
    session_destroy();
    header('location: /');
}
