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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$rating = isset($_POST["rating"]) ? (int) $_POST["rating"] : 0;
	$review_text = trim($_POST["review_text"] ?? "");

	if ($rating < 1 || $rating > 5) {
		$error = "Rating must be between 1 and 5.";
	} else {
		$stmt = $conn->prepare("INSERT INTO reviews (product_id, rating, review_text) VALUES (?, ?, ?)");
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
<html>
<head>
	<meta charset="UTF-8">
	<title>Add Review</title>
	<link rel="stylesheet" href="css/styles.css">
</head>
<body>

<h2>Submit Review for <?php echo htmlspecialchars($product['name']); ?></h2>

<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST" action="">
	<label>Rating *</label><br>
	<select name="rating" required>
		<option value="">Select rating</option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
	</select><br><br>

	<label>Review (optional)</label><br>
	<textarea name="review_text"></textarea><br><br>

	<button type="submit">Submit</button>
</form>
<br>
<a href="product.php?id=<?php echo $product_id; ?>">⬅ Back</a>
</body>
</html>