// Mock product data
let products = [
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

// Calculate average rating
function getAverageRating(product) {
  if (product.reviews.length === 0) return "No ratings";
  const total = product.reviews.reduce((sum, r) => sum + r.rating, 0);
  return (total / product.reviews.length).toFixed(1);
}

// Render product list
if (document.getElementById("product-list")) {
  const list = document.getElementById("product-list");

  products.forEach(product => {
    const div = document.createElement("div");
    div.className = "product";
    div.innerHTML = `
      <h3>${product.name}</h3>
      <p>Category: ${product.category}</p>
      <p>Average Rating: ${getAverageRating(product)}</p>
      <a href="product.html">View Details</a>
    `;
    list.appendChild(div);
  });
}

// Add product
const productForm = document.getElementById("product-form");
if (productForm) {
  productForm.addEventListener("submit", e => {
    e.preventDefault();

    const name = document.getElementById("name").value.trim();
    if (!name) {
      alert("Product name is required.");
      return;
    }

    const newProduct = {
      id: products.length + 1,
      name,
      description: document.getElementById("description").value,
      category: document.getElementById("category").value,
      reviews: []
    };

    products.push(newProduct);
    alert("Product added!");
    window.location.href = "index.html";
  });
}

// Show product details
if (document.getElementById("product-details")) {
  const product = products[0]; // demo product

  const div = document.getElementById("product-details");
  div.innerHTML = `
    <h2>${product.name}</h2>
    <p>${product.description}</p>
    <p>Average Rating: ${getAverageRating(product)}</p>
    <h3>Reviews</h3>
    ${product.reviews.map(r => `<p>${r.rating}/5 - ${r.text}</p>`).join("")}
  `;
}

// Add review
const reviewForm = document.getElementById("review-form");
if (reviewForm) {
  reviewForm.addEventListener("submit", e => {
    e.preventDefault();

    const rating = document.getElementById("rating").value;
    if (!rating) {
      alert("Rating is required.");
      return;
    }

    products[0].reviews.push({
      rating: Number(rating),
      text: document.getElementById("review-text").value
    });

    alert("Review submitted!");
    window.location.href = "product.html";
  });
}
