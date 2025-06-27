<?php
include 'db_config.php';

$faculty_id = $_SESSION['user']['id'];

if (!isset($_POST['submit_marks'])) {
    // Get assigned subjects
    $query = $conn->prepare("SELECT DISTINCT subject, year, branch, section FROM faculty_subjects WHERE faculty_id = ?");
    $query->bind_param("i", $faculty_id);
    $query->execute();
    $result = $query->get_result();

    $subjects = [];
    while ($row = $result->fetch_assoc()) {
        $key = $row['subject'] . " | " . $row['year'] . " | " . $row['branch'] . " | " . $row['section'];
        $subjects[$key] = $row;
    }
?>

<h3>📝 Upload Class Test Marks</h3>
<form method="POST">
    <label>Select Subject:</label>
    <select name="subject_key" required>
        <option value="">-- Select --</option>
        <?php foreach ($subjects as $key => $info) {
            echo "<option value='$key'>$key</option>";
        } ?>
    </select><br><br>

    <label>Test Number:</label>
    <input type="number" name="test_no" min="1" max="5" required><br><br>

    <button type="submit" name="load_students">Load Students</button>
</form>

<?php
}

// Load student list
if (isset($_POST['load_students'])) {
    list($subject, $year, $branch, $section) = explode(" | ", $_POST['subject_key']);
    $test_no = $_POST['test_no'];

    // Check for existing marks
    $check = $conn->prepare("SELECT * FROM class_test WHERE faculty_id = ? AND subject = ? AND year = ? AND branch = ? AND section = ? AND test_no = ?");
    $check->bind_param("issssi", $faculty_id, $subject, $year, $branch, $section, $test_no);
    $check->execute();
    $res = $check->get_result();
    if ($res->num_rows > 0) {
        echo "<p style='color:red;'>❌ Marks already submitted for Test $test_no of this subject.</p>";
    } else {
        $_SESSION['test_data'] = compact('subject', 'year', 'branch', 'section', 'test_no');

        $stmt = $conn->prepare("SELECT * FROM students WHERE year = ? AND branch = ? AND section = ?");
        $stmt->bind_param("sss", $year, $branch, $section);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<form method='POST'>";
        echo "<input type='hidden' name='submit_marks' value='1'>";
        echo "<table border='1' cellpadding='8'>";
        echo "<tr><th>Name</th><th>Roll No</th><th>Marks</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['roll_no']}</td>";
            echo "<td><input type='number' name='marks[{$row['id']}]' min='0' max='100' required></td>";
            echo "</tr>";
        }

        echo "</table><br>";
        echo "<button type='submit'>✅ Submit Marks</button>";
        echo "</form>";
    }
}

// Submit marks
if (isset($_POST['submit_marks']) && isset($_SESSION['test_data'])) {
    $d = $_SESSION['test_data'];
    foreach ($_POST['marks'] as $student_id => $marks) {
        $stmt = $conn->prepare("INSERT INTO class_test (student_id, subject, test_no, marks, faculty_id, year, branch, section) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isisiiss", $student_id, $d['subject'], $d['test_no'], $marks, $faculty_id, $d['year'], $d['branch'], $d['section']);
        $stmt->execute();
    }
    unset($_SESSION['test_data']);
    echo "<p style='color:green;'>✅ Marks submitted successfully!</p>";
}
?>
