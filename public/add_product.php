<?php
include 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$name = trim($_POST["name"] ?? "");
	$description = trim($_POST["description"] ?? "");
	$category = trim($_POST["category"] ?? "");
	if ($name === "") {
		$error = "Product name is required.";
	}
	else {
		$stmt = $conn->prepare("INSERT INTO products (name, description, category) VALUES (?, ?, ?)");
		$stmt->bind_param("sss", $name, $description, $category);
		if ($stmt->execute()) {
			header("Location: index.php");
			exit();
		}
		else {
			$error = "Error adding product.";
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Add Product</title>
	<link rel="stylesheet" href="css/styles.css">
</head>
<body>

<h2>Add New Product</h2>

<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST" action="">
	<label>Product Name</label><br>
	<input type="text" name="name" required><br><br>

	<label>Description</label><br>
	<textarea name="description"></textarea><br><br>

	<label>Category</label><br>
	<input type="text" name="category"><br><br>

	<button type="submit">Submit</button>
</form>
<br>
<a href="index.php">⬅ Back</a>
</body>
</html>