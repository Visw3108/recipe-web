<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "recipeweb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the URL from the database
$sql = "SELECT url FROM urllist WHERE status = 'Active' LIMIT 1"; // Adjust your query as needed
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['url' => $row['url']]);
} else {
    echo json_encode(['url' => '']);
}

$conn->close();
?>
