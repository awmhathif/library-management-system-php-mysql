<?php
require 'includes/functions.php';
if (me()) redirect(home_url());
$errors = [];
$f = ['name' => '', 'email' => ''];
if (is_post()) {
    check_csrf();
    $f = ['name' => clean_name($_POST['name'] ?? ''), 'email' => strtolower(trim($_POST['email'] ?? ''))];
    $password = $_POST['password'] ?? '';
    $errors = check_person($f['name'], $f['email'], $password);
    if (!$errors) {
        // Sign up always makes a member. Librarians are added by a librarian (members.php).
        db()->beginTransaction();
        $card = next_card_no();
        q("INSERT INTO users (name, email, password, role, card_no, created_at) VALUES (?, ?, ?, 'member', ?, ?)",
            [$f['name'], $f['email'], password_hash($password, PASSWORD_DEFAULT), $card, today()]);
        $new_id = db()->lastInsertId();
        db()->commit();
        session_regenerate_id(true);
        $_SESSION['uid'] = $new_id;
        flash("Welcome. Your library card number is $card.");
        redirect('my-books.php');
    }
}
$title = 'Sign up';
$narrow = true;
require 'includes/header.php';
?>
<div class="auth">
<h1>Join the library</h1>
<p class="lead">Free for students. You get a card number straight away.</p>
<form method="post" class="card" novalidate>
  <?= csrf_field() ?>
  <label>Name <input id="reg-name" name="name" value="<?= e($f['name']) ?>" maxlength="80" required></label><?= field_error($errors, 'name') ?>
  <label>Email <input id="reg-email" name="email" type="email" value="<?= e($f['email']) ?>" maxlength="120" required></label><?= field_error($errors, 'email') ?>
  <label>Password <input id="reg-password" name="password" type="password" required></label><?= field_error($errors, 'password') ?>
  <?php if (!isset($errors['password'])): ?><p class="muted small">At least 8 characters.</p><?php endif; ?>
  <button class="button">Sign up</button>
</form>
<p class="after">Already a member? <a href="login.php">Log in</a></p>
</div>
<?php require 'includes/footer.php'; ?>
