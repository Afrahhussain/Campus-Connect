<?php
session_start();
include 'db_config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Access denied."; exit();
}

$id = $_POST['id'];
$new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
$stmt->bind_param("si", $new_pass, $id);
$stmt->execute();
echo "Password updated. <a href='admin_users.php'>Back</a>";
?>