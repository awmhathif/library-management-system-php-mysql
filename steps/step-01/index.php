<?php
require 'includes/functions.php';
$title = 'Home';
$titles = (int)val("SELECT COUNT(DISTINCT book_id) FROM copies WHERE status NOT IN ('lost', 'withdrawn')");
$on_shelf = (int)val("SELECT COUNT(*) FROM copies WHERE status = 'available'");
require 'includes/header.php';
?>
<section class="hero">
  <h1>Find a book. Hold it. Pick it up.</h1>
  <p class="lead">See what is on the shelf, join the queue for what is out, and know your due dates.</p>
  <p class="stats"><strong><?= $titles ?></strong> titles, <strong><?= $on_shelf ?></strong> copies on the shelf right now.</p>
</section>


<section class="promise">
  <h2>Your reading is private</h2>
  <p>Members see only counts, such as "3 people waiting". Nobody can see another member's name, loans or holds. Only the librarians can.</p>
</section>
<?php require 'includes/footer.php'; ?>
