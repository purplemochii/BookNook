<?php
session_start();
include 'db_connect.php';

$user_id = $_SESSION['user_id'] ?? 0;

$sql = "SELECT b.book_id, b.title, g.genre_name
        FROM User_Library ul
        JOIN Books b ON ul.book_id = b.book_id
        LEFT JOIN Genres g ON b.genre_id = g.genre_id
        WHERE ul.user_id = ? AND ul.book_status = 'To Read'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

header('Content-Type: application/json');
echo json_encode($books);
$conn->close();