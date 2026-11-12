<?php
// includes/header.php - top of every page. Set $title before including. Set $narrow = true for a small centred page (log in, sign up).
$user = me();
$role = $user['role'] ?? '';
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$page = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'Library Management System') ?> | Library Management System</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="top">
  <div class="wrap topin">
    <a class="brand" href="index.php"><span class="mark" aria-hidden="true"></span>Library Management System</a>
    <nav>
      <?php if ($role === 'librarian'): ?>
      <?php elseif ($role === 'member'): ?>
        <?= nav_link('my-books.php', 'My books', $page) ?>
      <?php endif; ?>
    </nav>
    <div class="who">
      <?php if ($user): ?>
        <?= nav_link('account.php', 'Account', $page) ?>
        <form method="post" action="logout.php" class="inline"><?= csrf_field() ?><button class="link">Log out</button></form>
      <?php else: ?>
        <?= nav_link('login.php', 'Log in', $page) ?>
        <a class="button small" href="register.php">Sign up</a>
      <?php endif; ?>
    </div>
  </div>
</header>
<main class="wrap<?= !empty($narrow) ? ' narrow' : '' ?>">
<?php if ($flash): ?><div class="flash <?= e($flash[0]) ?>" role="status"><?= e($flash[1]) ?></div><?php endif; ?>
