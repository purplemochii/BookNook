<?php
include 'db_connect.php';

$input = json_decode(file_get_contents("php://input"), true);
$book_id = intval($input['book_id'] ?? 0);
$user_id = $_SESSION['user_id']; // Hardcoded for testing - replace with $_SESSION['user_id'] when login is implemented

$response = ["success" => false];

if ($book_id <= 0) {
    $response['error'] = 'Invalid book ID';
} else {
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Get book price
        $priceStmt = $conn->prepare("SELECT price FROM books WHERE book_id = ?");
        $priceStmt->bind_param("i", $book_id);
        $priceStmt->execute();
        $priceResult = $priceStmt->get_result();
        $book = $priceResult->fetch_assoc();
        
        // Create order
        $orderStmt = $conn->prepare("INSERT INTO orders (user_id, book_id, sold_at) VALUES (?, ?, ?)");
        $sold_at = $book['price'];
        $orderStmt->bind_param("iid", $user_id, $book_id, $sold_at);
        $orderStmt->execute();
        
        // Add to user library as owned
        $libraryStmt = $conn->prepare("INSERT INTO user_library (user_id, book_id, book_status) VALUES (?, ?, 'owned') 
                                      ON DUPLICATE KEY UPDATE book_status = 'owned'");
        $libraryStmt->bind_param("ii", $user_id, $book_id);
        $libraryStmt->execute();
        
        // Remove from readlist if exists
        $removeStmt = $conn->prepare("DELETE FROM user_library WHERE user_id = ? AND book_id = ? AND book_status = 'readlist'");
        $removeStmt->bind_param("ii", $user_id, $book_id);
        $removeStmt->execute();
        
        $conn->commit();
        $response["success"] = true;
        $response["message"] = "Purchase completed successfully";
        
    } catch (Exception $e) {
        $conn->rollback();
        $response["error"] = "Purchase failed: " . $e->getMessage();
    }
}

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>