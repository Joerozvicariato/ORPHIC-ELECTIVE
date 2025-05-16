<?php
session_start();
require_once 'connection.php'; // This defines $pdo

// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if fields exist
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        die("Username or password not provided.");
    }

    $username = $_POST['username'];
    $password = $_POST['password']; // Match stored format

    $stmt = $pdo->prepare("SELECT * FROM tbl_user WHERE user_name = ? AND user_password = ?");
    $stmt->execute([$username, $password]);

    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['username'] = $user['username'];
        echo "Login successful. Welcome, " . htmlspecialchars($user['username']) . "!";
        header("Location: Home.php"); // optional redirect
    } else {
        echo "Invalid username or password.";
    }
} else {
    echo "Invalid request method.";
}
?>
