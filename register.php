<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // ✅ Backend Gmail validation
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@gmail\.com$/", $email)) {
        die("❌ Invalid Gmail. Please enter a valid Gmail ID ending with .com");
    }

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        echo "✅ Registration successful. Await admin approval.";
    } else {
        echo "❌ Error: " . $stmt->error;
    }
}
?>

