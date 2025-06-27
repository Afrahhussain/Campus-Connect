<?php
include 'db_config.php';
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Access denied.";
    exit();
}
?>

<h2>📚 Assigned Subjects to Faculty</h2>
<a href="admin_dashboard.php">🏠 Back to Dashboard</a><br><br>

<table border="1" cellpadding="10">
    <tr style="background-color: #f2f2f2;">
        <th>Faculty Name</th>
        <th>Email</th>
        <th>Subject</th>
        <th>Year</th>
        <th>Branch</th>
        <th>Section</th>
    </tr>

    <?php
    $sql = "SELECT u.name, u.email, f.subject, f.year, f.branch, f.section
            FROM faculty_subjects f
            JOIN users u ON f.faculty_id = u.id
            ORDER BY u.name";

    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['email']}</td>";
            echo "<td>{$row['subject']}</td>";
            echo "<td>{$row['year']}</td>";
            echo "<td>{$row['branch']}</td>";
            echo "<td>{$row['section']}</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No assignments found.</td></tr>";
    }
    ?>
</table>
