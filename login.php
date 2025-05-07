<?php
session_start(); // Start the session

include 'db.php';

// Sanitize input
$email = $conn->real_escape_string($_POST['email']);
$password = $_POST['password'];

// Check user
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['name'];
        $_SESSION['email'] = $row['email'];

        echo "Login successful! Welcome, " . htmlspecialchars($row['name']) . ".";

        // Optional: Redirect to a dashboard
        // header("Location: dashboard.php");
        // exit;
    } else {
        echo "Invalid password!";
    }
} else {
    echo "User not found!";
}

$conn->close();
?>
