<?php
$username = $_POST["username"];
$password = $_POST["password"];
$email = $_POST["email"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } 
    else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
}

echo "thank you " . $username . " for filling up\n\n";

//mailing
$to = "you@gmail.com";
$subject = "WEbsite";
$msg = "Thank you bebeh from love";
$mailheaders = "From: My website\n";
$mailheaders .= "Reply-To: " . $email;

echo $mailheaders;

mail($to, $subject, $msg, $mailheaders);

?>