<?php
// 404.php - Apache sends every unknown address here (see .htaccess), so it shows the site's own Not found page.
require 'includes/functions.php';
$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . '/';   // the site folder, such as /library-management-system/
$asked = substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), strlen($base));
if (strpos($asked, '/') !== false) redirect($base . '404.php');   // a deeper address: go back to the site folder so the page's links and styles work
not_found();
