<?php
session_start();
include 'db_config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'faculty') {
    echo "Access denied."; exit();
}
?>
<h2>Select Year, Branch, Section</h2>
<form method="GET" action="view_student_data.php">
    Year: <input type="text" name="year" required>
    Branch: <input type="text" name="branch" required>
    Section: <input type="text" name="section" required>
    <input type="submit" value="Load Students">
</form>
