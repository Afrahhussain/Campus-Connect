<?php
include 'db_config.php';
session_start();

if (isset($_POST['subject_key'])) {
    list($subject, $year, $branch, $section) = explode(" | ", $_POST['subject_key']);

    $stmt = $conn->prepare("SELECT id, name FROM students WHERE year=? AND branch=? AND section=?");
    $stmt->bind_param("sss", $year, $branch, $section);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        echo "<option value='{$row['id']}'>{$row['name']}</option>";
    }
}
?>
