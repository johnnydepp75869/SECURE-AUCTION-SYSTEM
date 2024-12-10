<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Bids</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include "./include/header.php"; ?>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('You must be logged in to view your bids.');
        window.location.href = 'main.php';
    </script>";
    exit();
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bids";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT b.id, p.product_image, p.product_description, b.bid_amount, b.bidder_name, b.bid_time, b.bid_status, b.payment_status, b.product_id
        FROM bids b
        JOIN product p ON b.product_id = p.id
        WHERE b.user_id = ? AND (b.id, b.bid_time) IN (
            SELECT MAX(id), MAX(bid_time)
            FROM bids
            WHERE user_id = ?
            GROUP BY product_id
        )";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5">
    <h4>Product and Bid Details</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Product Image</th>
                <th>Product Description</th>
                <th>Bid Amount</th>
                <th>Bidder Name</th>
                <th>Bid Time</th>
                <th>Payment Status</th>
                <th>Approved Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><img src='./admin/" . htmlspecialchars($row['product_image']) . "' class='img-fluid' alt='Product Image' style='width: 100px; height: auto;'></td>";
            echo "<td>" . htmlspecialchars($row['product_description']) . "</td>";
            echo "<td>$" . htmlspecialchars($row['bid_amount']) . "</td>";
            echo "<td>" . htmlspecialchars($row['bidder_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['bid_time']) . "</td>";
            echo "<td>" . htmlspecialchars($row['payment_status']) . "</td>";
            echo "<td>" . htmlspecialchars($row['bid_status']) . "</td>";

            if ($row['bid_status'] == "Approved" && $row['payment_status'] == 'Pending') {
                echo "<td><a href='payment_page.php?bid_id=" . $row['id'] . "' class='btn btn-success'>Pay Now</a></td>";
            } elseif ($row['bid_status'] == "Approved" && $row['payment_status'] == 'Done') {
                echo "<td>Finish</td>";
                $productId = $row['product_id'];
                $updateSql = "UPDATE product SET showp = '0' WHERE id = $productId";
                if ($conn->query($updateSql) === TRUE) {
                    echo "<script>console.log('Product showp updated successfully for product ID $productId');</script>";
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            } else {
                echo "<td>Not Won</td>";
            }

            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8' class='text-center'>No bids found</td></tr>";
    }
    ?>
        </tbody>
    </table>
</div>

<?php include "./include/footer.php"; ?>

</body>
</html>
