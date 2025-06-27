<?php
session_start();
include 'db_config.php';
if (!isset($_SESSION['user'])) {
    echo "Access denied."; exit();
}
$role = $_SESSION['user']['role'];
$can_edit = in_array($role, ['admin', 'faculty']);
$year = $_GET['year'];
$branch = $_GET['branch'];
$section = $_GET['section'];
$search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";

$query = $conn->prepare("SELECT * FROM students WHERE year=? AND branch=? AND section=? AND (name LIKE ? OR roll_no LIKE ? OR email LIKE ?)");
$query->bind_param("ssssss", $year, $branch, $section, $search, $search, $search);
$query->execute();
$result = $query->get_result();
?>

<h2>Students of <?php echo "$year - $branch - $section"; ?></h2>

<form method="GET">
  <input type="hidden" name="year" value="<?php echo $year; ?>">
  <input type="hidden" name="branch" value="<?php echo $branch; ?>">
  <input type="hidden" name="section" value="<?php echo $section; ?>">
  🔍 Search Student: <input type="text" name="search" placeholder="Name, Roll, Email">
  <input type="submit" value="Search">
</form>
<br>

<table border="1" cellpadding="5">
<tr><th>Roll No</th><th>Name</th><th>Email</th><th>Actions</th></tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<td><?php echo $row['roll_no']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['email']; ?></td>
<td>
<?php if ($can_edit): ?>
<a href="faculty_attendance.php?sid=<?php echo $row['id']; ?>">Attendance</a> |
<a href="faculty_class_test.php?sid=<?php echo $row['id']; ?>">Class Test</a> |
<a href="faculty_mid_marks.php?sid=<?php echo $row['id']; ?>">Mid Marks</a>
<?php else: ?>
View Only
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</table>