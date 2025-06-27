<?php
include 'db_config.php'; // session already started in admin_dashboard.php

// ✅ Session role check
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Access denied.";
    exit();
}
?>

<h2>🕓 Pending Users</h2>
<a href='admin_dashboard.php'>🏠 Back to Dashboard</a><br><br>

<?php
// ✅ Fetch users with status 'pending'
$result = $conn->query("SELECT * FROM users WHERE status = 'pending'");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "👤 <b>{$row['name']}</b> | Role: {$row['role']} | Email: {$row['email']}<br>";
        echo "<a href='verify_user.php?id={$row['id']}&action=approve'>✅ Approve</a> | ";
        echo "<a href='verify_user.php?id={$row['id']}&action=reject'>❌ Reject</a>";
        echo "<hr>";
    }
} else {
    echo "<p>No pending users found.</p>";
}
?>

