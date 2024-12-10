<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bidding Platform - Home</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bids";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    ?>
    
    <?php include "./include/header.php"; ?>

    <div class="container mt-5 text-center">
        <h1 class="display-4">Welcome to BidNow</h1>
        <p class="lead">Explore exclusive products and place your bids to win your favorite items.</p>
        <a href="product.php" class="btn btn-primary btn-lg">Start Bidding</a>
    </div>

    <div class="container mt-5">
        <h2 class="text-center mb-4">How It Works</h2>
        <div class="row text-center">
            <div class="col-md-4">
                <h5>1. Browse Products</h5>
                <p>Explore a variety of products across different categories.</p>
            </div>
            <div class="col-md-4">
                <h5>2. Place Your Bid</h5>
                <p>Enter your bid and compete to win the product.</p>
            </div>
            <div class="col-md-4">
                <h5>3. Win the Auction</h5>
                <p>Highest bidder wins! Secure your product quickly.</p>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Featured Products</h2>
        <div class="row">
            <?php
            $sql = "SELECT * FROM product WHERE showp = 1 ORDER BY created_at DESC LIMIT 6";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="./admin/'.$row['product_image'].'" class="card-img-top" alt="'.$row['product_name'].'">
                            <div class="card-body">
                                <h5 class="card-title">'.$row['product_name'].'</h5>
                                <p class="card-text">Starting bid: $'.$row['starting_bid'].'</p>
                                <a href="product.php?id='.$row['id'].'" class="btn btn-primary">Bid Now</a>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<p class="text-center">No featured products available at the moment.</p>';
            }
            $conn->close();
            ?>
        </div>
    </div>

    <?php include "./include/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
