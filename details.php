<?php
include 'db_connect.php';

$book_id = intval($_GET['id'] ?? 0);

if ($book_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid book ID']);
    exit;
}

$sql = "SELECT b.book_id, b.title, b.price, b.blurb, b.img, b.year, g.genre_name,
               GROUP_CONCAT(a.author_name SEPARATOR ', ') AS authors
        FROM books b
        LEFT JOIN genres g ON b.genre_id = g.genre_id
        LEFT JOIN book_authors ba ON b.book_id = ba.book_id
        LEFT JOIN authors a ON ba.author_id = a.author_id
        WHERE b.book_id = ?
        GROUP BY b.book_id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Book not found']);
    exit;
}

$book = $result->fetch_assoc();
// Ensure all expected fields exist
$book['img'] = $book['img'] ?? 'default-cover.jpg';
$book['price'] = (float)$book['price'];

header('Content-Type: application/json');
echo json_encode($book);
$conn->close();
?>