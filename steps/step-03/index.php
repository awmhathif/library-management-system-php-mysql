<?php
require 'includes/functions.php';
$title = 'Home';
$titles = (int)val("SELECT COUNT(DISTINCT book_id) FROM copies WHERE status NOT IN ('lost', 'withdrawn')");
$on_shelf = (int)val("SELECT COUNT(*) FROM copies WHERE status = 'available'");
// The shelf: titles with a copy on the shelf now, as spines. Height comes from the title length, so the shelf looks uneven like a real one.
$spines = rows("SELECT b.id, b.title, b.category FROM books b WHERE EXISTS (SELECT 1 FROM copies c WHERE c.book_id = b.id AND c.status = 'available')
                ORDER BY b.category, b.title LIMIT 18");
require 'includes/header.php';
?>
<section class="hero">
  <h1>Find a book. Hold it. Pick it up.</h1>
  <p class="lead">See what is on the shelf, join the queue for what is out, and know your due dates.</p>
  <form action="books.php" method="get" class="bigsearch" role="search">
    <input type="search" name="q" placeholder="Search by title or author" aria-label="Title or author">
    <button>Search the catalogue</button>
  </form>
  <p class="stats"><strong><?= $titles ?></strong> titles, <strong><?= $on_shelf ?></strong> copies on the shelf right now.</p>
</section>

<?php if ($spines): ?>
<div class="shelf" aria-label="Books on the shelf now">
  <?php foreach ($spines as $s): ?>
    <a class="spine <?= cover_class($s['category']) ?>" style="height: <?= 8 + min(2, intdiv(mb_strlen($s['title']), 12)) ?>rem" href="book.php?id=<?= $s['id'] ?>" title="<?= e($s['title']) ?>"><span><?= e($s['title']) ?></span></a>
  <?php endforeach; ?>
</div>
<p class="shelfnote muted small">On the shelf now. <span class="wide-only">Click a spine to open the book.</span><span class="phone-only">Swipe the shelf for more, tap a spine to open the book.</span></p>
<?php endif; ?>

<section class="promise">
  <h2>Your reading is private</h2>
  <p>Members see only counts, such as "3 people waiting". Nobody can see another member's name, loans or holds. Only the librarians can.</p>
  <?php if (!me()): ?><p><a class="button" href="register.php">Join the library</a> &nbsp; <a href="login.php">Log in</a></p><?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
