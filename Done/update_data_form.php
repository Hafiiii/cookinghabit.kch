<?php
include 'connect.php';

if(isset($_GET['Update'])) {
    $id = $_GET['StdID'];
    $name = $_GET['StdName'];
    $email = $_GET['StdEmail'];
    $intakeyear = $_GET['IntakeYear'];

    $sql = "UPDATE 80609_Students SET StdName = '$name', StdEmail = '$email', IntakeYear = '$intakeyear' WHERE StdID = '$id'";

    if($conn->query($sql)) {
        echo "Record Updated " . $id . "<br>";
        include('list.php');
    }
    else
    {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>