<?php
include 'db_connect.php';

$user_id = $_SESSION['user_id']; 

$sql = "SELECT b.book_id, b.title, b.img, g.genre_name,
               GROUP_CONCAT(a.author_name SEPARATOR ', ') AS authors
        FROM user_library ul
        JOIN books b ON ul.book_id = b.book_id
        LEFT JOIN genres g ON b.genre_id = g.genre_id
        LEFT JOIN book_authors ba ON b.book_id = ba.book_id
        LEFT JOIN authors a ON ba.author_id = a.author_id
        WHERE ul.user_id = ? AND ul.book_status = 'readlist'
        GROUP BY b.book_id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$books = [];
while ($row = $result->fetch_assoc()) {
    $row['img'] = $row['img'] ?? 'default-cover.jpg';
    $books[] = $row;
}

header('Content-Type: application/json');
echo json_encode($books);
$conn->close();
?>