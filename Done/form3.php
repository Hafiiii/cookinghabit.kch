<!DOCTYPE html>
<html>
<body>
    <form action="form4.php" method="POST">
        <p>Number 1: <input type="text" name="num1"><?php echo $err;?></p>
        <p>Number 2: <input type="text" name="num2"><?php echo $err;?></p>

        Calculation<br><?php echo $err;?>
        <input type="radio" name="calc" value="add">Addition<br>
        <input type="radio" name="calc" value="sub">Substraction<br>
        <input type="radio" name="calc" value="mul">Multiplication<br>
        <input type="radio" name="calc" value="div">Division<br>
        <input type="submit" value="submit">
    </form>
</body>
</html>