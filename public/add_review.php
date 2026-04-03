<?php
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    	die("Invalid product ID.");
}

$product_id = (int) $_GET['id'];
$error = "";
$productCheck = $conn->prepare("SELECT id, name FROM products WHERE id = ?");
$productCheck->bind_param("i", $product_id);
$productCheck->execute();
$product = $productCheck->get_result()->fetch_assoc();

if (!$product) {
    	die("Product not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$rating = isset($_POST["rating"]) ? (int) $_POST["rating"] : 0;
	$review_text = trim($_POST["review_text"] ?? "");

	if ($rating < 1 || $rating > 5) {
		$error = "Rating must be between 1 and 5.";
	} else {
		$stmt = $conn->prepare("
			INSERT INTO reviews (product_id, rating, review_text)
			VALUES (?, ?, ?)
		");
		$stmt->bind_param("iis", $product_id, $rating, $review_text);

		if ($stmt->execute()) {
			header("Location: product.php?id=" . $product_id);
			exit();
		} else {
			$error = "Error submitting review.";
		}
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Add Review - Product Review System</title>
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
			<h2>Add Review for <?php echo htmlspecialchars($product['name']); ?></h2>
			<?php if ($error): ?>
			<div class="message-error"><?php echo htmlspecialchars($error); ?></div>
			<?php endif; ?>
			<form method="POST" action="">
			<label>Rating</label>
			<select name="rating" required>
				<option value="">Select rating</option>
				<option value="1">1 - Poor</option>
				<option value="2">2 - Fair</option>
				<option value="3">3 - Good</option>
				<option value="4">4 - Very Good</option>
				<option value="5">5 - Excellent</option>
			</select>
			<label>Review</label>
			<textarea name="review_text" placeholder="Write your thoughts about this product..."></textarea>
			<button type="submit">Submit Review</button>
			</form>
			<a class="back-link" href="product.php?id=<?php echo $product_id; ?>">Back to Product</a>
		</div>
	</main>
</body>
</html>