<?php
    session_start();
    include 'db_connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        if ($password !== $confirm_password){
            echo "Passwords do not match";
            exit();
        }

        $sql = "INSERT INTO users (email, password) VALUES ('$email', '$password')";

        if ($conn -> query($sql) === TRUE){
            echo "Account created successfully";
            header("Location: home.html");
            exit();
        } else {
            echo "Account could not be created";
        }
    }
    $conn -> close();
?>