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

// Create the products table if it doesn't exist
$tableCreationSQL = "CREATE TABLE IF NOT EXISTS career (
    id INT AUTO_INCREMENT PRIMARY KEY,
    career_name VARCHAR(255) NOT NULL,
    career_age INT NOT NULL,
    career_phone VARCHAR(255) NOT NULL,
    career_email VARCHAR(255) NOT NULL,
    career_address LONGTEXT NOT NULL,
    position VARCHAR(255) NOT NULL,
    jobtype VARCHAR(255) NOT NULL,
    career_resume MEDIUMBLOB NOT NULL -- Assuming a path to the image
)";

if ($conn->query($tableCreationSQL) === TRUE) {
    // Remove the echo statement to prevent the message from being displayed
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Handle form submission
$message = ""; // Initialize an empty message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $career_name = $_POST["career_name"];
    $career_age = $_POST["career_age"];
    $career_phone = $_POST["career_phone"];
    $career_email = $_POST["career_email"];
    $career_address = $_POST["career_address"];
    $position = $_POST["position"];
    $jobtype = $_POST["jobtype"];

    // Image upload handling
    $targetDir = "resume/";

    // Ensure the target directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $targetFile = $targetDir . basename($_FILES["career_resume"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the image file is a valid image
    $check = getimagesize($_FILES["career_resume"]["tmp_name"]);

    // Check file size (you can adjust this value)
    if ($_FILES["career_resume"]["size"] > 500000) {
        $message = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats (you can adjust this list)
    $allowedExtensions = ["jpg", "jpeg", "png", "pdf"];
    if (!in_array($imageFileType, $allowedExtensions)) {
        $message = "Sorry, only PDF, JPEG, JPEG, and PNG files are allowed.";
        $uploadOk = 0;
    }


    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message = "Sorry, your file was not uploaded.";
    } else {
        // If everything is ok, try to upload the file
        if (move_uploaded_file($_FILES["career_resume"]["tmp_name"], $targetFile)) {
            $message = "The file " . htmlspecialchars(basename($_FILES["career_resume"]["name"])) . " has been uploaded.";

            // Use prepared statements to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO career (career_name, career_age, career_phone, career_email, career_address, position, jobtype, career_resume)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            // Bind parameters
            $stmt->bind_param("ssdsssss", $career_name, $career_age, $career_phone, $career_email, $career_address, $position, $jobtype, $targetFile);

            // Execute the statement
            if ($stmt->execute()) {
                $message .= " Application successfully submited";
                // Add a JavaScript script to redirect after a successful submission
                echo '<script>window.location.href = "notification_career.html";</script>';
            } else {
                $message = "Error: " . $stmt->error;
            }

            // Close the prepared statement
            $stmt->close();
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE htmL>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Opportunities</title>
    <link rel="icon" href="image/square-logo.png">
    <link rel="stylesheet" href="career_apply.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <!-- NAVIGATION BAR -->
    <script id="replace_with_navbar" src="navbar.js"></script>

    <div class="banner">  
        <div class="banner-text"><p>CAREER</p></div>
        <img src="image/career-banner.png">
    </div>

    <div class="back-page">
        <a href="javascript:history.back()"><img src="image/back.png"></a>
    </div>

    <div class="career-section">
        <div class="career-sentence">
            <p>Are you passionate about DESSERT?
                <br/>Then come to join our team <br></p>
        </div> 

        <?php
        // Display the message if it's not empty
        if (!empty($message)) {
            echo "<p>{$message}</p>";
        }
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">

            <label for="career_name">Name </label>
            <input type="text" id="career_name" name="career_name" placeholder="Enter your name" required>
        
            <label for="career_age">Age </label>
            <input type="number" id="career_age" name="career_age" placeholder="Enter your age" required>
        
            <label for="career_phone">Phone Number </label>
            <input type="tel" id="career_phone" name="career_phone" pattern="[0-9]{10}" placeholder="Enter your phone number" required>
        
            <label for="career_email">Email </label>
            <input type="email" id="career_email" name="career_email" placeholder="Enter your email" required>
        
            <label for="career_address">Address </label>
            <textarea id="career_address" name="career_address" class="career_address" placeholder="Enter your address" required></textarea>

            <label for="position">Position </label>
            <select id="position" name="position" required>
                <option selected="selected" value="Kitchen Helper">Kitchen Helper</option>
                <option value="Service Crew">Service Crew</option>
            </select>

            <p>Select an option </p>
            <label class="jobtype-container">Full Time
            <input type="radio" checked="checked" id="jobtype" name="jobtype" value="full-time" required>
            <span class="checkmark"></span></label>
            <label class="jobtype-container">Part Time
            <input type="radio" checked="checked" id="jobtype" name="jobtype" value="part-time" required>
            <span class="checkmark"></span></label>

            <label class="upload-resume" for="career_resume">Upload resume</label>
            <input type="file" id="upload" name="career_resume" required>
            
            <div class="upload-btn">
                <button type="submit" name="send">Submit</button>
            </div>
        </form> 

        <?php
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        require './phpEmail/PHPMailer/src/Exception.php';
        require './phpEmail/PHPMailer/src/PHPMailer.php';
        require './phpEmail/PHPMailer/src/SMTP.php';

        if(isset($_POST['send'])){
        $name = htmlentities($_POST['career_name']);
        $email = htmlentities($_POST['career_email']);
        $message = "Dear $name, <br><br>
        Thank you for applying to cookinghabit.kch as $position. <br><br>
        We have received your application for the position of $position with job type $jobtype. <br><br>
        We will review your application and get back to you as soon as possible. <br><br>
        Best regards, <br>
        The cookinghabit.kch Team";
        $fileContents = file_get_contents($_FILES['career_resume']['tmp_name']);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'cookinghabit.kch2.0@gmail.com';
        $mail->Password = 'cqajcwigokbpzgzj';
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->isHTML(true);
        $mail->setFrom('cookinghabit.kch2.0@gmail.com');
        $mail->addAddress($email, $name);
        $mail->addAddress('cookinghabit.kch2.0@gmail.com');
        $mail->Subject = ("Thank you for applying to cookinghabit.kch!");
        $mail->Body = $message;
        $mail->addStringAttachment($fileContents, $_FILES['career_resume']['name']);
        $mail->send();

        }
        ?>

    </div>
    


    <!-- FOOTER -->
    <script id="replace_with_footer" src="footer.js"></script>
</body>
</html>


