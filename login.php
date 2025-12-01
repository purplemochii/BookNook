<?php
    session_start();
    include 'db_connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        $email = $_POST["email"];
        $password = $_POST["password"];

        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";

        $result = $conn -> query($sql);

        if ($result -> num_rows === 1) {
            $row = $result -> fetch_assoc();

            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION["username"] = $row["username"];

            "success" => true, 
            "message" => "Login successful"
            "user_id" => $row["user_id"],
            "username" => $row["username"]
            header("Location: home.html")
            exit();
        } else {
            echo json_encode([
                "success" => false, 
                "message" => "Invalid email or password"
            ]);
        }
    }
    $conn -> close();
?>