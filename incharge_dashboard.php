<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'incharge') {
    echo "Access denied.";
    exit();
}
?>
<h2>Welcome, Class Incharge <?php echo htmlspecialchars($_SESSION['user']['name']); ?></h2>
<a href="logout.php">Logout</a>
