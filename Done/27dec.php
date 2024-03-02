<?php

$host = "localhost";
$user = "h80609";
$pass = "";

$conn = mysqli_connect($host, $user, $pass);

if(!$conn) {
    echo "Could not connect to server\n<p>";
    trigger_error(mysqli_error(), E_USER_ERROR);
}
else {
    echo "Connection established\n<p>";
}

echo mysqli_get_server_info($conn) . "\n<p>";

mysqli_close($conn);

?>