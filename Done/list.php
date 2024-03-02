<?php
include 'connect.php';

$sql = "SELECT * FROM 80609_Students";
$result = $conn->query($sql);
while($row = $result->fetch_array()) {
    $id = $row['StdID'];
    echo "<a href='update.php?id=" . "$id" . "'>" . "\t\t" . $row['StdID']
        . "\t\t" . $row['StdName'] . "\t\t" . $row['StdEmail']
        . "\t\t" . $row['IntakeYear'] . "</a>";
    echo "<br>";
}
mysqli_close($conn);
?>
