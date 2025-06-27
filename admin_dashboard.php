<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Access denied.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 230px;
            height: 100vh;
            background-color: #2c2f33;
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
            background-color: #40444b;
        }
        .content {
            margin-left: 250px;
            padding: 25px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="?page=upload">📂 Upload Students</a>
    <a href="?page=edit">✏️ Edit Students</a>
    <a href="?page=faculty">👩‍🏫 View Faculty</a>
    <a href="?page=assign">📚 Assign Faculty Subjects</a>
    <a href="?page=assigned">🧾 View Assigned Subjects</a>
    <a href="?page=pending">🕓 Pending Users</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="content">
    <h2>Welcome, <?php echo $_SESSION['user']['name']; ?></h2>

    <?php
    if (isset($_GET['page'])) {
        $page = $_GET['page'];

        if ($page === 'upload') {
            include 'admin_upload_students.php';
        } elseif ($page === 'edit') {
            include 'admin_edit_students.php';
        } elseif ($page === 'faculty') {
            include 'admin_view_faculty.php';
        } elseif ($page === 'assign') {
            include 'admin_assign_subjects.php';
        } elseif ($page === 'assigned') {
            include 'admin_view_assigned_subjects.php';
        } elseif ($page === 'pending') {
            include 'admin_pending.php';
        } else {
            echo "<p>⚠️ Invalid page requested.</p>";
        }
    } else {
        echo "<p>Select an action from the sidebar.</p>";
    }
    ?>
</div>

</body>
</html>

