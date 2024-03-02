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

// Create users table if not exists
$createTableSQL = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL
)";

if ($conn->query($createTableSQL) === TRUE) {
    echo "Table created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// Placeholder for signup process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $retypePassword = $_POST["retype_password"];

    // Check if password and retype password match
    if ($password !== $retypePassword) {
        echo "Password and Retype Password do not match.";
        exit(); // Stop execution if passwords do not match
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    $sql = "INSERT INTO users (name, email, password_hash) VALUES ('$name', '$email', '$hashedPassword')";

    if ($conn->query($sql) === TRUE) {
        echo "Signup successful! Redirect to another page.";
        // Redirect to login.php after successful signup
        header("Location: login.php");
        exit(); // Ensure that no further code is executed after the redirect
    } else {
        echo "Signup failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Sign Up</title>
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

    <h2>Administrator Sign Up</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <label for="retype_password">Retype Password:</label>
        <input type="password" name="retype_password" id="retype_password" required>

        <input type="submit" value="Sign Up">
    </form>

    <script src="validation.js"></script>
</body>
</html>
