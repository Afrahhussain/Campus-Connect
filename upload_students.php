<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['faculty', 'admin'])) {
    echo "Access denied."; exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $section = $_POST['section'];
    $added_by = $_SESSION['user']['id'];

    $stmt = $conn->prepare("INSERT INTO students (name, email, section, added_by) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $email, $section, $added_by);

    if ($stmt->execute()) {
        echo "✅ Student added successfully.";
    } else {
        echo "❌ Error: " . $stmt->error;
    }
}
?>

<h2>Upload Student Details</h2>
<form method="POST">
    Student Name: <input type="text" name="name" required><br><br>
    Student Email: <input type="email" name="email" required><br><br>
    Section/Class: <input type="text" name="section" required><br><br>
    <input type="submit" value="Add Student">
</form>
