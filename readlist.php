<?php
session_start();
include 'db_connect.php';


$user_id = $_SESSION['user_id'] ?? 0;
header('Content-Type: application/json');

if($user_id == 0){
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $book_id = $_POST["book_id"] ?? null;
    $action = $_POST["action"] ?? null;
    $response = ['success' => false, 'message' => 'Invalid action'];

    if($book_id==null){
        $response = ['success' => false, 'message' => 'Invalid book ID.'];
        echo json_encode($response);
        exit;
    }

    // Action=add: add to readlist
    if($action== 'add'){
        $new_status = "readlist";
        $sql = "INSERT INTO user_library (user_id, book_id, book_status) VALUES (?, ?, ?) -- insert book to user_library with corresponding u_id and b_id
                ON DUPLICATE KEY UPDATE book_status = VALUES(book_status)"; // if book exists, just update status
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $user_id, $book_id, $new_status);

        if($stmt->execute()){
            $response = ['success' => true, 'message' => 'Book added to Readlist.'];
        }else{
            $response = ['success' => false, 'message' => 'Failed to add book to Readlist.'];
        }
        echo json_encode($response);
        $conn->close();
        exit;

    // Action=remove: remove from readlist
    }else if($action == 'remove'){
        $sql = "DELETE FROM user_library WHERE user_id = ? AND book_id = ? AND book_status = 'readlist'"; // delete book from user_library for given u_id and b_id if its on readlist
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $book_id);

        if($stmt->execute()){
            $response = ['success' => true, 'message' => 'Book removed from Readlist.'];
        }else{
            $response = ['success' => false, 'message' => 'Failed to remove book from Readlist.'];
        }
        echo json_encode($response);
        $conn->close();
        exit;
    }
}

// if ($_SERVER["REQUEST_METHOD"] == "POST"){
//     $book_id = $_POST["book_id"] ?? null;
//     $new_status = "owned";

//     if($book_id == null){
//         echo json_encode(['success' => false, 'message' => 'Invalid book ID.']);
//         exit;
//     }

//     // updating the book status from "readlist" to "Owned"
//     $sql = "UPDATE user_library
//             SET book_status = ?
//             WHERE user_id = ? AND book_id = ?";

//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("sii", $new_status, $user_id, $book_id);

//     if($stmt->execute()){
//         $response = ['success' => true, 'message' => 'Book status updated to owned.'];
//     }else{
//         $response = ['success' => false, 'message' => 'Failed to update book status.'];
//     }

    
//     header('Content-Type: application/json');
//     echo json_encode($response);
//     $conn->close();
//     exit;
    
// }

$sql = "SELECT b.book_id, b.title, b.year, b.price, b.blurb, g.genre_name, b.img,
            GROUP_CONCAT(a.author_name SEPARATOR ', ') AS author -- get all authors
            FROM user_library ul
            JOIN Books b ON ul.book_id = b.book_id
            LEFT JOIN Genres g ON b.genre_id = g.genre_id
            LEFT JOIN Book_Authors ba ON b.book_id = ba.book_id
            LEFT JOIN Authors a ON ba.author_id = a.author_id
            WHERE ul.user_id = $user_id AND ul.book_status = 'readlist'
            GROUP BY b.book_id, b.title, b.year, b.price, b.blurb, g.genre_name, b.img";

$result = $conn->query($sql);

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = [
        'id' => (int)$row['book_id'],
        'title' => $row['title'],
        'author' => $row['author'], 
        'genre' => $row['genre_name'],
        'year' => (int)$row['year'],
        'price' => (float)$row['price'],
        'blurb' => $row['blurb'],
        // uses placeholder image if none provided
        'img' => $row['img'] ?? 'https://d827xgdhgqbnd.cloudfront.net/wp-content/uploads/2016/04/09121712/book-cover-placeholder.png'
    ];
}

echo json_encode($books);
$conn->close();
?>