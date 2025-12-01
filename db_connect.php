<?php
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