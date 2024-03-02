<?php
include 'connect.php';

if(isset($_POST['Delete'])) {
    $id = $_POST['StdID'];
    $sql = "DELETE FROM 80609_Students WHERE StdID = '$id'";

    if($conn->query($sql)) {
        echo $id . " is deleted";
    }
    else
    {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>

<html>
<body>
    <h3>Student Details</h3>
    ---------------
    <form name="delete_record" method="post">
        <p>ID: <input type="text" name="StdID" placeholder="Student ID"></p>
        <input type="submit" name="Delete" placeholder="DELETE" value="Delete">
    </form>
</body>    
</html>