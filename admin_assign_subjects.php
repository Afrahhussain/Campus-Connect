<?php
include 'db_config.php'; // session already started in admin_dashboard.php

// ✅ Role check
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Access denied.";
    exit();
}

// ✅ Handle subject assignment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $faculty_id = $_POST['faculty_id'];
    $subject = $_POST['subject'];
    $year = $_POST['year'];
    $branch = $_POST['branch'];
    $section = $_POST['section'];

    $stmt = $conn->prepare("INSERT INTO faculty_subjects (faculty_id, subject, year, branch, section) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $faculty_id, $subject, $year, $branch, $section);
    
    if ($stmt->execute()) {
        echo "<p style='color: green;'>✅ Subject assigned successfully.</p>";
    } else {
        echo "<p style='color: red;'>❌ Error: " . $stmt->error . "</p>";
    }
}

// ✅ Get all faculty users (approved)
$faculties = $conn->query("SELECT id, name FROM users WHERE role = 'faculty' AND status = 'approved'");
?>

<h2>📚 Assign Faculty Subject</h2>
<a href="admin_dashboard.php">🏠 Back to Dashboard</a>
<br><br>

<form method="POST">
    <label>Faculty:</label>
    <select name="faculty_id" required>
        <option value="">-- Select Faculty --</option>
        <?php while ($row = $faculties->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['name']}</option>";
        } ?>
    </select><br><br>

    <label>Subject:</label>
    <input type="text" name="subject" required><br><br>

    <label>Year:</label>
    <select name="year" required>
        <option value="">-- Select Year --</option>
        <option value="1st">1st</option>
        <option value="2nd">2nd</option>
        <option value="3rd">3rd</option>
    </select><br><br>

    <label>Branch:</label>
    <select name="branch" required>
        <option value="">-- Select Branch --</option>
        <option value="CSE">CSE</option>
        <option value="ECE">ECE</option>
        <option value="EEE">EEE</option>
    </select><br><br>

    <label>Section:</label>
    <select name="section" required>
        <option value="">-- Select Section --</option>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
    </select><br><br>

    <button type="submit">➕ Assign Subject</button>
</form>

