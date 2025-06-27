<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Access denied.";
    exit();
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM users WHERE id=$id");
$user = $result->fetch_assoc();
?>

<h2>Edit User</h2>
<form method="POST" action="update_user.php">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
    Name: <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br>
    Email: <input type="email" name="email" value="<?php echo $user['email']; ?>" pattern="[a-zA-Z0-9._%+-]+@gmail\.com$" required><br>
    Role:
    <select name="role">
        <option value="faculty" <?php if ($user['role'] == 'faculty') echo 'selected'; ?>>Faculty</option>
        <option value="incharge" <?php if ($user['role'] == 'incharge') echo 'selected'; ?>>Incharge</option>
        <option value="hod" <?php if ($user['role'] == 'hod') echo 'selected'; ?>>HOD</option>
        <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
    </select><br>
    Status:
    <select name="status">
        <option value="pending" <?php if ($user['status'] == 'pending') echo 'selected'; ?>>Pending</option>
        <option value="approved" <?php if ($user['status'] == 'approved') echo 'selected'; ?>>Approved</option>
        <option value="rejected" <?php if ($user['status'] == 'rejected') echo 'selected'; ?>>Rejected</option>
    </select><br>
    <input type="submit" value="Update">
</form>
