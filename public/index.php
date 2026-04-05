<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Product Review System</title>
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
		<section class="hero">
			<h1>Discover and Review Products</h1>
			<p>Browse products, view average ratings, upload product images, and share feedback through a full-stack review platform.</p>
		</section>

		<div class="section-header">
			<h2>All Products</h2>
			<a class="button" href="add_product.php">Add New Product</a>
		</div>

		<div class="grid">
			<?php
			$sql = "
				SELECT p.id, p.name, p.category, p.image,
					ROUND(AVG(r.rating), 1) AS avg_rating
				FROM products p
				LEFT JOIN reviews r ON p.id = r.product_id
				GROUP BY p.id, p.name, p.category, p.image
				ORDER BY p.created_at DESC
			";
			$result = $conn->query($sql);

			if ($result && $result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
				$avg = $row['avg_rating'] ? $row['avg_rating'] : 'No ratings';
				$avgNum = is_numeric($avg) ? round($avg) : 0;
				$stars = is_numeric($avg) ? str_repeat("★", $avgNum) . str_repeat("☆", 5 - $avgNum) : "☆☆☆☆☆";

				echo "<div class='card'>";

				if (!empty($row['image'])) {
				$uploadPath = "uploads/" . $row['image'];
				$seedPath = "images/" . $row['image'];

				if (file_exists($uploadPath)) {
					echo "<img class='card-image' src='" . htmlspecialchars($uploadPath) . "' alt='Product image'>";
				} elseif (file_exists($seedPath)) {
					echo "<img class='card-image' src='" . htmlspecialchars($seedPath) . "' alt='Product image'>";
				} else {
					echo "<img class='card-image' src='images/default-product.jpg' alt='Default product image'>";
				}
			} 
				else {
					echo "<img class='card-image' src='images/default-product.jpg' alt='Default product image'>";
				}

				echo "<div class='card-body'>";
				echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
				echo "<p class='muted'>Category: " . htmlspecialchars($row['category']) . "</p>";
				echo "<p class='rating'><span class='stars'>$stars</span> ($avg)</p>";
				echo "<a class='button' href='product.php?id=" . $row['id'] . "'>View Details</a>";
				echo "</div>";

				echo "</div>";
				}
			} else {
				echo "<div class='empty-state'>No products found yet. Add your first product.</div>";
			}
			?>
		</div>
	</main>
</body>
</html>