<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cookinghabitDB";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize an empty message
$message = "";

// Check if the "save" button is clicked
if (isset($_POST["save"])) {
    $career_id = $_POST["career_id"];
    $career_name = $_POST["career_name"];
    $career_age = $_POST["career_age"];
    $career_phone = $_POST["career_phone"];
    $career_email = $_POST["career_email"];
    $career_address = $_POST["career_address"];
    $position = $_POST["position"];
    $jobtype = $_POST["jobtype"];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("UPDATE career SET career_name=?, career_age=?, career_phone=?, career_email=?, career_address=?, position=?, jobtype=? WHERE id=?");

    // Bind parameters
    $stmt->bind_param("sddssiii", $career_name, $career_age, $career_phone, $career_email, $career_address, $position, $jobtype, $career_id);

    // Execute the statement
    if ($stmt->execute()) {
        $message = "Career updated successfully.";
        // Redirect to career_list.php after a successful update
        header("Location: career_list.php");
        exit();
    } else {
        $message = "Error updating career: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Fetch product details for editing
if (isset($_GET["id"])) {
    $career_id = $_GET["id"];
    $query = "SELECT * FROM career WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $career_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $career = $result->fetch_assoc();
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Career</title>
    <link rel="stylesheet" href="editcareer.css">
</head>
<body>
    <div class="logo">
        <img src="image/square-logo.png" alt="Logo">
    </div>

    <h1>Edit Career</h1>

    <?php
    // Display the message if it's not empty
    if (!empty($message)) {
        echo "<p>{$message}</p>";
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">

        <input type="hidden" name="career_id" value="<?php echo $career['id']; ?>">

        <label for="career_name">Name: </label>
        <input type="text" name="career_name" value="<?php echo $career['career_name']; ?>" required>

        <label for="career_age">Age: </label>
        <input type="number" name="career_age" value="<?php echo $career['career_age']; ?>" required>

        <label for="career_phone">Phone Number: </label>
        <input type="tel" name="career_phone" value="<?php echo $career['career_phone']; ?>" required>

        <label for="career_email">Email: </label>
        <input type="email" name="career_email" value="<?php echo $career['career_email']; ?>" required>

        <label for="career_address">Address: </label>
        <textarea name="career_address" required><?php echo $career['career_address']; ?></textarea>

        <label for="position">Position: </label>
        <select name="position" id="position" required>
            <option value="kitchen-helper" <?php if ($career['position'] == 'kitchen-helper') echo 'selected'; ?>>Kitchen Helper</option>
            <option value="service-crew" <?php if ($career['position'] == 'service-crew') echo 'selected'; ?>>Service Crew</option>
        </select>

        <label for="jobtype">Job Type: </label>
        <input type="text" name="jobtype" value="<?php echo $career['jobtype']; ?>" required>

        <button type="submit" name="save">Save</button>
        <button type="button" name="cancel" onclick="location.href='career_list.php'">Cancel</button>
    </form>

</body>
</html>
