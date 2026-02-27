// Load products from localStorage or default
let products = JSON.parse(localStorage.getItem("products")) || [
  {
    id: 1,
    name: "Wireless Mouse",
    description: "Ergonomic wireless mouse",
    category: "Electronics",
    reviews: [
      { rating: 5, text: "Excellent product!" },
      { rating: 4, text: "Very good value." }
    ]
  }
];

// Save products
function saveProducts() {
  localStorage.setItem("products", JSON.stringify(products));
}

// Get product ID from URL
function getProductId() {
  const params = new URLSearchParams(window.location.search);
  return Number(params.get("id"));
}

// Calculate average rating
function getAverageRating(product) {
  if (!product.reviews.length) return "No ratings";
  const total = product.reviews.reduce((sum, r) => sum + r.rating, 0);
  return (total / product.reviews.length).toFixed(1);
}

// PRODUCT LIST PAGE
if (document.getElementById("product-list")) {
  const list = document.getElementById("product-list");
  list.innerHTML = "";

  products.forEach(product => {
    const div = document.createElement("div");
    div.className = "product";
    div.innerHTML = `
      <h3>${product.name}</h3>
      <p>Category: ${product.category}</p>
      <p>Average Rating: ${getAverageRating(product)}</p>
      <a href="product.html?id=${product.id}">View Details</a>
    `;
    list.appendChild(div);
  });
}

// ADD PRODUCT
const productForm = document.getElementById("product-form");
if (productForm) {
  productForm.addEventListener("submit", e => {
    e.preventDefault();

    const name = document.getElementById("name").value.trim();
    if (!name) return alert("Product name is required.");

    const newProduct = {
      id: Date.now(),
      name,
      description: document.getElementById("description").value,
      category: document.getElementById("category").value,
      reviews: []
    };

    products.push(newProduct);
    saveProducts();

    alert("Product added!");
    window.location.href = "index.html";
  });
}

// PRODUCT DETAILS PAGE
if (document.getElementById("product-details")) {
  const id = getProductId();
  const product = products.find(p => p.id === id);

  if (!product) {
    document.getElementById("product-details").innerHTML = "<p>Product not found.</p>";
  } else {
    const div = document.getElementById("product-details");
    div.innerHTML = `
      <h2>${product.name}</h2>
      <p>${product.description}</p>
      <p>Category: ${product.category}</p>
      <p><strong>Average Rating:</strong> ${getAverageRating(product)}</p>
      <h3>Reviews</h3>
      ${product.reviews.length
        ? product.reviews.map(r => `<p>${r.rating}/5 - ${r.text}</p>`).join("")
        : "<p>No reviews yet.</p>"}
    `;

    // Pass correct product id to review page
    document.getElementById("add-review-link").href = `add_review.html?id=${product.id}`;
  }
}

// ADD REVIEW PAGE
const reviewForm = document.getElementById("review-form");
if (reviewForm) {
  const id = getProductId();
  const product = products.find(p => p.id === id);

  reviewForm.addEventListener("submit", e => {
    e.preventDefault();

    if (!product) return alert("Product not found.");

    const rating = document.getElementById("rating").value;
    if (!rating) return alert("Rating is required.");

    product.reviews.push({
      rating: Number(rating),
      text: document.getElementById("review-text").value
    });

    saveProducts();

    alert("Review submitted!");
    window.location.href = `product.html?id=${product.id}`;
  });
}
