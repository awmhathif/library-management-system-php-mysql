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
function home_url() { return me() && me()['role'] === 'librarian' ? 'today.php' : 'my-books.php'; }
function not_found() {
    http_response_code(404);
    $title = 'Not found';
    require __DIR__ . '/header.php';
    echo '<h1>Not found</h1><p>There is nothing at this address. <a href="index.php">Go to the home page</a>.</p>';
    require __DIR__ . '/footer.php';
    exit;
}
// Logged out people go to Log in. The wrong role sees Not found.
function require_role($role) {
    if (!me()) { flash('Please log in first.', 'warn'); redirect('login.php'); }
    if (me()['role'] !== $role) not_found();
}


// ---------- checking what people type ----------
function clean_name($s) { return trim(preg_replace('/\s+/', ' ', (string)$s)); }
function check_person($name, $email, $password, $exclude_id = 0) {   // R1 and R2
    $errors = [];
    if (mb_strlen($name) < 2 || mb_strlen($name) > 80 || !preg_match('/^[\p{L}\p{M} .\'-]+$/u', $name)) {
        $errors['name'] = 'Enter 2 to 80 letters.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 120) {
        $errors['email'] = 'Enter a valid email address.';
    } elseif (val('SELECT COUNT(*) FROM users WHERE email = ? AND id <> ?', [$email, $exclude_id])) {
        $errors['email'] = 'That email is already used.';
    }
    if (strlen($password) < 8) $errors['password'] = 'Use at least 8 characters.';
    return $errors;
}
function next_card_no() {
    $n = (int)val("SELECT COALESCE(MAX(CAST(SUBSTRING(card_no, 4) AS UNSIGNED)), 1000) FROM users");
    return 'LM-' . ($n + 1);
}
// A title is shown to the public only while at least one copy is still in the library.
function copies_in_library($book_id) {
    return (int)val("SELECT COUNT(*) FROM copies WHERE book_id = ? AND status NOT IN ('lost', 'withdrawn')", [$book_id]);
}
function cover_class($category) { return 'cover-' . preg_replace('/[^a-z]/', '', strtolower($category)); }
// A book cover drawn in CSS: category colour, a spine edge, the title and the author. No images.
function cover($book, $size = '') {
    return '<span class="cover ' . $size . ' ' . cover_class($book['category']) . '" aria-hidden="true"><span class="ct">'
        . e($book['title']) . '</span><span class="ca">' . e($book['author']) . '</span></span>';
}
function nav_link($href, $text, $page) {   // the top bar link, marked when it is the page you are on
    return '<a href="' . $href . '"' . ($href === $page ? ' class="here" aria-current="page"' : '') . '>' . $text . '</a>';
}

