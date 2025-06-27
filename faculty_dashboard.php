<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'faculty') {
    echo "Access denied.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Faculty Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 230px;
            height: 100vh;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            padding: 20px;
        }
        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 20px;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: white;
            text-decoration: none;
            margin: 6px 0;
            border-radius: 4px;
        }
        .sidebar a:hover {
            background-color: #34495e;
        }
        .content {
            margin-left: 250px;
            padding: 25px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Faculty Panel</h2>
    <a href="?page=home">🏠 Home</a>
    <a href="?page=attendance">📅 Attendance</a>
    <a href="?page=class_test">📝 Class Test Marks</a>
    <a href="?page=mid_exam">📊 Mid Exam Marks</a>
    <a href="?page=view_student">📖 View Student Data</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="content">
    <h2>Welcome, <?php echo $_SESSION['user']['name']; ?></h2>

    <?php
    if (isset($_GET['page'])) {
        $page = $_GET['page'];

        if ($page === 'home') {
            echo "<p>Select an action from the sidebar.</p>";
        }
        elseif ($page === 'attendance') {
            include 'faculty_attendance.php'; // to be created
        }
        elseif ($page === 'class_test') {
            include 'faculty_class_test.php'; // to be created
        }
        elseif ($page === 'mid_exam') {
            include 'faculty_mid_marks.php'; // to be created
        }
        elseif ($page === 'view_student') {
            include 'faculty_view_students.php'; // to be created
        }
        else {
            echo "<p>Invalid page.</p>";
        }
    } else {
        echo "<p>Select an action from the sidebar.</p>";
    }
    ?>
</div>

</body>
</html>
