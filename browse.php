<?php
session_start();
include 'db_connect.php';

$sql = "SELECT b.book_id, b.title, b.year, b.price, g.genre_name, b.blurb,
                        GROUP_CONCAT(a.author_name SEPARATOR ', ') AS authors
                        FROM Books b
                        LEFT JOIN Genres g ON b.genre_id = g.genre_id
                        LEFT JOIN Book_Authors ba ON b.book_id = ba.book_id
                        LEFT JOIN Authors a ON ba.author_id = a.author_id 
                        GROUP BY b.book_id, b.title, b.year, b.price, g.genre_name, b.blurb
                        ORDER BY b.title";

$result = $conn->query($sql);

$books = [];
while ($row = $result->fetch_assoc()) {
    // mapping the book structure to how its originally laid out on app.js
    $books[] = [
        'id' => (int)$row['book_id'],
        'title' => $row['title'],
        'author' => $row['authors'], 
        'genre' => $row['genre_name'],
        'year' => (int)$row['year'],
        'price' => (float)$row['price'],
        'blurb' => $row['blurb'],
        'img' => $row['img'],
    ];
}

header('Content-Type: application/json');
echo json_encode($books);
$conn->close();
?>
