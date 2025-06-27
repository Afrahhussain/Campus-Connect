<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'hod') {
    echo "Access denied.";
    exit();
}
?>
<h2>Welcome, HOD <?php echo htmlspecialchars($_SESSION['user']['name']); ?></h2>
<a href="logout.php">Logout</a>
