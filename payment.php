<?php
session_start();
include 'db_connect.php';

$user_id = $_SESSION['user_id'] ?? null;
$book_id = intval($_POST['book_id'] ?? 0);

$response = ["success" => false, "message" => "Purchase failed."];

if (!$user_id || !$book_id) {
    echo json_encode(["success" => false, "message" => "Invalid user or book ID."]);
    exit;
}

$success = true;

// 1. update book status to 'owned'
$update_sql = "UPDATE user_library SET book_status = 'owned' WHERE user_id = $user_id AND book_id = $book_id";

if(!$conn->query($update_sql)){
    $response["message"] = "Failed to update book status.";
    $success = false;
}

// 2. insert book into user's bookshelf directly if no rows were affected
if($success && $conn->affected_rows == 0){
    $insert_sql = "INSERT INTO user_library (user_id, book_id, book_status) VALUES ($user_id, $book_id, 'owned')";
    if(!$conn->query($insert_sql)){
        $response["message"] = "Failed to insert book into user's bookshelf.";
        $success = false;
    }
}

// 3. insert into orders (always runs for a purchase)
$order_sql = "INSERT INTO orders (user_id, book_id, order_date) VALUES ($user_id, $book_id, NOW())";

if(!$conn->query($order_sql)){
    $response["message"] = "Failed to record the order.";
    $success = false;
}

if($success){
    $response["success"] = true;
    $response["message"] = "Purchase successful.";
}

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>