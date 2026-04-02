<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "product_review_system";
$port = 3306;
$conn = new mysqli($host, $user, $pass, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
