<?php

require('config.php');

function show_header($title){
?>

<html>
<head>
    <title><?php echo $title; ?></title>
    <style>
        td.menu {
            background-color:black;
            color:white;
            font-family:verdana;
            width:20%;
        } 
        td.logo {
            width:20%;
        }
        a::visited{
            color:yellow;
        }
    </style>
</head>
<body>
<table align='center'>
<tr style="height:40px">
<td rowspan="2" colspan="2" class="logo">
<img src=<?php echo LOGO_PIC; ?>  style="width:40%;height:30%" />
</td>
<td colspan="2" class="logo"><h1>Cool Bookmark</h1></td>
</tr>
<tr>
<td colspan="2" class="logo"><i>One Amazing Website for you to manage bookmarks</i><br/></td>
</tr>
<tr style="height:20px" align='center'>
<td class='menu'><a href=<?php echo HOME_PAGE; ?>>Home</a></td>
<td class='menu'><a href=<?php echo USER_INFO_PAGE; ?>>HUserInfo</a></td>
<td class='menu'><a href=<?php echo BOOKMARKS_PAGE; ?>>HBookMarks</a></td>
<td class='menu'><a href=<?php echo LOGOUT_PAGE; ?>>HLogout</a></td>
</tr>
</table>
<br/>
<?php
}

function show_footer() {
?>
<div style="position:absolute; bottom:2px; display:block;">
<p>Kenneth Optimization Co., Ltd</p>
<p>Contact: <a href="mailto:<?php echo ADMIN_CONTACT_MAIL; ?>"><i><?php echo ADMIN_CONTACT_MAIL; ?></i></a>"</p>
</div>
</body>
</html>
<?php
}

function show_login($login_script) {
?>
<h2 align='center'>User Login</h2>
<form action=<?php echo $login_script; ?> method='post'>
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
<?php
}

function show_change_pwd($process_script) {
?>
<h2 align='center'>Change Password</h2>
<form action=<?php echo $process_script; ?> method='post'>
<table align='center'>
    <tr>
        <td>Old Password:</td>
        <td><input type='password' size="25" name="oldpwd"/></td>
    </tr>
    <tr>
        <td>New Password:</td>
        <td><input type='password' size="25" name='newpwd'/></td>
    </tr>
    <tr>
        <td>New Password Confirm:</td>
        <td><input type='password' size="25" name='newpwd_confirm'/></td>
    </tr>
    <tr>
        <td colspan='2' align='center'><input type='submit' name='submit' value="Change Password"></td>
    </tr>
</table>
</form>
<?php
}

function show_login_warning() {
    echo "<p style=\"color:red\">Sorry, you are not logged in. Please ".
         "<a href=".LOGIN_PAGE.">Login</a><br/></p>";
}

function show_logout($username) {
    echo "You have been logged out successfully, ".$username.".<br/><br/>";
    echo "You can <a href=".LOGIN_PAGE.">Login</a> again.<br/>";
    
}

/************************************************************************
 * Each row of the bookmark table would look like this:
 * bookmark_icon   bookmark_name  <submit_button>submit_show_name
 * When a user press the submit button, the process_script would be called
 ***********************************************************************/
function show_bookmarks($title, $bookmark_arr, $process_script, $submit_show_name) {
?>
    <table align='center'> 
    <tr align='center'>
        <td style="width:30%" colspan="2"><?php echo $title; ?></td>
    </tr>
<?php
    for($i = 0; $i < count($bookmark_arr); ++$i) {
        echo '<tr align="center">';
        echo '<td style="width:50%">';
        echo '<img src='.BOOKMARK_ICON.'/> '.$bookmark_arr[$i]->name.
             '</td>';
        echo '<td style="width:10%">'.
             '<form action="'.$process_script.'" method="post">'.
             '<input type="submit" value='.$submit_show_name.'"Go" name="'.$bookmark_arr[$i]->name.'" />'.
             '</form>'.
             '</td>';
        echo '</tr>';
    }
?>
    </table>
    <br />
<?php
}

function show_add_bookmark($process_script) {
?>
    <h2 align='center'>User Login</h2>
    <form action=<?php echo $process_script; ?> method='post'>
    <table align='center'>
        <tr>
            <td>Bookmark Name:</td>
            <td><input type='text' size="40" name='name'/></td>
        </tr>
        <tr>
            <td>URL:</td>
            <td><input type='text' size="40" name='URL'/></td>
        </tr>
        <tr>
            <td colspan='2' align='center'><input type='submit' name='submit' value="Add"></td>
        </tr>
    </table>
    </form>
<?php
}





?>
