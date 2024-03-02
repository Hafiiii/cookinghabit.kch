<?php

$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$db = "mydb";

$r = mysqli_connect($host, $user, $pass, $db);
if (!$r) {
    echo "Could not connect to server<br />";
    trigger_error(mysqli_error(), E_USER_ERROR);
} else {
    echo "Connection established<br />"; 
}

$r2 = mysqli_select_db($r, $db);
if (!$r2) {
    echo "Cannot select database<br />";
    trigger_error(mysqli_error(), E_USER_ERROR); 
} else {
    echo "Database selected<br />";
}

$file = "usb.jpg";
$img = fopen($file, 'r');
if (!$img) {
    echo "Cannot open file for writing<br />";
    trigger_error("Cannot open file for writing<br />", E_USER_ERROR);
} 

$data = fread($img, filesize($file));
if (!$data) {
    echo "Cannot read image data<br />";
    trigger_error("Cannot read image data<br />", E_USER_ERROR);
}

$es_data = mysqli_real_escape_string($r, $data);
fclose($img);

$query = "INSERT INTO Images(Id, Data) Values(1, '$es_data')";
$rs = mysqli_query($r, $query);
if (!$rs) {
    echo "Could not execute query: $query";
    trigger_error(mysqli_error(), E_USER_ERROR); 
} else {
    echo "Query successfully executed<br />";
} 

mysqli_close($r);
?>
