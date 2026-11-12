<?php
require 'includes/functions.php';
$book = row('SELECT * FROM books WHERE id = ?', [(int)($_GET['id'] ?? 0)]);
$is_librarian = me() && me()['role'] === 'librarian';
if (!$book || (!$is_librarian && copies_in_library($book['id']) == 0)) not_found();
$id = $book['id'];


$copies = rows("SELECT c.*, u.name AS borrower, l.due_on FROM copies c
                LEFT JOIN loans l ON l.copy_id = c.id AND l.status = 'active'
                LEFT JOIN users u ON u.id = l.member_id WHERE c.book_id = ? ORDER BY c.barcode", [$id]);
$in_library = copies_in_library($id);
$on_shelf = (int)val("SELECT COUNT(*) FROM copies WHERE book_id = ? AND status = 'available'", [$id]);
$waiting = (int)val("SELECT COUNT(*) FROM holds WHERE book_id = ? AND status = 'waiting'", [$id]);
$back_by = val("SELECT MIN(l.due_on) FROM loans l JOIN copies c ON c.id = l.copy_id WHERE c.book_id = ? AND l.status = 'active'", [$id]);
$title = $book['title'];
require 'includes/header.php';
?>
<div class="bookhead">
  <?= cover($book, 'big') ?>
  <div>
    <h1><?= e($book['title']) ?></h1>
    <p><?= e($book['author']) ?></p>
    <p class="muted"><?= e($book['category']) ?> · <?= e($book['language']) ?> · Shelf <?= e($book['shelf']) ?></p>
  </div>
</div>

<?php if ($on_shelf): ?>
  <p class="status good"><?= $in_library == 1 ? 'The only copy is on the shelf now.' : $on_shelf . ' of ' . $in_library . ' copies on the shelf now.' ?></p>
<?php else: ?>
  <p class="status warn"><?= $in_library == 1 ? 'The only copy is out' : 'All ' . $in_library . ' copies are out' ?><?php if ($back_by && $back_by < today()): ?><?= $in_library == 1 ? ' and overdue' : ', one is overdue' ?><?php elseif ($back_by): ?><?= ($in_library == 1 ? ', due back by ' : ', one is due back by ') . nice_date($back_by) ?><?php endif; ?>.</p>
<?php endif; ?>
<?php if ($waiting): ?><p><?= $waiting == 1 ? '1 person' : $waiting . ' people' ?> waiting.</p><?php endif; ?>

<?php if ($is_librarian): ?>
  <h2>Copies</h2>
  <table>
    <tr><th>Barcode</th><th>Status</th><th>With</th></tr>
    <?php foreach ($copies as $c): ?>
      <tr><td data-label="Barcode"><?= e($c['barcode']) ?></td>
          <td data-label="Status"><?= e(str_replace('_', ' ', $c['status'])) ?></td>
          <td data-label="With"><?= $c['borrower'] ? e($c['borrower']) . ', ' . e(due_words($c['due_on'])) : '' ?></td></tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>
<?php require 'includes/footer.php'; ?>
