-- Library Management System: tables and demo data. Import this file once (see the guide, Start and step 1).
-- Every account has the password: password123
-- Dates in the demo data are counted back from the day you import, so the demo is always up to date.

SET NAMES utf8mb4;   -- read this file as UTF-8, so Sinhala and Tamil titles are saved correctly by any import tool
DROP DATABASE IF EXISTS library_management;
CREATE DATABASE library_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE library_management;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('member','librarian') NOT NULL,
  card_no VARCHAR(10) NULL UNIQUE,
  created_at DATE NOT NULL
);

CREATE TABLE books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  author VARCHAR(100) NOT NULL,
  category VARCHAR(30) NOT NULL,
  language VARCHAR(10) NOT NULL,
  shelf VARCHAR(10) NOT NULL
);

CREATE TABLE copies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  book_id INT NOT NULL,
  barcode VARCHAR(6) NOT NULL UNIQUE,
  status ENUM('available','on_loan','on_hold_shelf','lost','withdrawn') NOT NULL DEFAULT 'available',
  FOREIGN KEY (book_id) REFERENCES books(id)
);

CREATE TABLE loans (
  id INT AUTO_INCREMENT PRIMARY KEY,
  copy_id INT NOT NULL,
  member_id INT NOT NULL,
  loaned_on DATE NOT NULL,
  due_on DATE NOT NULL,
  renewals INT NOT NULL DEFAULT 0,
  status ENUM('active','returned','lost') NOT NULL DEFAULT 'active',
  closed_on DATE NULL,
  FOREIGN KEY (copy_id) REFERENCES copies(id),
  FOREIGN KEY (member_id) REFERENCES users(id)
);

CREATE TABLE holds (
  id INT AUTO_INCREMENT PRIMARY KEY,
  book_id INT NOT NULL,
  member_id INT NOT NULL,
  created_at DATE NOT NULL,
  status ENUM('waiting','ready','collected','cancelled','expired') NOT NULL DEFAULT 'waiting',
  copy_id INT NULL,
  ready_on DATE NULL,
  collect_by DATE NULL,
  FOREIGN KEY (book_id) REFERENCES books(id),
  FOREIGN KEY (member_id) REFERENCES users(id),
  FOREIGN KEY (copy_id) REFERENCES copies(id)
);

CREATE TABLE fines (
  id INT AUTO_INCREMENT PRIMARY KEY,
  loan_id INT NOT NULL UNIQUE,
  member_id INT NOT NULL,
  reason ENUM('late','lost') NOT NULL,
  amount INT NOT NULL,
  status ENUM('unpaid','paid','waived') NOT NULL DEFAULT 'unpaid',
  created_on DATE NOT NULL,
  settled_on DATE NULL,
  FOREIGN KEY (loan_id) REFERENCES loans(id),
  FOREIGN KEY (member_id) REFERENCES users(id)
);

-- ---------- demo data ----------

INSERT INTO users (id, name, email, password, role, card_no, created_at) VALUES
(1, 'Ruwani Jayasinghe', 'ruwani@demo.test', '$2y$10$cnO7BMXYgH/tVlTEEtPDGeOUbsfJNeyHHvx8Uvn5jEJKo9JSKHrcS', 'librarian', NULL, DATE_ADD(CURDATE(), INTERVAL -71 DAY)),
(2, 'Marcus Bell', 'marcus@demo.test', '$2y$10$cnO7BMXYgH/tVlTEEtPDGeOUbsfJNeyHHvx8Uvn5jEJKo9JSKHrcS', 'librarian', NULL, DATE_ADD(CURDATE(), INTERVAL -71 DAY)),
(3, 'Tharushi Wijesekara', 'tharushi@demo.test', '$2y$10$cnO7BMXYgH/tVlTEEtPDGeOUbsfJNeyHHvx8Uvn5jEJKo9JSKHrcS', 'member', 'LM-1001', DATE_ADD(CURDATE(), INTERVAL -71 DAY)),
(4, 'Kasun Bandara', 'kasun@demo.test', '$2y$10$cnO7BMXYgH/tVlTEEtPDGeOUbsfJNeyHHvx8Uvn5jEJKo9JSKHrcS', 'member', 'LM-1002', DATE_ADD(CURDATE(), INTERVAL -71 DAY)),
(5, 'Priyanka Nadarajah', 'priyanka@demo.test', '$2y$10$cnO7BMXYgH/tVlTEEtPDGeOUbsfJNeyHHvx8Uvn5jEJKo9JSKHrcS', 'member', 'LM-1003', DATE_ADD(CURDATE(), INTERVAL -71 DAY)),
(6, 'Emily Carter', 'emily@demo.test', '$2y$10$cnO7BMXYgH/tVlTEEtPDGeOUbsfJNeyHHvx8Uvn5jEJKo9JSKHrcS', 'member', 'LM-1004', DATE_ADD(CURDATE(), INTERVAL -71 DAY)),
(7, 'Mohamed Rizwan', 'rizwan@demo.test', '$2y$10$cnO7BMXYgH/tVlTEEtPDGeOUbsfJNeyHHvx8Uvn5jEJKo9JSKHrcS', 'member', 'LM-1005', DATE_ADD(CURDATE(), INTERVAL -71 DAY)),
(8, 'Nadia Hussain', 'nadia@demo.test', '$2y$10$cnO7BMXYgH/tVlTEEtPDGeOUbsfJNeyHHvx8Uvn5jEJKo9JSKHrcS', 'member', 'LM-1006', DATE_ADD(CURDATE(), INTERVAL -71 DAY)),
(9, 'Sanjay Kumar', 'sanjay@demo.test', '$2y$10$cnO7BMXYgH/tVlTEEtPDGeOUbsfJNeyHHvx8Uvn5jEJKo9JSKHrcS', 'member', 'LM-1007', DATE_ADD(CURDATE(), INTERVAL -71 DAY)),
(10, 'Joel Dias', 'joel@demo.test', '$2y$10$cnO7BMXYgH/tVlTEEtPDGeOUbsfJNeyHHvx8Uvn5jEJKo9JSKHrcS', 'member', 'LM-1008', DATE_ADD(CURDATE(), INTERVAL -71 DAY)),
(11, 'Ishara Fernando', 'ishara@demo.test', '$2y$10$cnO7BMXYgH/tVlTEEtPDGeOUbsfJNeyHHvx8Uvn5jEJKo9JSKHrcS', 'member', 'LM-1009', DATE_ADD(CURDATE(), INTERVAL -71 DAY)),
(12, 'Daniel Okafor', 'daniel@demo.test', '$2y$10$cnO7BMXYgH/tVlTEEtPDGeOUbsfJNeyHHvx8Uvn5jEJKo9JSKHrcS', 'member', 'LM-1010', DATE_ADD(CURDATE(), INTERVAL -71 DAY)),
(13, 'Ayesha Khan', 'ayesha@demo.test', '$2y$10$cnO7BMXYgH/tVlTEEtPDGeOUbsfJNeyHHvx8Uvn5jEJKo9JSKHrcS', 'member', 'LM-1011', DATE_ADD(CURDATE(), INTERVAL -71 DAY));

INSERT INTO books (id, title, author, category, language, shelf) VALUES
(1, 'Introduction to Algorithms', 'Thomas H. Cormen, Charles E. Leiserson, Ronald L. Rivest, Clifford Stein', 'Computing', 'English', 'COM-1'),
(2, 'Database System Concepts', 'Abraham Silberschatz, Henry F. Korth, S. Sudarshan', 'Computing', 'English', 'COM-1'),
(3, 'Clean Code', 'Robert C. Martin', 'Computing', 'English', 'COM-1'),
(4, 'The Pragmatic Programmer', 'Andrew Hunt, David Thomas', 'Computing', 'English', 'COM-2'),
(5, 'Eloquent JavaScript', 'Marijn Haverbeke', 'Computing', 'English', 'COM-2'),
(6, 'Python Crash Course', 'Eric Matthes', 'Computing', 'English', 'COM-2'),
(7, 'Computer Networking: A Top-Down Approach', 'James F. Kurose, Keith W. Ross', 'Computing', 'English', 'COM-3'),
(8, 'Don''t Make Me Think', 'Steve Krug', 'Computing', 'English', 'COM-3'),
(9, 'Pride and Prejudice', 'Jane Austen', 'Fiction', 'English', 'FIC-1'),
(10, 'Things Fall Apart', 'Chinua Achebe', 'Fiction', 'English', 'FIC-1'),
(11, 'The Alchemist', 'Paulo Coelho', 'Fiction', 'English', 'FIC-1'),
(12, 'Frankenstein', 'Mary Shelley', 'Fiction', 'English', 'FIC-2'),
(13, 'Treasure Island', 'Robert Louis Stevenson', 'Fiction', 'English', 'FIC-2'),
(14, 'The Adventures of Sherlock Holmes', 'Arthur Conan Doyle', 'Fiction', 'English', 'FIC-2'),
(15, 'Funny Boy', 'Shyam Selvadurai', 'Fiction', 'English', 'FIC-3'),
(16, 'Madol Doova (මඩොල් දූව)', 'Martin Wickramasinghe (මාර්ටින් වික්‍රමසිංහ)', 'Fiction', 'Sinhala', 'FIC-3'),
(17, 'Gamperaliya (ගම්පෙරළිය)', 'Martin Wickramasinghe (මාර්ටින් වික්‍රමසිංහ)', 'Fiction', 'Sinhala', 'FIC-3'),
(18, 'Ponniyin Selvan (பொன்னியின் செல்வன்)', 'Kalki Krishnamurthy (கல்கி)', 'Fiction', 'Tamil', 'FIC-4'),
(19, 'A Brief History of Time', 'Stephen Hawking', 'Science', 'English', 'SCI-1'),
(20, 'The Selfish Gene', 'Richard Dawkins', 'Science', 'English', 'SCI-1'),
(21, 'Cosmos', 'Carl Sagan', 'Science', 'English', 'SCI-1'),
(22, 'Astrophysics for People in a Hurry', 'Neil deGrasse Tyson', 'Science', 'English', 'SCI-2'),
(23, 'Silent Spring', 'Rachel Carson', 'Science', 'English', 'SCI-2'),
(24, 'Sapiens', 'Yuval Noah Harari', 'History and Biography', 'English', 'HIS-1'),
(25, 'The Diary of a Young Girl', 'Anne Frank', 'History and Biography', 'English', 'HIS-1'),
(26, 'Wings of Fire', 'A. P. J. Abdul Kalam with Arun Tiwari', 'History and Biography', 'English', 'HIS-1'),
(27, 'Long Walk to Freedom', 'Nelson Mandela', 'History and Biography', 'English', 'HIS-2'),
(28, 'Running in the Family', 'Michael Ondaatje', 'History and Biography', 'English', 'HIS-2'),
(29, 'Atomic Habits', 'James Clear', 'Learning and Self-help', 'English', 'LRN-1'),
(30, 'Thinking, Fast and Slow', 'Daniel Kahneman', 'Learning and Self-help', 'English', 'LRN-1'),
(31, 'Deep Work', 'Cal Newport', 'Learning and Self-help', 'English', 'LRN-1'),
(32, 'Make It Stick', 'Peter C. Brown, Henry L. Roediger III, Mark A. McDaniel', 'Learning and Self-help', 'English', 'LRN-2');

INSERT INTO copies (id, book_id, barcode, status) VALUES
(1, 1, 'B00001', 'on_loan'),
(2, 1, 'B00002', 'available'),
(3, 2, 'B00003', 'on_loan'),
(4, 2, 'B00004', 'on_loan'),
(5, 2, 'B00005', 'available'),
(6, 3, 'B00006', 'on_hold_shelf'),
(7, 3, 'B00007', 'on_loan'),
(8, 4, 'B00008', 'available'),
(9, 5, 'B00009', 'on_loan'),
(10, 5, 'B00010', 'available'),
(11, 6, 'B00011', 'on_loan'),
(12, 6, 'B00012', 'available'),
(13, 6, 'B00013', 'available'),
(14, 7, 'B00014', 'on_hold_shelf'),
(15, 8, 'B00015', 'available'),
(16, 9, 'B00016', 'on_loan'),
(17, 9, 'B00017', 'available'),
(18, 10, 'B00018', 'available'),
(19, 10, 'B00019', 'available'),
(20, 11, 'B00020', 'on_loan'),
(21, 11, 'B00021', 'available'),
(22, 11, 'B00022', 'withdrawn'),
(23, 12, 'B00023', 'lost'),
(24, 12, 'B00024', 'available'),
(25, 13, 'B00025', 'on_loan'),
(26, 14, 'B00026', 'available'),
(27, 14, 'B00027', 'available'),
(28, 15, 'B00028', 'available'),
(29, 16, 'B00029', 'available'),
(30, 16, 'B00030', 'available'),
(31, 17, 'B00031', 'available'),
(32, 18, 'B00032', 'on_loan'),
(33, 19, 'B00033', 'on_loan'),
(34, 19, 'B00034', 'available'),
(35, 20, 'B00035', 'available'),
(36, 21, 'B00036', 'on_loan'),
(37, 22, 'B00037', 'available'),
(38, 22, 'B00038', 'available'),
(39, 23, 'B00039', 'available'),
(40, 24, 'B00040', 'on_loan'),
(41, 24, 'B00041', 'available'),
(42, 25, 'B00042', 'on_loan'),
(43, 25, 'B00043', 'available'),
(44, 26, 'B00044', 'available'),
(45, 26, 'B00045', 'available'),
(46, 27, 'B00046', 'available'),
(47, 28, 'B00047', 'available'),
(48, 29, 'B00048', 'on_loan'),
(49, 29, 'B00049', 'on_loan'),
(50, 30, 'B00050', 'available'),
(51, 31, 'B00051', 'available'),
(52, 32, 'B00052', 'available'),
(53, 32, 'B00053', 'available');

INSERT INTO loans (id, copy_id, member_id, loaned_on, due_on, renewals, status, closed_on) VALUES
(1, 11, 3, DATE_ADD(CURDATE(), INTERVAL -14 DAY), DATE_ADD(CURDATE(), INTERVAL 0 DAY), 0, 'active', NULL),
(2, 7, 3, DATE_ADD(CURDATE(), INTERVAL -12 DAY), DATE_ADD(CURDATE(), INTERVAL 2 DAY), 0, 'active', NULL),
(3, 3, 3, DATE_ADD(CURDATE(), INTERVAL -5 DAY), DATE_ADD(CURDATE(), INTERVAL 9 DAY), 0, 'active', NULL),
(4, 1, 4, DATE_ADD(CURDATE(), INTERVAL -19 DAY), DATE_ADD(CURDATE(), INTERVAL -5 DAY), 0, 'active', NULL),
(5, 36, 5, DATE_ADD(CURDATE(), INTERVAL -14 DAY), DATE_ADD(CURDATE(), INTERVAL 0 DAY), 0, 'active', NULL),
(6, 32, 5, DATE_ADD(CURDATE(), INTERVAL -7 DAY), DATE_ADD(CURDATE(), INTERVAL 7 DAY), 0, 'active', NULL),
(7, 40, 6, DATE_ADD(CURDATE(), INTERVAL -13 DAY), DATE_ADD(CURDATE(), INTERVAL 1 DAY), 0, 'active', NULL),
(8, 48, 6, DATE_ADD(CURDATE(), INTERVAL -12 DAY), DATE_ADD(CURDATE(), INTERVAL 2 DAY), 0, 'active', NULL),
(9, 20, 6, DATE_ADD(CURDATE(), INTERVAL -19 DAY), DATE_ADD(CURDATE(), INTERVAL 9 DAY), 1, 'active', NULL),
(10, 33, 6, DATE_ADD(CURDATE(), INTERVAL -2 DAY), DATE_ADD(CURDATE(), INTERVAL 12 DAY), 0, 'active', NULL),
(11, 42, 6, DATE_ADD(CURDATE(), INTERVAL -1 DAY), DATE_ADD(CURDATE(), INTERVAL 13 DAY), 0, 'active', NULL),
(12, 25, 7, DATE_ADD(CURDATE(), INTERVAL -55 DAY), DATE_ADD(CURDATE(), INTERVAL -41 DAY), 0, 'active', NULL),
(13, 16, 8, DATE_ADD(CURDATE(), INTERVAL -40 DAY), DATE_ADD(CURDATE(), INTERVAL -12 DAY), 1, 'active', NULL),
(14, 4, 11, DATE_ADD(CURDATE(), INTERVAL -7 DAY), DATE_ADD(CURDATE(), INTERVAL 7 DAY), 0, 'active', NULL),
(15, 49, 12, DATE_ADD(CURDATE(), INTERVAL -9 DAY), DATE_ADD(CURDATE(), INTERVAL 5 DAY), 0, 'active', NULL),
(16, 9, 12, DATE_ADD(CURDATE(), INTERVAL -30 DAY), DATE_ADD(CURDATE(), INTERVAL 12 DAY), 2, 'active', NULL),
(17, 51, 9, DATE_ADD(CURDATE(), INTERVAL -58 DAY), DATE_ADD(CURDATE(), INTERVAL -44 DAY), 0, 'returned', DATE_ADD(CURDATE(), INTERVAL -20 DAY)),
(18, 39, 6, DATE_ADD(CURDATE(), INTERVAL -41 DAY), DATE_ADD(CURDATE(), INTERVAL -27 DAY), 0, 'returned', DATE_ADD(CURDATE(), INTERVAL -20 DAY)),
(19, 35, 4, DATE_ADD(CURDATE(), INTERVAL -62 DAY), DATE_ADD(CURDATE(), INTERVAL -48 DAY), 0, 'returned', DATE_ADD(CURDATE(), INTERVAL -44 DAY)),
(20, 31, 8, DATE_ADD(CURDATE(), INTERVAL -71 DAY), DATE_ADD(CURDATE(), INTERVAL -57 DAY), 0, 'returned', DATE_ADD(CURDATE(), INTERVAL -54 DAY)),
(21, 52, 3, DATE_ADD(CURDATE(), INTERVAL -70 DAY), DATE_ADD(CURDATE(), INTERVAL -56 DAY), 0, 'returned', DATE_ADD(CURDATE(), INTERVAL -57 DAY)),
(22, 28, 5, DATE_ADD(CURDATE(), INTERVAL -50 DAY), DATE_ADD(CURDATE(), INTERVAL -36 DAY), 0, 'returned', DATE_ADD(CURDATE(), INTERVAL -37 DAY)),
(23, 46, 11, DATE_ADD(CURDATE(), INTERVAL -36 DAY), DATE_ADD(CURDATE(), INTERVAL -22 DAY), 0, 'returned', DATE_ADD(CURDATE(), INTERVAL -23 DAY)),
(24, 15, 12, DATE_ADD(CURDATE(), INTERVAL -43 DAY), DATE_ADD(CURDATE(), INTERVAL -29 DAY), 0, 'returned', DATE_ADD(CURDATE(), INTERVAL -30 DAY)),
(25, 37, 6, DATE_ADD(CURDATE(), INTERVAL -63 DAY), DATE_ADD(CURDATE(), INTERVAL -49 DAY), 0, 'returned', DATE_ADD(CURDATE(), INTERVAL -50 DAY)),
(26, 14, 4, DATE_ADD(CURDATE(), INTERVAL -22 DAY), DATE_ADD(CURDATE(), INTERVAL -8 DAY), 0, 'returned', DATE_ADD(CURDATE(), INTERVAL -8 DAY)),
(27, 6, 12, DATE_ADD(CURDATE(), INTERVAL -15 DAY), DATE_ADD(CURDATE(), INTERVAL -1 DAY), 0, 'returned', DATE_ADD(CURDATE(), INTERVAL -1 DAY)),
(28, 23, 10, DATE_ADD(CURDATE(), INTERVAL -51 DAY), DATE_ADD(CURDATE(), INTERVAL -37 DAY), 0, 'lost', DATE_ADD(CURDATE(), INTERVAL -22 DAY));

INSERT INTO holds (id, book_id, member_id, created_at, status, copy_id, ready_on, collect_by) VALUES
(1, 3, 5, DATE_ADD(CURDATE(), INTERVAL -8 DAY), 'ready', 6, DATE_ADD(CURDATE(), INTERVAL -1 DAY), DATE_ADD(CURDATE(), INTERVAL 2 DAY)),
(2, 7, 11, DATE_ADD(CURDATE(), INTERVAL -13 DAY), 'expired', 14, DATE_ADD(CURDATE(), INTERVAL -8 DAY), DATE_ADD(CURDATE(), INTERVAL -5 DAY)),
(3, 7, 3, DATE_ADD(CURDATE(), INTERVAL -7 DAY), 'waiting', NULL, NULL, NULL),
(4, 29, 11, DATE_ADD(CURDATE(), INTERVAL -8 DAY), 'waiting', NULL, NULL, NULL),
(5, 29, 5, DATE_ADD(CURDATE(), INTERVAL -6 DAY), 'waiting', NULL, NULL, NULL),
(6, 29, 3, DATE_ADD(CURDATE(), INTERVAL -5 DAY), 'waiting', NULL, NULL, NULL);

INSERT INTO fines (id, loan_id, member_id, reason, amount, status, created_on, settled_on) VALUES
(1, 17, 9, 'late', 120, 'unpaid', DATE_ADD(CURDATE(), INTERVAL -20 DAY), NULL),
(2, 18, 6, 'late', 35, 'unpaid', DATE_ADD(CURDATE(), INTERVAL -20 DAY), NULL),
(3, 28, 10, 'lost', 800, 'unpaid', DATE_ADD(CURDATE(), INTERVAL -22 DAY), NULL),
(4, 19, 4, 'late', 20, 'paid', DATE_ADD(CURDATE(), INTERVAL -44 DAY), DATE_ADD(CURDATE(), INTERVAL -37 DAY)),
(5, 20, 8, 'late', 15, 'waived', DATE_ADD(CURDATE(), INTERVAL -54 DAY), DATE_ADD(CURDATE(), INTERVAL -43 DAY));
