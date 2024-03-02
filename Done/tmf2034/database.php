<?php
$servername = "http://127.0.0.1";
$username = "root";
$password = "";

//create connection
$conn = mysqli_connect($servername, $username, $password);

//check connection
if (!$icon) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
?>