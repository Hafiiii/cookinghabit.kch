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

<html>
<body>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
    Student Details: <br>
    ---------------------------<br>
    ID: <input type="text" name="StdID"><br><br>
    Name: <input type="text" name="StdName"><br><br>
    Email: <input type="text" name="StdEmail"><br><br>
    Intake Year: <input type="text" name="IntakeYear"><br><br>
    <input type="submit">
</form>
</body>
</html>