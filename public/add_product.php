<?php
include 'db.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$name = trim($_POST["name"] ?? "");
	$description = trim($_POST["description"] ?? "");
	$category = trim($_POST["category"] ?? "");
	$imageName = null;

	if ($name === "") {
		$error = "Product name is required.";
	}

	if (!$error && isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
		$allowed = ["jpg", "jpeg", "png", "gif", "webp"];
		$originalName = $_FILES["image"]["name"];
		$tmpName = $_FILES["image"]["tmp_name"];
		$ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

		if (!in_array($ext, $allowed, true)) {
			$error = "Only JPG, JPEG, PNG, GIF, and WEBP files are allowed.";
		} elseif ($_FILES["image"]["size"] > 2 * 1024 * 1024) {
			$error = "Image must be under 2MB.";
		} else {
			$safeBaseName = preg_replace("/[^A-Za-z0-9_\-\.]/", "_", $originalName);
			$imageName = time() . "_" . $safeBaseName;
			$destination = "uploads/" . $imageName;

			if (!move_uploaded_file($tmpName, $destination)) {
				$error = "Image upload failed.";
			}
		}
	}

	if (!$error) {
		$stmt = $conn->prepare("
			INSERT INTO products (name, description, category, image)
			VALUES (?, ?, ?, ?)
		");
		$stmt->bind_param("ssss", $name, $description, $category, $imageName);

		if ($stmt->execute()) {
			header("Location: index.php");
			exit();
		} else {
			$error = "Error adding product.";
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Add Product - Product Review System</title>
	<link rel="stylesheet" href="css/styles.css">
</head>
<body>
	<header class="site-header">
		<div class="container">
			<a href="index.php" class="logo">ProductReview</a>
			<nav class="nav-links">
				<a href="index.php">Home</a>
				<a href="add_product.php" class="nav-button">Add Product</a>
			</nav>
		</div>
	</header>

	<main class="container main-content">
		<div class="form-card">
			<h2>Add New Product</h2>

			<?php if ($error): ?>
			<div class="message-error"><?php echo htmlspecialchars($error); ?></div>
			<?php endif; ?>

			<form method="POST" action="" enctype="multipart/form-data">
			<label>Product Name</label>
			<input type="text" name="name" required>

			<label>Description</label>
			<textarea name="description" placeholder="Write a short description..."></textarea>

			<label>Category</label>
			<input type="text" name="category" placeholder="e.g. Electronics, Books, Accessories">

			<label>Product Image</label>
			<input type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp">

			<button type="submit">Add Product</button>
			</form>

			<a class="back-link" href="index.php">Back to Home</a>
		</div>
	</main>
</body>
</html>

