<?php
include 'db_connect.php';

$sql = "SELECT b.book_id, b.title, b.year, b.price, b.img, g.genre_name, b.blurb,
               GROUP_CONCAT(a.author_name SEPARATOR ', ') AS authors
        FROM books b
        LEFT JOIN genres g ON b.genre_id = g.genre_id
        LEFT JOIN book_authors ba ON b.book_id = ba.book_id
        LEFT JOIN authors a ON ba.author_id = a.author_id 
        GROUP BY b.book_id
        ORDER BY b.title";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Database query failed: ' . $conn->error]);
    exit;
}

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = [
        'book_id' => (int)$row['book_id'],
        'title' => $row['title'],
        'authors' => $row['authors'], 
        'genre_name' => $row['genre_name'],
        'year' => (int)$row['year'],
        'price' => (float)$row['price'],
        'blurb' => $row['blurb'],
        'img' => $row['img'] ?? 'default-cover.jpg', 
    ];
}

header('Content-Type: application/json');
echo json_encode($books);
$conn->close();
?>