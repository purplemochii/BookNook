<?php
session_start();
include 'db_connect.php';

$user_id = $_SESSION['user_id'] ?? 0;

$sql = "SELECT b.book_id, b.title, b.year, b.price, b.blurb, g.genre_name, b.img,
            GROUP_CONCAT(a.author_name SEPARATOR ', ') AS author -- get all authors
            FROM user_library ul
            JOIN Books b ON ul.book_id = b.book_id
            LEFT JOIN Genres g ON b.genre_id = g.genre_id
            LEFT JOIN Book_Authors ba ON b.book_id = ba.book_id
            LEFT JOIN Authors a ON ba.author_id = a.author_id
            WHERE ul.user_id = $user_id AND ul.book_status = 'owned'
            GROUP BY b.book_id, b.title, b.year, b.price, b.blurb, g.genre_name, b.img";

// $sql = "SELECT b.book_id, b.title, g.genre_name
//         FROM User_Library ul
//         JOIN Books b ON ul.book_id = b.book_id
//         LEFT JOIN Genres g ON b.genre_id = g.genre_id
//         WHERE ul.user_id = ? AND ul.status = 'Owned'";
$result = $conn->query($sql);

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = [
        'id' => (int)$row['book_id'],
        'title' => $row['title'],
        'author' => $row['author'], 
        'genre' => $row['genre_name'],
        'year' => (int)$row['year'],
        'price' => (float)$row['price'],
        'blurb' => $row['blurb'],
        // uses placeholder image if none provided
        'img' => $row['img'] ?? 'https://d827xgdhgqbnd.cloudfront.net/wp-content/uploads/2016/04/09121712/book-cover-placeholder.png'
    ];
}

header('Content-Type: application/json');
echo json_encode($books);
$conn->close();