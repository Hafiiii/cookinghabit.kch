<?php

$host = "localhost"; 
$user = "root"; 
$pass = ""; 

$r = mysqli_connect($host, $user, $pass);
if (!$r) {
    echo "Could not connect to server";
    trigger_error(mysqli_error(), E_USER_ERROR);
} else {
    echo "Connection established"; 
}

$query = "SELECT VERSION()";	//from MySQL 4.0
$rs = mysqli_query($r,$query);
if (!$rs) {
    echo "Could not execute query: $query";
    trigger_error(mysqli_error(), E_USER_ERROR);
} else {
    echo "Query: $query executed"; 
}

$row = mysqli_fetch_row($rs);
echo "Version: $row[0]";

mysqli_close();
?>
