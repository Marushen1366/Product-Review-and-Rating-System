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

$reviewStmt = $conn->prepare("SELECT rating, review_text FROM reviews WHERE product_id = ?");
$reviewStmt->bind_param("i", $id);
$reviewStmt->execute();
$reviews = $reviewStmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Product Details</title>
	<link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div id="product-details">
	<h2><?php echo htmlspecialchars($product['name']); ?></h2>
	<p><?php echo htmlspecialchars($product['description']); ?></p>
	<p>Category: <?php echo htmlspecialchars($product['category']); ?></p>
	<p><strong>Average Rating:</strong> <?php echo $product['avg_rating'] ? $product['avg_rating'] : 'No ratings'; ?></p>

	<h3>Reviews</h3>
	<?php
	if ($reviews->num_rows > 0) {
		while ($review = $reviews->fetch_assoc()) {
		echo "<p>" . $review['rating'] . "/5 - " . htmlspecialchars($review['review_text']) . "</p>";
		}
	} else {
		echo "<p>No reviews yet.</p>";
	}
	?>
</div>
<br>
<a href="add_review.php?id=<?php echo $id; ?>">Add Review</a><br>
<a href="index.php">Back to Products</a>
</body>
</html>