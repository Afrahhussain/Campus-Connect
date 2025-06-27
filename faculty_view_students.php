<?php
include 'db_config.php';

$faculty_id = $_SESSION['user']['id'];

// Step 1: Subject + student selection
if (!isset($_POST['view_marks'])) {
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

<h3>📖 View Student Academic Performance</h3>
<form method="POST">
    <label>Select Subject & Section:</label>
    <select name="subject_key" required>
        <option value="">-- Select --</option>
        <?php foreach ($subjects as $key => $info) {
            echo "<option value='$key'>$key</option>";
        } ?>
    </select><br><br>

    <label>Select Student:</label>
    <select name="student_id" required>
        <option value="">-- Select Subject First --</option>
    </select><br><br>

    <button type="submit" name="view_marks">🔍 View Marks</button>
</form>

<script>
document.querySelector("select[name='subject_key']").addEventListener("change", function () {
    var subjectKey = this.value;
    if (subjectKey) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "fetch_students_ajax.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            document.querySelector("select[name='student_id']").innerHTML = this.responseText;
        };
        xhr.send("subject_key=" + encodeURIComponent(subjectKey));
    }
});
</script>

<?php } ?>

<?php
// Step 2: Show student marks
if (isset($_POST['view_marks'])) {
    list($subject, $year, $branch, $section) = explode(" | ", $_POST['subject_key']);
    $student_id = $_POST['student_id'];

    // Get student info
    $stu = $conn->query("SELECT name, roll_no FROM students WHERE id=$student_id")->fetch_assoc();
    echo "<h4>📌 {$stu['name']} ({$stu['roll_no']})</h4>";
    echo "<h5>📘 Subject: $subject | Year: $year | $branch-$section</h5>";

    // Class Test Marks
    echo "<h4>📝 Class Test Marks</h4>";
    $res1 = $conn->query("SELECT test_no, marks FROM class_test WHERE student_id=$student_id AND subject='$subject' ORDER BY test_no");
    if ($res1->num_rows > 0) {
        echo "<ul>";
        while ($r = $res1->fetch_assoc()) {
            echo "<li>Test {$r['test_no']}: {$r['marks']} marks</li>";
        }
        echo "</ul>";
    } else {
        echo "No class test marks found.<br>";
    }

    // Mid Exam Marks
    echo "<h4>📊 Mid Exam Marks</h4>";
    $res2 = $conn->query("SELECT marks FROM mid_marks WHERE student_id=$student_id AND subject='$subject'");
    if ($r = $res2->fetch_assoc()) {
        echo "Mid Exam: {$r['marks']} marks";
    } else {
        echo "No mid exam marks found.";
    }
}
?>
