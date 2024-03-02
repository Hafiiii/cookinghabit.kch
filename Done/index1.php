<html>
<body>
<?php echo "Hello World! Use these tags if you want to serve PHP code in HTML pages!"; ?>
<? echo "<br><p>Hello World again! This time using short tags, but it will only work"." if short_open_tag is enabled in php.ini. Try it~"; ?>
<?php echo nl2br("\n\n Remember to stop and restart Apache server before \r\n any changes made in php.ini will be effective."); ?>
<?= '<br><br>print this string…'; ?>
<?php print '<br>print this string…'; ?>
</body>
</html>
