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

$r2 = mysqli_select_db($r, $db);	//error

if (!$r2) {
    echo "Cannot select database<br />";
    trigger_error(mysqli_error(), E_USER_ERROR); 
} else {
    echo "Database selected<br />";
}

$name = "O'Neill";
$name_es = mysqli_real_escape_string($name);	//error

$query = "INSERT INTO Authors(Name) VALUES('$name_es')";
$rs = mysqli_query($r, $query);	//error

if (!$rs) {
    echo "Could not execute query: $query<br />";
    trigger_error(mysqli_error(), E_USER_ERROR); 
} else {
    echo "Query: $query executed<br />";
} 

mysqli_close($r);	//error
?>
