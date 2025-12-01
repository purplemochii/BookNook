//delete later
<?php
session_start();
include 'db_connect.php';

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    $sql = "SELECT b.book_id, b.title, b.price, g.genre_name,
                   GROUP_CONCAT(a.author_name SEPARATOR ', ') AS authors
            FROM User_Library ul
            JOIN Books b ON ul.book_id = b.book_id
            LEFT JOIN Genres g ON b.genre_id = g.genre_id
            LEFT JOIN Book_Authors ba ON b.book_id = ba.book_id
            LEFT JOIN Authors a ON ba.author_id = a.author_id
            WHERE ul.user_id = ? AND ul.status = 'Owned'
            GROUP BY b.book_id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT b.book_id, b.title, b.price, g.genre_name,
                                   GROUP_CONCAT(a.author_name SEPARATOR ', ') AS authors
                            FROM Books b
                            LEFT JOIN Genres g ON b.genre_id = g.genre_id
                            LEFT JOIN Book_Authors ba ON b.book_id = ba.book_id
                            LEFT JOIN Authors a ON ba.author_id = a.author_id
                            GROUP BY b.book_id");
}

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

header('Content-Type: application/json');
echo json_encode($books);
$conn->close();
