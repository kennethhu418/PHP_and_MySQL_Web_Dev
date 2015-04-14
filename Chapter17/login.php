<html>
<head>
    <title>Login</title>
</head>
<body>

<?php
    function show_authentication_form(){
?>
<form action=<?php echo basename(__FILE__)?> > 
<table align="center"> 
<tr> 
<td>UserName</td>
<td><input type="text" name="name" /></td> 
</tr> 
<tr> 
<td>Password</td> 
<td><input type="password" name="pwd" /></td> 
</tr> 
<tr> 
<td colspan="2"><input type="Submit" value="Login"></td> 
</tr> 
</table> 
</form> 
<?php
    }
?>

<?php
    $username = $_POST['name'];
    $userpwd  = $_POST['pwd'];
    if(!isset($username) && !isset($userpwd)) {
        echo "<h1>Login Form</h1><br />";
        show_authentication_form();
        echo "</body></html>";
        exit;
    }
    else {
       require_once("utility_verify.php"); 
       $result = user_verify($username, $userpwd);
       if($result == AUTH_RESULT_WRONG) {
        echo "<h1>Login Form</h1>";
        echo "<span style=\"color:red\">Wrong username or password!</span><br />";
        show_authentication_form();        
       }
       else if($result == AUTH_RESULT_DB_ERROR) {
        echo "<h1>Login Form</h1>";
        echo "<span style=\"color:red\">Sorry, something is wrong with DB. Retry later.<br />";
        show_authentication_form();        
       }
       else {
        echo $_SERVER["REQUEST_URI"]."<br />";
       }    
    }
?>
</body>
</html>
