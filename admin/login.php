<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cookinghabitDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Placeholder for login process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    
    // Check if "password" key exists in $_POST and is not empty
    $password = isset($_POST["password"]) ? $_POST["password"] : '';
    if (empty($password)) {
    echo "Login failed. Please enter a password.";
    // You may choose to redirect or handle this case as appropriate for your application
    exit;
}
}

$sql = "SELECT password_hash FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashedPassword = $row["password_hash"];

    if (password_verify($password, $hashedPassword)) {
        echo "Login successful! Redirect to another page.";
        if (password_verify($password, $hashedPassword)) {
            echo "Login successful! Redirecting to another page...";
            // Add redirection code here
            header("Location: dashboard.php");
            exit; // Ensure that no further code is executed after redirection
        } else {
            echo "Login failed. Invalid email or password.";
        }
    } else {
        echo "Login failed. Invalid email or password.";
    }
} else {
    echo "Login failed. Invalid email or password.";
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Login</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="logo">
        <img src="logo.png" alt="Logo">
    </div>

    <h2>Administrator Login</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Login">

        <p>Don't have an account? <a href="signup.php">Sign up here</a>.</p>
    </form>
    
    <script src="validation.js"></script>
</body>
</html>