<?php
require 'db_connect.php'; 

header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['book_id'])) {
    echo json_encode(['error' => 'No book_id provided']);
    exit;
}

$book_id = intval($input['book_id']);
$user_id = $_SESSION['user_id']; // or 1 if you're testing

$stmt = $conn->prepare("INSERT INTO user_library (user_id, book_id, book_status)
                        VALUES (?, ?, 'readlist')");
$stmt->bind_param("ii", $user_id, $book_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'DB insert failed']);
}
?>
