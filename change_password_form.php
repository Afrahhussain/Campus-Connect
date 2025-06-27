<?php
session_start();
include 'db_config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Access denied."; exit();
}
$id = $_GET['id'];
?>
<h2>Change Password</h2>
<form method="POST" action="change_password.php">
  <input type="hidden" name="id" value="<?php echo $id; ?>">
  New Password: <input type="password" name="new_password" required><br>
  <input type="submit" value="Update Password">
</form>