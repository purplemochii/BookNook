<?php
include 'db_connect.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action_type = $_POST['action_type'] ?? '';

    if($action_type === 'register'){
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $check_email_sql = "SELECT email FROM users WHERE email = '$email'";
        $check_result = $conn->query($check_email_sql);

        if($check_result->num_rows > 0){
            // Email already exists
            $message = "Email ID already exists";
        } else {
            // Email does not exist, proceed with registration
            $insert_sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
            if ($conn->query($insert_sql) === TRUE) {
                $message = "Account created successfully";
            } else {
                $message = "Error: " . $conn->error;
            }
        }
    }else if($action_type === 'login'){
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $login_sql = "SELECT username FROM users where email = '$email' AND password = '$password'";
        $login_result = $conn->query($login_sql);

        if($login_result->num_rows > 0){
            // Login found, logging in
            $row = $result->fetch_assoc();
            $username_from_db = $row['username'];

            $message ="Login successful.";

            $_SESSION['username'] = $username_from_db;
            $_SESSION['email'] = $email;

            header("Location: home.html");
            exit();
        }else{
            // No match found
            $message = "Invalid email or password.";
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – The Book Nook</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body class="index" data-page="index">

    <div class="auth-body">
        <h1 class="auth-title">THE BOOK NOOK</h1>
        <p class="auth-subtitle">Your personal digital library</p>

        <div class="auth-container">

            <!-- Toggle Buttons -->
            <div class="auth-toggle">
                <button class="auth-tab active" id="loginTab">Login</button>
                <button class="auth-tab" id="signupTab">Sign Up</button>
            </div>

            <!-- LOGIN FORM -->
            <form id="loginForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="auth-form active">
                <label>Email</label>
                <input type="email" placeholder="Enter your email">

                <label>Password</label>
                <input type="password" placeholder="Enter your password">

                <button id="auth-submit-login" class="auth-submit-login">Login</button>
            </form>

            <!-- SIGNUP FORM -->
            <form id="signupForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"class="auth-form">
                <label>Email</label>
                <input type="email" placeholder="Enter your email">

                <label>Password</label>
                <input type="password" placeholder="Create a password">

                <label>Confirm Password</label>
                <input type="password" placeholder="Re-enter your password">

                <button id="auth-submit-signup" class="auth-submit-signup">Sign Up</button>
            </form>

            <a href="home.html" class="auth-back">← Back to Home</a>

        </div>
    </div>
<script src="app.js"></script>
</body>
</html>
