<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bids";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo "<script>
        alert('Connection failed: " . addslashes($conn->connect_error) . "');
        window.location.href = 'main.php';
    </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $bidder_name = trim($_POST['bidder_name']);
    $bid_amount = floatval($_POST['bid_amount']);

    if (!isset($_SESSION['user_id'])) {
        echo "<script>
            alert('You must be logged in to place a bid.');
            window.location.href = 'main.php';
        </script>";
        exit();
    }

    $user_id = $_SESSION['user_id'];

    if (empty($bidder_name) || $bid_amount <= 0) {
        echo "<script>
            alert('Invalid input. Please check your bid details.');
            window.location.href = 'product.php?product_id=$product_id';
        </script>";
        exit();
    }

    $sql = "SELECT MAX(bid_amount) AS highest_bid FROM bids WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $highest_bid = $result->num_rows > 0 ? $result->fetch_assoc()['highest_bid'] : 0;

    if ($bid_amount <= $highest_bid) {
        echo "<script>
            alert('Your bid must be higher than the current highest bid of $" . number_format($highest_bid, 2) . "');
            window.location.href = 'product.php?id=$product_id';
        </script>";
        exit();
    }

    $sql = "INSERT INTO bids (product_id, bidder_name, bid_amount, user_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isdi", $product_id, $bidder_name, $bid_amount, $user_id);

    if ($stmt->execute()) {
        echo "<script>
            alert('Your bid was placed successfully!');
            window.location.href = 'main.php';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('Error placing your bid: " . addslashes($stmt->error) . "');
            window.location.href = 'product.php?product_id=$product_id';
        </script>";
    }

    $stmt->close();
}

$conn->close();
?>
