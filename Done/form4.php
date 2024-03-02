<?php
$num1 = $num2 = $calc = $err = "";

$num1 = $_POST["num1"];
$num2 = $_POST["num2"];

$calc = $_POST["calc"];

if(($num1 == "") || ($num2 == "") || ($calc == "")) {
    header("Location: form3.html");
    exit;
}

if(empty($num1)) {
    $err = "Required";
}
else {
    $num1 = test_input($_POST["num1"]);
}
if(empty($num2)) {
    $err = "Required";
}
if(empty($calc)) {
    $err = "Required";
}


if($calc == "add") {
    $result = $num1 + $num2;
}
else if($calc == "sub") {
    $result = $num1 - $num2;
}
else if($calc == "mul") {
    $result = $num1 * $num2;
}
else if($calc == "div") {
    $result = $num1 / $num2;
}

echo $result;
?>