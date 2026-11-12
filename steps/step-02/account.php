<?php
require 'includes/functions.php';
if (!me()) { flash('Please log in first.', 'warn'); redirect('login.php'); }
$errors = [];
if (is_post()) {
    check_csrf();
    $current = $_POST['current'] ?? '';
    $new = $_POST['new'] ?? '';
    if (!password_verify($current, me()['password'])) $errors['current'] = 'That is not your current password.';
    if (strlen($new) < 8) $errors['new'] = 'Use at least 8 characters.';
    if ($new !== ($_POST['again'] ?? '')) $errors['again'] = 'The two passwords are not the same.';
    if (!$errors) {
        q('UPDATE users SET password = ? WHERE id = ?', [password_hash($new, PASSWORD_DEFAULT), me()['id']]);
        flash('Password changed.');
        redirect('account.php');
    }
}
$title = 'Account';
$narrow = true;
require 'includes/header.php';
?>
<div class="auth">
<h1>Your account</h1>
<p class="lead"><?= e(me()['name']) ?> · <?= e(me()['email']) ?><?= me()['card_no'] ? ' · Card ' . e(me()['card_no']) : ' · Librarian' ?></p>
<form method="post" class="card">
  <h2>Change password</h2>
  <?= csrf_field() ?>
  <label>Current password <input name="current" type="password" required></label><?= field_error($errors, 'current') ?>
  <label>New password <input name="new" type="password" required></label><?= field_error($errors, 'new') ?>
  <label>New password again <input name="again" type="password" required></label><?= field_error($errors, 'again') ?>
  <button class="button">Change password</button>
</form>
</div>
<?php require 'includes/footer.php'; ?>
