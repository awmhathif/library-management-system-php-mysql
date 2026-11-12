<?php
// includes/functions.php - every page starts by including this file.
session_start();
require __DIR__ . '/config.php';


// ---------- database ----------
function db() {
    static $pdo;
    if (!$pdo) {
        $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}
function q($sql, $p = []) { $s = db()->prepare($sql); $s->execute($p); return $s; }
function rows($sql, $p = []) { return q($sql, $p)->fetchAll(); }
function row($sql, $p = []) { return q($sql, $p)->fetch() ?: null; }
function val($sql, $p = []) { $v = q($sql, $p)->fetchColumn(); return $v === false ? null : $v; }

// ---------- small helpers ----------
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function redirect($url) { header('Location: ' . $url); exit; }
function today() { return date('Y-m-d'); }
function add_days($date, $n) { return date('Y-m-d', strtotime("$date +$n day")); }
function days_between($a, $b) { return (int)(new DateTime($a))->diff(new DateTime($b))->format('%r%a'); }
function nice_date($d) { return $d ? date('D j M', strtotime($d)) : ''; }
function plural($n, $word, $many = '') { return $n . ' ' . ($n == 1 ? $word : ($many ?: $word . 's')); }
function like($text) { return '%' . addcslashes($text, '%_\\') . '%'; }   // search text for LIKE: % and _ are matched as typed

function due_words($due) {
    $n = days_between(today(), $due);
    if ($n == 0) return 'Due today';
    if ($n == 1) return 'Due tomorrow';
    if ($n > 1)  return "Due in $n days";
    return $n == -1 ? '1 day late' : (-$n) . ' days late';
}

// ---------- messages, forms and safety ----------
function flash($text, $type = 'ok') { $_SESSION['flash'] = [$type, $text]; }
function csrf() { return $_SESSION['csrf'] ??= bin2hex(random_bytes(16)); }
function csrf_field() { return '<input type="hidden" name="csrf" value="' . csrf() . '">'; }
function is_post() { return $_SERVER['REQUEST_METHOD'] === 'POST'; }
function check_csrf() {
    if (!hash_equals(csrf(), $_POST['csrf'] ?? '')) { http_response_code(400); exit('Bad request.'); }
}
function field_error($errors, $name) { return isset($errors[$name]) ? '<p class="error">' . e($errors[$name]) . '</p>' : ''; }

// ---------- who is logged in ----------
function me() {
    static $user = false;
    if ($user === false) {
        $user = isset($_SESSION['uid']) ? row('SELECT * FROM users WHERE id = ?', [$_SESSION['uid']]) : null;
    }
    return $user;
}


function nav_link($href, $text, $page) {   // the top bar link, marked when it is the page you are on
    return '<a href="' . $href . '"' . ($href === $page ? ' class="here" aria-current="page"' : '') . '>' . $text . '</a>';
}

