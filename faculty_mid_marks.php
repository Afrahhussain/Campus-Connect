<?php
include 'db_config.php';

$faculty_id = $_SESSION['user']['id'];

if (!isset($_POST['submit_mid'])) {
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

<h3>📊 Upload Mid Exam Marks</h3>
<form method="POST">
    <label>Select Subject:</label>
    <select name="subject_key" required>
        <option value="">-- Select --</option>
        <?php foreach ($subjects as $key => $info) {
            echo "<option value='$key'>$key</option>";
        } ?>
    </select><br><br>

    <button type="submit" name="load_students">Load Students</button>
</form>

<?php
}

// Load students
if (isset($_POST['load_students'])) {
    list($subject, $year, $branch, $section) = explode(" | ", $_POST['subject_key']);

    $check = $conn->prepare("SELECT * FROM mid_marks WHERE faculty_id = ? AND subject = ? AND year = ? AND branch = ? AND section = ?");
    $check->bind_param("issss", $faculty_id, $subject, $year, $branch, $section);
    $check->execute();
    $res = $check->get_result();
    if ($res->num_rows > 0) {
        echo "<p style='color:red;'>❌ Mid exam marks already submitted for this subject and section.</p>";
    } else {
        $_SESSION['mid_data'] = compact('subject', 'year', 'branch', 'section');

        $stmt = $conn->prepare("SELECT * FROM students WHERE year = ? AND branch = ? AND section = ?");
        $stmt->bind_param("sss", $year, $branch, $section);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<form method='POST'>";
        echo "<input type='hidden' name='submit_mid' value='1'>";
        echo "<table border='1' cellpadding='8'>";
        echo "<tr><th>Name</th><th>Roll No</th><th>Mid Marks</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['roll_no']}</td>";
            echo "<td><input type='number' name='mid_marks[{$row['id']}]' min='0' max='100' required></td>";
            echo "</tr>";
        }

        echo "</table><br>";
        echo "<button type='submit'>✅ Submit Mid Marks</button>";
        echo "</form>";
    }
}

// Save mid marks
if (isset($_POST['submit_mid']) && isset($_SESSION['mid_data'])) {
    $d = $_SESSION['mid_data'];

    foreach ($_POST['mid_marks'] as $student_id => $marks) {
        $stmt = $conn->prepare("INSERT INTO mid_marks (student_id, subject, marks, faculty_id, year, branch, section) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isiiiss", $student_id, $d['subject'], $marks, $faculty_id, $d['year'], $d['branch'], $d['section']);
        $stmt->execute();
    }

    unset($_SESSION['mid_data']);
    echo "<p style='color:green;'>✅ Mid Exam Marks uploaded successfully!</p>";
}
?>
