<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Product Review</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
	<h1> Product Review and Rating System</h1>
	<a href="add_product.php">Add Product</a>
	<div id="product-list">
		<?php
		$sql = "SELECT p.id, p.name, p.category, ROUND(AVG(r.rating), 1) AS avg_rating
				FROM products p
				LEFT JOIN reviews r ON p.id = r.product_id
				GROUP BY p.id, p.name, p.category
				ORDER BY p.created_at DESC";
		$result = $conn->query($sql);
		if ($result && $result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$avg = $row['avg_rating'] ? $row['avg_rating'] : 'No ratings yet';
				echo "<div class='product'>";
				echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
				echo "<p>Category: " . htmlspecialchars($row['category']) . "</p>";
				echo "<p>Average Rating: " . $avg . "</p>";
				echo "<a href='product.php?id=" . $row['id'] . "'>View Details</a>";
				echo "</div>";
			}
		} else {
			echo "<p>No products found.</p>";
		}
		?>
	</div>
</body>
</html>