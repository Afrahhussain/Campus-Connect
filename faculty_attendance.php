<?php
include 'db_config.php';

if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'faculty') {
    echo "Access denied.";
    exit();
}

$faculty_id = $_SESSION['user']['id'];

if (!isset($_POST['submit_attendance'])) {
    // Step 1: Load assigned subjects for this faculty
    $query = $conn->prepare("SELECT DISTINCT subject, year, branch, section FROM faculty_subjects WHERE faculty_id = ?");
    $query->bind_param("i", $faculty_id);
    $query->execute();
    $result = $query->get_result();

    $subjects = [];
    while ($row = $result->fetch_assoc()) {
        $key = "{$row['subject']} | {$row['year']} | {$row['branch']} | {$row['section']}";
        $subjects[$key] = $row;
    }
?>

<h3>📋 Mark Attendance</h3>
<form method="POST">
    <label>Select Subject & Section:</label>
    <select name="subject_key" required>
        <option value="">-- Select --</option>
        <?php foreach ($subjects as $key => $info) {
            echo "<option value='$key'>$key</option>";
        } ?>
    </select><br><br>

    <label>Select Date:</label>
    <input type="date" name="date" required><br><br>

    <button type="submit" name="load_students">📄 Load Students</button>
</form>

<?php } ?>

<?php
// Step 2: Load students if subject+section+date selected
if (isset($_POST['load_students']) && isset($_POST['subject_key']) && isset($_POST['date'])) {
    list($subject, $year, $branch, $section) = explode(" | ", $_POST['subject_key']);
    $date = $_POST['date'];

    // Step 3: Check if already submitted
    $stmt = $conn->prepare("SELECT * FROM attendance WHERE faculty_id = ? AND subject = ? AND year = ? AND branch = ? AND section = ? AND date = ?");
    $stmt->bind_param("isssss", $faculty_id, $subject, $year, $branch, $section, $date);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        echo "<p style='color:red;'>❌ Attendance already marked for <b>$subject</b> on <b>$date</b>.</p>";
    } else {
        // Step 4: Load students to mark attendance
        $stmt = $conn->prepare("SELECT * FROM students WHERE year = ? AND branch = ? AND section = ?");
        $stmt->bind_param("sss", $year, $branch, $section);
        $stmt->execute();
        $students = $stmt->get_result();

        echo "<form method='POST'>";
        echo "<input type='hidden' name='submit_attendance' value='1'>";
        echo "<input type='hidden' name='subject' value='$subject'>";
        echo "<input type='hidden' name='year' value='$year'>";
        echo "<input type='hidden' name='branch' value='$branch'>";
        echo "<input type='hidden' name='section' value='$section'>";
        echo "<input type='hidden' name='date' value='$date'>";

        echo "<table border='1' cellpadding='8'>";
        echo "<tr><th>Name</th><th>Roll No</th><th>Status</th></tr>";

        while ($row = $students->fetch_assoc()) {
            $id = $row['id'];
            echo "<tr>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['roll_no']}</td>";
            echo "<td>
                    <label><input type='radio' name='status[$id]' value='Present' required> Present</label>
                    <label><input type='radio' name='status[$id]' value='Absent'> Absent</label>
                  </td>";
            echo "</tr>";
        }

        echo "</table><br>";
        echo "<button type='submit'>✅ Submit Attendance</button>";
        echo "</form>";
    }
}

// Step 5: Final submission → Insert into attendance table
if (isset($_POST['submit_attendance'])) {
    $subject = $_POST['subject'];
    $year = $_POST['year'];
    $branch = $_POST['branch'];
    $section = $_POST['section'];
    $date = $_POST['date'];

    foreach ($_POST['status'] as $student_id => $status) {
        $stmt = $conn->prepare("INSERT INTO attendance (student_id, date, status, subject, year, branch, section, faculty_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssi", $student_id, $date, $status, $subject, $year, $branch, $section, $faculty_id);
        $stmt->execute();
    }

    echo "<p style='color:green;'>✅ Attendance marked successfully!</p>";
}
?>

