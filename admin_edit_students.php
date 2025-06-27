<h2>✏️ Edit Student Details</h2>
<a href="admin_dashboard.php">🏠 Back to Dashboard</a>
<br><br>

<?php
include 'db_config.php'; // no session_start() needed again

$result = $conn->query("SELECT * FROM students");

if ($result->num_rows === 0) {
    echo "⚠️ No student records found.";
} else {
    echo "<table border='1' cellpadding='8'>
        <tr>
            <th>Name</th><th>Email</th><th>Year</th><th>Branch</th><th>Section</th><th>Roll No</th><th>Actions</th>
        </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <form method='POST'>
            <input type='hidden' name='id' value='{$row['id']}'>
            <td><input type='text' name='name' value='{$row['name']}'></td>
            <td><input type='email' name='email' value='{$row['email']}'></td>
            <td><input type='text' name='year' value='{$row['year']}'></td>
            <td><input type='text' name='branch' value='{$row['branch']}'></td>
            <td><input type='text' name='section' value='{$row['section']}'></td>
            <td><input type='text' name='roll_no' value='{$row['roll_no']}'></td>
            <td>
                <button type='submit' name='update'>💾 Save</button>
                <button type='submit' name='delete' onclick='return confirm(\"Delete this student?\")'>❌</button>
            </td>
            </form>
        </tr>";
    }

    echo "</table>";
}

// Handle Update/Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    if (isset($_POST['update'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $year = $_POST['year'];
        $branch = $_POST['branch'];
        $section = $_POST['section'];
        $roll_no = $_POST['roll_no'];

        $stmt = $conn->prepare("UPDATE students SET name=?, email=?, year=?, branch=?, section=?, roll_no=? WHERE id=?");
        $stmt->bind_param("ssssssi", $name, $email, $year, $branch, $section, $roll_no, $id);
        $stmt->execute();

        echo "<meta http-equiv='refresh' content='0'>"; // refresh
    }

    if (isset($_POST['delete'])) {
        $conn->query("DELETE FROM students WHERE id=$id");
        $conn->query("DELETE FROM users WHERE email='{$_POST['email']}'");
        echo "<meta http-equiv='refresh' content='0'>";
    }
}
?>
