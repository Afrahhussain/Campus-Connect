<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Access denied.";
    exit();
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'approve') {
        $conn->query("UPDATE users SET status='approved' WHERE id=$id");
    } elseif ($action == 'reject') {
        $conn->query("UPDATE users SET status='rejected' WHERE id=$id");
    }
}
header("Location: admin_dashboard.php?page=pending");
exit();
?>
