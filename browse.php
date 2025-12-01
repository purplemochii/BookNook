<?php
session_start();
include 'db_connect.php';

$user_id = $_SESSION['user_id'] ?? 0;

$sql = "SELECT b.book_id, b.title, b.year, b.price, g.genre_name, b.blurb, b.img,
                        ul.book_status AS book_status,
                        GROUP_CONCAT(a.author_name SEPARATOR ', ') AS authors
                        FROM Books b
                        LEFT JOIN Genres g ON b.genre_id = g.genre_id
                        LEFT JOIN Book_Authors ba ON b.book_id = ba.book_id
                        LEFT JOIN Authors a ON ba.author_id = a.author_id 
                        LEFT JOIN user_library ul ON b.book_id = ul.book_id AND ul.user_id = $user_id  -- used to display 'Already Owned' on browse page
                        GROUP BY b.book_id, b.title, b.year, b.price, g.genre_name, b.blurb, b.img, ul.book_status
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
        'status' => $row['book_status'] ?? 'none',
        // uses placeholder image if none provided
        'img' => $row['img'] ?? 'https://d827xgdhgqbnd.cloudfront.net/wp-content/uploads/2016/04/09121712/book-cover-placeholder.png'
    ];
}

header('Content-Type: application/json');
echo json_encode($books);
$conn->close();
?>
