<?php
<<<<<<< HEAD
$DB_HOST = "localhost";   
$DB_USER = "root";        
$DB_PASS = "";            
$DB_NAME = "booknook_db"; 
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
=======
    // XAMPP's default account
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbName = "booknook_db";

    /*
        1. Connect to the DBMS
        2. Select the DB
        3. Run SQL commands
        4. Process the results
        5. Close the connection
    */

    // 1 + 2: Create connection and select DB
    $conn = new mysqli($host, $user, $pass, $dbName);

    // if a connection results in an error, end it
    if($conn->connect_errno > 0){
        die("Unable to connect to DBMS and/or DB.<br/>");
    }
?>
>>>>>>> 0bb6fc4477c8482e606687a37643559d59c6da55
