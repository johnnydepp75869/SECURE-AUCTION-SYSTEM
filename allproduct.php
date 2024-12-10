<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bid on Product</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .product-card {
            margin-top: 20px;
        }
        .sidebar-title {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .filter-link {
            cursor: pointer;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const filterLinks = document.querySelectorAll(".filter-link");
            filterLinks.forEach(link => {
                link.addEventListener("click", event => {
                    event.preventDefault();
                    filterProducts(event.target.dataset.category);
                });
            });
        });

        function filterProducts(category) {
            const products = document.querySelectorAll('.product-card');
            products.forEach(product => {
                product.style.display = category === 'all' || product.dataset.category === category ? 'block' : 'none';
            });
        }
    </script>
</head>
<body>
    <?php include "./include/header.php"; ?>

    <div class="container mt-5">
        <div class="row">
            <aside class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="sidebar-title">Filter by Category</h5>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none filter-link" data-category="all">All</a>
                            </li>
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none filter-link" data-category="shoes">Shoes</a>
                            </li>
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none filter-link" data-category="cars">Cars</a>
                            </li>
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none filter-link" data-category="electronics">Electronics</a>
                            </li>
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none filter-link" data-category="accessories">accessories</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>
            <main class="col-md-9">
                <h2 class="text-center mb-4">Products</h2>
                <div class="row">
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "bids";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT * FROM product WHERE showp = 1";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $product_name = $row['product_name'];
                            $starting_bid = $row['starting_bid'];
                            $product_description = $row['product_description'];
                            $product_image = $row['product_image'];
                            $category = $row['category'];

                            echo '
                            <div class="col-md-4 product-card" data-category="' . htmlspecialchars($category) . '">
                                <div class="card">
                                    <img src="./admin/' . htmlspecialchars($product_image) . '" class="card-img-top" alt="' . htmlspecialchars($product_name) . '">
                                    <div class="card-body">
                                        <h5 class="card-title">' . htmlspecialchars($product_name) . '</h5>
                                        <p class="card-text">Starting bid: $' . number_format($starting_bid, 2) . '</p>
                                        <a href="product.php?id=' . $row['id'] . '" class="btn btn-primary">Bid Now</a>
                                    </div>
                                </div>
                            </div>';
                        }
                    } else {
                        echo '<p>No products found.</p>';
                    }

                    $conn->close();
                    ?>
                </div>
            </main>
        </div>
    </div>

    <?php include "./include/footer.php"; ?>
</body>
</html>
