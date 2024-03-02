<?
$msg = "E-mail sent from www site\n";
$msg .="Sender's name: \t$_POST[sender_name]\n";
$msg .="Sender's e-mail: \t$_POST[sender_mail]\n";
$msg .="Message: \t$_POST[sender_message]\n";
$to ="hafizah.rmli@gmail.com";
$subject = "Web Site feedback";
$mailheaders = "From: My Website \n";;
$mailheaders .="Reply-To: $_POST[sender_mail]\n\n";
mail($to, $subject, $msg, $mailheaders);
?>
