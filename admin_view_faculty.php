<?php
include 'db_config.php';

echo "<h3>All Faculty Users</h3>";

$result = $conn->query("SELECT * FROM users WHERE role = 'faculty' ORDER BY name");

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='6'>
            <tr><th>Name</th><th>Email</th><th>Status</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['status']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No faculty found.</p>";
}
?>
