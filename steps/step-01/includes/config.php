<?php
// includes/config.php - the only file you change to set up the system.

// Database (XAMPP defaults: user root, no password)
define('DB_DSN', 'mysql:host=127.0.0.1;dbname=campus_library;charset=utf8mb4');
define('DB_USER', 'root');
define('DB_PASS', '');

date_default_timezone_set('Asia/Colombo');

// Library rules. Change a number here and the whole system follows.
define('LOAN_DAYS', 14);       // days for each loan or renewal
define('MAX_LOANS', 5);        // books one member can have out
define('MAX_RENEWALS', 2);     // renewals per loan
define('MAX_HOLDS', 3);        // active holds per member
define('PICKUP_DAYS', 3);      // days to collect a held copy
define('FINE_PER_DAY', 5);     // Rs per day late
define('FINE_CAP', 200);       // a late fine never goes above this
define('BLOCK_AT', 100);       // unpaid fines of this much block borrowing
define('LOST_FEE', 800);       // Rs charged for a lost book
define('MAX_COPIES', 10);      // copy records per title

$CATEGORIES = ['Computing', 'Fiction', 'Science', 'History and Biography', 'Learning and Self-help'];
$LANGUAGES  = ['English', 'Sinhala', 'Tamil'];
