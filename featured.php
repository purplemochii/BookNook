<?php
include 'db_connect.php';

header('Content-Type: application/json');

try {
    // Get 4 random books
    $sql = "SELECT b.book_id, b.title, b.year, b.price, b.img,
                   g.genre_name, b.blurb,
                   GROUP_CONCAT(a.author_name SEPARATOR ', ') AS authors
            FROM books b
            LEFT JOIN genres g ON b.genre_id = g.genre_id
            LEFT JOIN book_authors ba ON b.book_id = ba.book_id
            LEFT JOIN authors a ON ba.author_id = a.author_id 
            GROUP BY b.book_id
            ORDER BY RAND()
            LIMIT 4";

    $result = $conn->query($sql);
    
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
            'img' => $row['img'] ? $row['img'] : 'default-cover.jpg'
        ];
    }
    
    echo json_encode($books);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>