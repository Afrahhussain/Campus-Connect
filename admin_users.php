<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Access denied.";
    exit();
}

$result = $conn->query("SELECT * FROM users");
?>

<h2>All Registered Users</h2>
<a href="admin_dashboard.php">Back to Dashboard</a> | <a href="logout.php">Logout</a> | <a href="export_users.php">Download CSV</a><br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th>
    </tr>

<?php
while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['name']}</td>
        <td>{$row['email']}</td>
        <td>{$row['role']}</td>
        <td>{$row['status']}</td>
        <td>
            <a href='edit_user.php?id={$row['id']}'>Edit</a> |
            <a href='delete_user.php?id={$row['id']}' onclick=\"return confirm('Delete this user?')\">Delete</a> |
            <a href='change_password_form.php?id={$row['id']}'>Change Password</a>
        </td>
    </tr>";
}
?>
</table>
