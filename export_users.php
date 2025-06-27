<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Access denied."; exit();
}

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=users.csv");

$output = fopen("php://output", "w");
fputcsv($output, ['ID', 'Name', 'Email', 'Role', 'Status']);

$result = $conn->query("SELECT * FROM users");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}
fclose($output);
?>
