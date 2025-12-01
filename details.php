<?php
include 'db_connect.php';

$book_id = intval($_GET['id'] ?? 0);

$sql = "SELECT b.book_id, b.title, b.price, b.blurb, g.genre_name,
               b.img,
               GROUP_CONCAT(a.author_name SEPARATOR ', ') AS authors,
        FROM Books b
        LEFT JOIN Genres g ON b.genre_id = g.genre_id
        LEFT JOIN Book_Authors ba ON b.book_id = ba.book_id
        LEFT JOIN Authors a ON ba.author_id = a.author_id
        WHERE b.book_id = ?
        GROUP BY b.book_id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

$book = $result->fetch_assoc();

header('Content-Type: application/json');
echo json_encode($book);
$conn->close();
?>
