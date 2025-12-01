<?php
session_start();
include 'db_connect.php';

$user_id = $_SESSION['user_id'] ?? null;
$book_id = intval($_POST['book_id'] ?? 0);

$response = ["success" => false, "message" => "Purchase failed."];

if ($user_id && $book_id) {
    $conn->query("INSERT INTO Orders (user_id, book_id, order_date) VALUES ($user_id, $book_id, NOW())");
    $conn->query("INSERT INTO User_Library (user_id, book_id, book_status) VALUES ($user_id, $book_id, 'owned')");
    $response["success"] = true;
    $response["message"] = "Purchase successful.";
}else{
    $response["success"] = false;
    $response["message"] = "Invalid user or book ID.";
}

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>