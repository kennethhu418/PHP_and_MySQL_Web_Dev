<?php
session_start();

function compose_login_html() {
?>
    <html>
    <head>
        <title>User Login</title>
    </head>
    <body>
    <h1 align='center'>User Login</h1>
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
            <td colspan='2' align='center'><input type='submit' name='submit' value="login"></td>
        </tr>
    </table>
    </form>
    </body>
    </html>
<?php
}

function welcome_back_show() {
    echo "Welcome back, ".$_SESSION[SS_USER_NAME].".<br><br>";
    echo "<a href=".dirname($_SERVER['PHP_SELF']).'/vieworder.php>View Order</a><br/><br/>';
    echo "<a href=".dirname($_SERVER['PHP_SELF']).'/logout.php>Logout</a><br/>';
}

require('forcessl.php');
require('config.php');

forcessl($_SERVER['PHP_SELF']);

if(isset($_SESSION[SS_USER_LOGGED_IN]) && $_SESSION[SS_USER_LOGGED_IN] == true) {
    welcome_back_show();
    exit;
}

if(!isset($_POST['name']) && !isset($_POST['pwd'])){
    compose_login_html();
    exit;
}

$name = trim($_POST['name']);
$pwd  = $_POST['pwd'];

if(empty($name) || empty($pwd)) {
    echo "Sorry, please provide your name and password!!!<br />";
    echo "<a href=".$_SERVER['PHP_SELF'].">Retry Login</a>";
    exit;
}

$db = new mysqli(DATABASE_ADDR, DATABASE_ADMIN_NAME, DATABASE_ADMIN_PWD, USER_DATABASE);
if($db->connect_error) {
    echo "User Database connection error: ".$db->connect_error;
    exit;
}

$pwd = sha1($pwd);
$query = "select * from User where Name = '".$name."' and Pwd = '".$pwd."';";
$result = $db->query($query);
if($result->num_rows < 1) {
    echo "Wrong Password or Login Name.<br/>";
    echo "<a href=".$_SERVER['PHP_SELF'].">Retry Login</a>";
    $db->close();
    exit;
}

$result->free();
$db->close();

$_SESSION[SS_USER_NAME] = $name;
$_SESSION[SS_USER_LOGGED_IN] = true;

welcome_back_show();
?>
