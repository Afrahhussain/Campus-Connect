<?php
// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db_config.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND status = 'approved'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists and password is correct
    if ($user = $result->fetch_assoc()) {
        if (!empty($user['password']) && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;

            // Redirect based on role
            switch ($user['role']) {
                case 'faculty':
                    header("Location: faculty_dashboard.php");
                    break;
                case 'incharge':
                    header("Location: incharge_dashboard.php");
                    break;
                case 'hod':
                    header("Location: hod_dashboard.php");
                    break;
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                default:
                    echo "Unknown role assigned to user.";
                    exit();
            }
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Invalid email or account not approved.";
    }

    $stmt->close();
} else {
    echo "Please submit the form properly.";
}
?>

