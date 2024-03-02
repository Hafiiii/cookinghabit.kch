<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "h80609";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT StdID, StdName, StdEmail, IntakeYear FROM 80609_Students WHERE StdID= 345678";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "" . $row["StdID"] . "\t" . $row["StdName"] . "\t" . $row["StdEmail"] . "\t" . $row["IntakeYear"] . "<br>";
    }
}
else {
    echo "0 results";
}

mysqli_close($conn);
?>