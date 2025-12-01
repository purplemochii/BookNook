<?php
session_start();
require 'db_connect.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);


$user_id = $_SESSION['user_id']; 
$book_id = intval($input['book_id']);

// Check if already in readlist
$checkStmt = $conn->prepare("SELECT * FROM user_library WHERE user_id = ? AND book_id = ? AND book_status = 'readlist'");
$checkStmt->bind_param("ii", $user_id, $book_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Already in readlist']);
    exit;
}

// Insert into readlist
$stmt = $conn->prepare("INSERT INTO user_library (user_id, book_id, book_status) VALUES (?, ?, 'readlist')");
$stmt->bind_param("ii", $user_id, $book_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to add to readlist: ' . $conn->error]);
}
?>