<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"]) && is_numeric($_POST["id"])) {
     $id = (int) $_POST["id"];
     $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
     $stmt->bind_param("i", $id);
     $stmt->execute();
}

header("Location: index.php");
exit();
?>