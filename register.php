<?php

include 'db.php';


$name = $conn->real_escape_string($_POST['name']);
$email = $conn->real_escape_string($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$gender = $conn->real_escape_string($_POST['gender']);
$skills = isset($_POST['skills']) ? implode(', ', $_POST['skills']) : '';
$country = $conn->real_escape_string($_POST['country']);


$sql = "INSERT INTO users (name, email, password, gender, skills, country)
        VALUES ('$name', '$email', '$password', '$gender', '$skills', '$country')";

if ($conn->query($sql) === TRUE) {
    echo "Registration successful!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
