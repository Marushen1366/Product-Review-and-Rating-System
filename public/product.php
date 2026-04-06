<?php
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    	header("Location: index.php");
    	exit();
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
    	header("Location: index.php");
    	exit();
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
	<title><?php echo htmlspecialchars($product['name']); ?></title>
	<link rel="stylesheet" href="css/styles.css">
</head>

<body>
<header class="site-header">
	<div class="container">
	<a href="index.php" class="logo">Product Reviews</a>
	<nav class="nav-links">
	<a href="index.php">Home</a>
	<a href="add_product.php" class="nav-button">Add Product</a>
	</nav>
	</div>
</header>

<main class="container main-content">
<div class="product-page">

<div class="product-media">
<?php
if (!empty($product['image'])) {
    	$uploadPath = "uploads/" . $product['image'];
    	$seedPath = "images/" . $product['image'];

    	if (file_exists($uploadPath)) {
       	echo '<img src="' . htmlspecialchars($uploadPath) . '">';
    	} elseif (file_exists($seedPath)) {
        	echo '<img src="' . htmlspecialchars($seedPath) . '">';
    	} else {
       	echo '<img src="images/default-product.jpg">';
    }
} else {
    	echo '<img src="images/default-product.jpg">';
}
?>
</div>

<div class="product-info">
	<h2><?php echo htmlspecialchars($product['name']); ?></h2>
	<p class="muted">Category: <?php echo htmlspecialchars($product['category']); ?></p>
	<p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
	<p class="rating"><?php echo $stars; ?> (<?php echo $avg; ?>)</p>

	<div class="product-actions">
		<a class="button" href="add_review.php?id=<?php echo $id; ?>">Add Review</a>
		<a class="button" href="index.php">Back</a>

		<form method="POST" action="delete_product.php">
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
			$reviewStars = str_repeat("★", (int)$review['rating']) . str_repeat("☆", 5 - (int)$review['rating']);
			echo "$reviewStars (" . (int)$review['rating'] . "/5)";
			?>
		</div>
		<div><?php echo htmlspecialchars($review['review_text']); ?></div>
	</div>
	<?php endwhile; ?>
	<?php else: ?>
	<p>No reviews yet.</p>
	<?php endif; ?>

</div>
</main>
</body>
</html>