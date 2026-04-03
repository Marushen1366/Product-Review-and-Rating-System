<?php
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
	die("Invalid product ID.");
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("
	SELECT p.*, ROUND(AVG(r.rating), 1) AS avg_rating
	FROM products p
	LEFT JOIN reviews r ON p.id = r.product_id
	WHERE p.id = ?
	GROUP BY p.id
");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    	die("Product not found.");
}

$reviewStmt = $conn->prepare("
	SELECT rating, review_text, created_at
	FROM reviews
	WHERE product_id = ?
	ORDER BY id DESC
");
$reviewStmt->bind_param("i", $id);
$reviewStmt->execute();
$reviews = $reviewStmt->get_result();

$avg = $product['avg_rating'] ? $product['avg_rating'] : 'No ratings';
$avgNum = is_numeric($avg) ? round($avg) : 0;
$stars = is_numeric($avg) ? str_repeat("★", $avgNum) . str_repeat("☆", 5 - $avgNum) : "☆☆☆☆☆";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo htmlspecialchars($product['name']); ?> - Product Review System</title>
	<link rel="stylesheet" href="css/styles.css">
</head>
<body>
	<header class="site-header">
		<div class="container">
			<a href="index.php" class="logo">Product Review</a>
			<nav class="nav-links">
			<a href="index.php">Home</a>
			<a href="add_product.php" class="nav-button">Add Product</a>
			</nav>
		</div>
	</header>

	<main class="container main-content">
		<div class="product-page">
			<div class="product-media">
				<?php if (!empty($product['image'])): ?>
					<img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product image">
				<?php else: ?>
					<img src="images/default-product.png" alt="Default product image">
				<?php endif; ?>
			</div>

			<div class="product-info">
				<h2><?php echo htmlspecialchars($product['name']); ?></h2>
				<p class="muted">Category: <?php echo htmlspecialchars($product['category']); ?></p>
				<p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
				<p class="rating"><span class="stars"><?php echo $stars; ?></span> (<?php echo $avg; ?>)</p>

				<div class="product-actions">
					<a class="button" href="add_review.php?id=<?php echo $id; ?>">Add Review</a>
					<a class="button" href="index.php">Back</a>

					<form method="POST" action="delete_product.php" onsubmit="return confirm('Delete this product?');">
					<input type="hidden" name="id" value="<?php echo $id; ?>">
					<button type="submit" class="button-danger">Delete Product</button>
					</form>
				</div>
			</div>
		</div>

		<div class="review-list">
			<h3>Customer Reviews</h3>

			<?php if ($reviews->num_rows > 0): ?>
			<?php while ($review = $reviews->fetch_assoc()): ?>
				<div class="review-item">
				<div class="review-rating">
				<?php
				$reviewStars = str_repeat("★", (int) $review['rating']) . str_repeat("☆", 5 - (int) $review['rating']);
				echo "<span class='stars'>$reviewStars</span> (" . (int) $review['rating'] . "/5)";
				?>
				</div>
				<div><?php echo htmlspecialchars($review['review_text']); ?></div>
				<div class="muted" style="margin-top:8px; font-size:0.9rem;">
				Posted: <?php echo htmlspecialchars($review['created_at']); ?>
				</div>
				</div>
			<?php endwhile; ?>
			<?php else: ?>
			<p class="muted">No reviews yet.</p>
			<?php endif; ?>
		</div>
	</main>
</body>
</html>