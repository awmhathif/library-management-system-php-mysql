<?php
require 'includes/functions.php';
$title = 'Search';
$text = trim($_GET['q'] ?? '');
$cat = $_GET['category'] ?? '';
$lang = $_GET['language'] ?? '';

// Titles with no copy left in the library are hidden, except from librarians.
$sql = "SELECT b.*, (SELECT COUNT(*) FROM copies c WHERE c.book_id = b.id AND c.status = 'available') AS on_shelf,
               (SELECT COUNT(*) FROM copies c WHERE c.book_id = b.id AND c.status NOT IN ('lost', 'withdrawn')) AS in_library
        FROM books b WHERE (b.title LIKE ? OR b.author LIKE ?)";
$params = [like($text), like($text)];
if (in_array($cat, $CATEGORIES)) { $sql .= ' AND b.category = ?'; $params[] = $cat; }
if (in_array($lang, $LANGUAGES)) { $sql .= ' AND b.language = ?'; $params[] = $lang; }
if (!me() || me()['role'] !== 'librarian') $sql .= ' HAVING in_library > 0';
$books = rows($sql . ' ORDER BY b.title', $params);

require 'includes/header.php';
?>
<h1>Search the catalogue</h1>
<form method="get" class="filters">
  <input type="search" name="q" value="<?= e($text) ?>" placeholder="Title or author" aria-label="Title or author">
  <select name="category" aria-label="Category"><option value="">All categories</option>
    <?php foreach ($CATEGORIES as $c): ?><option <?= $c === $cat ? 'selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?></select>
  <select name="language" aria-label="Language"><option value="">All languages</option>
    <?php foreach ($LANGUAGES as $l): ?><option <?= $l === $lang ? 'selected' : '' ?>><?= e($l) ?></option><?php endforeach; ?></select>
  <button>Search</button>
</form>
<p class="muted"><?= plural(count($books), 'title') ?> found.</p>
<?php foreach ($books as $b): ?>
  <a class="result" href="book.php?id=<?= $b['id'] ?>">
    <?= cover($b) ?>
    <span class="info">
      <strong class="booktitle"><?= e($b['title']) ?></strong>
      <span><?= e($b['author']) ?></span>
      <span class="muted"><?= e($b['category']) ?> · <?= e($b['language']) ?> · Shelf <?= e($b['shelf']) ?></span>
    </span>
    <span class="status <?= $b['on_shelf'] ? 'good' : 'warn' ?>"><?= $b['on_shelf'] ? $b['on_shelf'] . ' on the shelf' : 'All copies out' ?></span>
  </a>
<?php endforeach; ?>
<?php if (!$books): ?><p class="empty">Nothing matched. Try fewer words, or <a href="books.php">clear the search and filters</a>.</p><?php endif; ?>
<?php require 'includes/footer.php'; ?>
