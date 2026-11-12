<?php
require 'includes/functions.php';
if (me()) redirect(home_url());
$error = '';
$email = '';
if (is_post()) {
    check_csrf();
    $email = strtolower(trim($_POST['email'] ?? ''));
    $user = row('SELECT * FROM users WHERE email = ?', [$email]);
    if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['uid'] = $user['id'];
        redirect($user['role'] === 'librarian' ? 'today.php' : 'my-books.php');
    }
    $error = 'The email or password is not right.';   // one message, so nobody can test which emails exist
}
$title = 'Log in';
$narrow = true;
require 'includes/header.php';
?>
<div class="auth">
<h1>Welcome back</h1>
<p class="lead">Log in to see your books and holds.</p>
<form method="post" class="card">
  <?= csrf_field() ?>
  <?php if ($error): ?><p class="error" id="login-general-error"><?= e($error) ?></p><?php endif; ?>
  <label>Email <input id="login-email" name="email" type="email" value="<?= e($email) ?>" required></label>
  <label>Password <input id="login-password" name="password" type="password" required></label>
  <button class="button">Log in</button>
</form>
<p class="after">New here? <a href="register.php">Join the library</a></p>
<p class="demo">Demo accounts: tharushi@demo.test (member) and ruwani@demo.test (librarian). Password: password123</p>
</div>
<?php require 'includes/footer.php'; ?>
