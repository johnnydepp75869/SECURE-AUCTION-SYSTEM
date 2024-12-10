<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bids";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['bid_id'])) {
        $bidId = intval($_POST['bid_id']);

        $updateSql = "UPDATE bids SET payment_status = 'Done' WHERE id = $bidId";

        if ($conn->query($updateSql) === TRUE) {
            echo "<script>
                    alert('Payment status updated successfully.');
                    window.location.href = 'bids.php';
                  </script>";
            exit();
        } else {
            echo "<script>
                    alert('Error updating payment status: " . $conn->error . "');
                    window.location.href = 'bids.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Invalid bid ID.');
                window.location.href = 'index.php';
              </script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Payment Status</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Update Payment Status</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="bid_id">Bid ID</label>
                <input type="number" class="form-control" id="bid_id" name="bid_id" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Payment Status</button>
        </form>
    </div>
</body>
</html>
