<?php
session_start();
include 'db_connect.php';

$user_id = $_SESSION['user_id'] ?? null;
$book_id = intval($_POST['book_id'] ?? 0);

$response = ["success" => false];

if ($user_id && $book_id) {
    $conn->query("INSERT INTO Orders (user_id, book_id, order_date) VALUES ($user_id, $book_id, NOW())");
    $conn->query("INSERT INTO User_Library (user_id, book_id, status) VALUES ($user_id, $book_id, 'Owned')");
    $response["success"] = true;
}

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>