<?php
include 'connect.php';
include 'update_data_form.php';

$id = $_GET['id'];
$sql = "SELECT * FROM 80609_Students WHERE StdID = '$id'";
$result = $conn->query($sql);

while($row = $result->fetch_array()) {
    $id = $row['StdID'];
    $name = $row['StdName'];
    $email = $row['StdEmail'];
    $intakeyear = $row['IntakeYear'];
}
mysqli_close($conn);
?>

<html>
<body>
    Update Student Details: <br>
    ---------------------------<br>
    <form action="<?php echo $_SERVER['php_self'];?>" method="get">
    ID: <input type="text" name="StdID" value="<?php echo $id; ?>"><br><br>
    Name: <input type="text" name="StdName" value="<?php echo $name; ?>"><br><br>
    Email: <input type="text" name="StdEmail" value="<?php echo $email; ?>"><br><br>
    Intake Year: <input type="text" name="IntakeYear" value="<?php echo $intakeyear; ?>"><br><br>
    <input type="submit" name="Update" value="Update"><br>
</body>
</html>


