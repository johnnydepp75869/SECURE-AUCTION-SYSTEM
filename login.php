<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bids";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (empty($username) || empty($email) || empty($password)) {
        echo "<div class='alert alert-warning'>All fields are required!</div>";
    } else {
        $sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "<div class='alert alert-warning'>Username or Email already exists!</div>";
        } else {
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success'>Registration successful!</div>";
            } else {
                echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['login_username']);
    $password = mysqli_real_escape_string($conn, $_POST['login_password']);

    if (empty($username) || empty($password)) {
        echo "<div class='alert alert-warning'>Both fields are required!</div>";
    } else {
        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header("Location: main.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Invalid password!</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>No user found with that username!</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .form-card {
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .toggle-btn {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="form-card bg-white">
            <div id="registerForm">
                <h2 class="text-center">Register</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                </form>
                <div class="toggle-btn">
                    <button class="btn btn-link" onclick="toggleForm()">Already have an account? Login</button>
                </div>
            </div>

            <div id="loginForm" style="display: none;">
                <h2 class="text-center">Login</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label for="login_username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="login_username" name="login_username" required>
                    </div>
                    <div class="mb-3">
                        <label for="login_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="login_password" name="login_password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-success w-100">Login</button>
                </form>
                <div class="toggle-btn">
                    <button class="btn btn-link" onclick="toggleForm()">Don't have an account? Register</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleForm() {
            const registerForm = document.getElementById('registerForm');
            const loginForm = document.getElementById('loginForm');
            if (registerForm.style.display === 'none') {
                registerForm.style.display = 'block';
                loginForm.style.display = 'none';
            } else {
                registerForm.style.display = 'none';
                loginForm.style.display = 'block';
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
