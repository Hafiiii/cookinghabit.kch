<?php
// form values
$name = $_POST['name'];
$age = $_POST['age'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];

if(!empty($email) && !empty($address)){ //if emeil and address fiel is not empty
   if(filter_var($email, FILTER_VALIDATE_EMAIL)){ //if user entered email is valid
        $receiver = "cookinghabit.kch@gmail.com"; //email receiver address
        $subject = "From: $name <$email>"; // subject of the email. subject looks like from : izzati  <izzati@gmail.com>
        //merging concating all user values inside body variable. 
        $body = "Name : $name\nAge : $age\nPhone: $phone\nEmail : $email\nAddress : $Address\n\nRegards,\n$name";
        $sender = "From : $email"; //sender email
        if(mail($receiver, $subject ,$body, $sender)){

        }
   }else{
    echo "Enter a valid email address!";
   }

}else{
    echo "Email and password field is required!";
}
?>