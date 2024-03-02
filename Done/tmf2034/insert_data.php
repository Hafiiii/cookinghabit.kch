<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "h80609";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if(isset($_GET["StdID"])) {
    $sql = "INSERT INTO 80609_Students(StdID, StdName, StdEmail, IntakeYear) 
    VALUES (' " . $_GET["StdID"] . " ', '" . $_GET["StdName"] . "', ' " . $_GET["StdEmail"] . "','" . $_GET["IntakeYear"] . "')";
        if(mysqli_query($conn, $sql)) {
        echo "New student record created successfully";
        }
        else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
}
mysqli_close($conn);
?>