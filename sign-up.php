<?php
    session_start();
    include 'db_connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        if ($password !== $confirm_password){
            echo "Passwords do not match";
            exit();
        }

        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

        if ($conn -> query($sql) === TRUE){
            // login the user after creating account
            $_new_user_id = $conn->insert_id;
            $_SESSION['user_id'] = $_new_user_id;
            $_SESSION['username'] = $username;
            header("Location: home.html");
            exit();
        } else {
            echo "Account could not be created";
        }
    }
    $conn -> close();
?>