<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bids";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    $sql = "SELECT product_name, starting_bid, product_description, product_image FROM product WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("Product not found.");
    }

    $highestBidQuery = "SELECT MAX(bid_amount) AS highest_bid FROM bids WHERE product_id = $product_id";
    $highestBidResult = $conn->query($highestBidQuery);
    $highestBid = $highestBidResult->fetch_assoc()['highest_bid'];
} else {
    die("No product ID specified.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bid on Product</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include "./include/header.php"; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <img src="./admin/<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
} echo htmlspecialchars($product['product_image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
            </div>
            <div class="col-md-6">
                <h2><?php echo htmlspecialchars($product['product_name']); ?></h2>
                <p>Starting bid: $<?php echo number_format($product['starting_bid'], 2); ?></p>
                
                <?php if ($highestBid !== null): ?>
                    <p><strong>Highest bid: $<?php echo number_format($highestBid, 2); ?></strong></p>
                <?php else: ?>
                    <p><strong>No bids yet.</strong></p>
                <?php endif; ?>

                <form action="placebid.php" method="POST" id="bidForm">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
                    <div class="mb-3">
                        <label for="bidderName" class="form-label">Your Name</label>
                        <?php if (isset($_SESSION['username'])): ?>
                            <input type="text" id="bidderName" name="bidder_name" class="form-control" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                        <?php else: ?>
                            <input type="text" id="bidderName" name="bidder_name" class="form-control" placeholder="Enter your name" required>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="bidAmount" class="form-label">Your Bid Amount</label>
                        <input type="number" id="bidAmount" name="bid_amount" class="form-control" placeholder="Enter your bid" step="0.01" required>
                    </div>
                    <button type="submit" class="btn btn-success">Place Bid</button>
                </form>
            </div>
        </div>
    </div>

    <hr>

    <div class="container mt-5">
        <h3 class="text-center mb-4">Bidding History</h3>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Bidder Name</th>
                    <th>Bid Amount</th>
                    <th>Bid Time</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT bidder_name, bid_amount, bid_time FROM bids WHERE product_id = $product_id ORDER BY bid_time DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '
                    <tr>
                        <td>' . htmlspecialchars($row['bidder_name']) . '</td>
                        <td>$' . number_format($row['bid_amount'], 2) . '</td>
                        <td>' . htmlspecialchars($row['bid_time']) . '</td>
                    </tr>';
                }
            } else {
                echo '<tr><td colspan="3">No bids yet.</td></tr>';
            }
            ?>
            </tbody>
        </table>
    </div>

    <hr>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Featured Products</h2>
        <div class="row">
            <?php
            $sql = "SELECT * FROM product WHERE showp = 1 AND id != $product_id ORDER BY created_at DESC LIMIT 6";
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
            ?>
        </div>
    </div>

    <?php include "./include/footer.php"; ?>
</body>
</html>
