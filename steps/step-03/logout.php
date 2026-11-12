<?php
require 'includes/functions.php';
if (is_post()) {
    check_csrf();
    $_SESSION = [];
    session_destroy();
    session_start();
    flash('You are logged out.');
}
redirect('index.php');
