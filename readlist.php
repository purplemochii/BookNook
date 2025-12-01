<?php
session_start();
include 'db_connect.php';

$user_id = $_SESSION['user_id'] ?? 0;

if($user_id == 0){
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $book_id = $_POST["book_id"] ?? null;
    $new_status = "owned";

    if($book_id == null){
        echo json_encode(['success' => false, 'message' => 'Invalid book ID.']);
        exit;
    }

    // updating the book status from "readlist" to "Owned"
    $sql = "UPDATE user_library
            SET book_status = ?
            WHERE user_id = ? AND book_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $new_status, $user_id, $book_id);

    if($stmt->execute()){
        $response = ['success' => true, 'message' => 'Book status updated to owned.'];
    }else{
        $response = ['success' => false, 'message' => 'Failed to update book status.'];
    }

    
    header('Content-Type: application/json');
    echo json_encode($response);
    $conn->close();
    exit;
    
}

$sql = "SELECT b.book_id, b.title, g.genre_name
        FROM User_Library ul
        JOIN Books b ON ul.book_id = b.book_id
        LEFT JOIN Genres g ON b.genre_id = g.genre_id
        WHERE ul.user_id = ? AND ul.book_status = 'readlist'";
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
?>