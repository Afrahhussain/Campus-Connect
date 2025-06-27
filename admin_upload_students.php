<?php
include 'db_config.php';

if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Access denied.";
    exit();
}
?>

<h2>📤 Upload Students via CSV</h2>
<a href="admin_dashboard.php">🏠 Back to Dashboard</a><br><br>

<form method="POST" enctype="multipart/form-data">
    <label>Select CSV File:</label>
    <input type="file" name="file" accept=".csv" required>
    <br><br>
    <button type="submit" name="upload">📤 Upload Students</button>
</form>

<?php
if (isset($_POST['upload']) && isset($_FILES['file']['tmp_name'])) {
    if ($_FILES['file']['error'] === 0) {

        $file = fopen($_FILES['file']['tmp_name'], "r");
        fgetcsv($file); // Skip header row
        $count = 0;

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) < 5) continue;

            list($name, $email, $year, $branch, $section) = $row;

            // Skip invalid emails
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;

            $roll_no = strtoupper(substr($branch, 0, 3)) . rand(100, 999);
            $added_by = $_SESSION['user']['id'];
            $password = password_hash("1234", PASSWORD_DEFAULT);

            // Insert into students table
            $stmt1 = $conn->prepare("INSERT INTO students (name, email, year, branch, section, roll_no, added_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt1->bind_param("ssssssi", $name, $email, $year, $branch, $section, $roll_no, $added_by);
            $stmt1->execute();

            // Insert into users table
            $stmt2 = $conn->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'student', 'approved')");
            $stmt2->bind_param("sss", $name, $email, $password);
            $stmt2->execute();

            $count++;
        }

        fclose($file);
        echo "<p style='color: green;'>✅ Successfully uploaded $count students.</p>";

    } else {
        echo "<p style='color: red;'>❌ File upload error. Please try again.</p>";
    }
}
?>
