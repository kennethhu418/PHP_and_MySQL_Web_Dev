<?php

require('forcessl.php');

forcessl($_SERVER['PHP_SELF']);

if(!isset($_POST['name']) && !isset($_POST['pwd'])) {
?>
<html>
<head>
    <title>User Register</title>
</head>
<body>
<h1 align='center'>User Register</h1>
<form action=<?php echo $_SERVER['PHP_SELF']; ?> method='post'>
<table align='center'>
    <tr>
        <td>UserName:</td>
        <td><input type='text' size="25" name='name'/></td>
    </tr>
    <tr>
        <td>Password:</td>
        <td><input type='password' size="25" name='pwd'/></td>
    </tr>
    <tr>
        <td colspan='2'><input type='submit' name='submit'></td>
    </tr>
</table>
</form>
</body>
</html>
<?php
    exit;
}


$name = trim($_POST['name']);
$pwd  = $_POST['pwd'];

if(empty($name) || empty($pwd)) {
    echo "Sorry, please provide your name and password!!!<br />";
    echo "<a href=".$_SERVER['PHP_SELF'].">Retry Register</a>";
    exit;
}

require('config.php');

$db = new mysqli(DATABASE_ADDR, DATABASE_ADMIN_NAME, DATABASE_ADMIN_PWD, USER_DATABASE);
if($db->connect_error) {
    echo "User Database connection error: ".$db->connect_error;
    exit;
}

// First check whether the user name is occupied.
// For test purpose, we ignore this check.

$pwd = sha1($pwd);
$query = "insert into ".USER_TABLE." values ('".$name."', '".$pwd."');";
$result = $db->query($query);
if($db->affected_rows != 1) {
    echo "Query Insert has something wrong.<br/>";
    $db->close();
    exit;
}

$db->close();

echo "<h2>Congratulations. Now you are a family member, ".$name."</h2><br/>";

$dirname = dirname($_SERVER['PHP_SELF']).'/login.php';
echo "Would you like go to <a href=".$dirname.">login</a>?";
?>
