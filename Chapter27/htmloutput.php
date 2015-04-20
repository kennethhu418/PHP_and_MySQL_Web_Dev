<?php
require_once('config.php');

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
        a.menu{
            color:white;
        }
    </style>
</head>
<body>
<table align='center' width="85%">
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
<td class='menu'><a class='menu' href=<?php echo HOME_PAGE; ?>>Home</a></td>
<td class='menu'><a class='menu' href=<?php echo CHANGE_PWD_PAGE; ?>>ChangePwd</a></td>
<td class='menu'><a class='menu' href=<?php echo BOOKMARKS_PAGE; ?>>BookMarks</a></td>
<td class='menu'><a class='menu' href=<?php echo LOGOUT_PAGE; ?>>Logout</a></td>
</tr>
</table>
<br/>
<?php
}

function show_footer() {
?>
<div style="position:absolute; bottom:0px; display:block; align:center"> 
<p align='center' style="font-size:70%">Kenneth Optimization Co., Ltd</p>
<p align='center' style="font-size:70%">Contact: <a href="mailto:<?php echo ADMIN_CONTACT_MAIL; ?>"><i><?php echo ADMIN_CONTACT_MAIL; ?></i></a>"</p>
</div>
</body>
</html>
<?php
}

function show_register() {
?>
<h2 align='center'>User Register</h2>
<form action=<?php echo REGISTER_PAGE; ?> method='post'>
<table align='center'>
    <tr>
        <td>UserName:</td>
        <td><input type='text' size="25" name='name'/></td>
    </tr>
    <tr>
        <td>Email:</td>
        <td><input type='text' size="25" name='email'/></td>
    </tr>
    <tr>
        <td>Password:</td>
        <td><input type='password' size="25" name='pwd'/></td>
    </tr>
    <tr>
        <td colspan="2" align='center'><button type='submit' name='register'>Submit</button></td>
    </tr>
</table>
</form>
<?php
}

function show_login() {
?>
<h2 align='center'>User Login</h2>
<form action=<?php echo LOGIN_PAGE; ?> method='post'>
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
        <td align='center'><button type='submit' name='login'>Submit</button></td>
        <td align='center'><button type='submit' name='register' formaction='<?php echo REGISTER_PAGE;?>'>Register</td>
    </tr>
</table>
</form>
<?php
}

function compose_login_page() {
   show_header('Login'); 
   show_login(); 
   show_footer();
}

function compose_register_page() {
   show_header('Register'); 
   show_register(); 
   show_footer();
}

function compose_change_pwd_page() {
   show_header('Change Password'); 
   show_change_pwd(CHANGE_PWD_PAGE); 
   show_footer();
}

function show_change_pwd_success($name) {
    show_header('Change Password'); 
    echo "Your password has been successfully change, ".$name."<br/>";
    show_footer();
}

function show_change_pwd_fail($err) {
    show_header('Change Password'); 
    echo "Password Change Fails: ".$err."<br/>";
    show_footer();
}

function show_register_success($name) {
    show_header("Register");
    echo "<p style=\"color:blue\">Register Success. Wecome to this cool site, ".$name."<br/>";
    show_footer();
}

function show_register_fail($error) {
    show_header("Register");
    show_error_message("Register Error: ".$error);
    echo '<a href="'.REGISTER_PAGE.'">Register</a><br/>';
    echo '<a href="'.LOGIN_PAGE.'">Login</a><br/>';
    show_footer();
}

function show_login_fail($error) {
    show_header("Login");
    show_error_message("Login Error: ".$error);
    echo '<a href="'.REGISTER_PAGE.'">Register</a><br/>';
    echo '<a href="'.LOGIN_PAGE.'">Login</a><br/>';
    show_footer();
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

function show_logout() {
    echo "You have been logged out successfully.<br/><br/>";
    echo "You can <a href=".LOGIN_PAGE.">Login</a> again.<br/>";
}

function show_add_bookmark() {
?>
    <form action=<?php echo BOOK_MARK_ADD_PAGE;?> method='post'>
        <table align='center'>
        <caption>Add Bookmark</caption>
        <tr>
            <td>Bookmark Name:</td>
            <td><input type='text' size='60px' name='name'/></td>
        </tr>
        <tr>
            <td>Bookmark URL:</td>
            <td><input type='text' size='60px' name='url'/></td>
        </tr>
        <tr>
            <td colspan='2' align='center'><input type='submit' name='submit' value='Add'/></td>
        </tr>
        </table>
    </form>
    <br/>
<?php
}

/************************************************************************
 * Each row of the bookmark table would look like this:
 * bookmark_icon   bookmark_name url <submit_button>submit_show_name
 * When a user press the submit button, the process_script would be called
 * Each submit button has a name and the name pattern is 'submit_xxx', where
 * xxx can be a bookmarkid or a urlID, which is specified by the
 * $form_bookmarkname variable.
 ***********************************************************************/
function show_bookmarks($title, $bookmark_arr, $process_script, $submit_show_name, $form_bookmarkname = true) {
?>
    <form method='post' action=<?php echo $process_script; ?>>
    <table align='center' border='1' width="85%"> 
    <tr align='center'>
        <td style="width:20%" colspan="3"><b><?php echo $title; ?></b></td>
    </tr>
<?php
    for($i = 0; $i < count($bookmark_arr); ++$i) {
        echo '<tr align="center">';
        echo '<td style="width:10%">';
        echo '<img style="width:7%" src="'.BOOKMARK_ICON.'"/> '.$bookmark_arr[$i]->name.
             ' </td>';
        echo '<td style="width:30%">';
        echo $bookmark_arr[$i]->url.'</td>';
        if($form_bookmarkname) {
            echo '<td style="width:5%">'.
                 '<button type="submit" name="submit_'.
                 $bookmark_arr[$i]->ID.
                 '">'.$submit_show_name.'</button>'.
                 '</td>';
        }
        else {
            echo '<td style="width:5%">'.
                 '<button type="submit" name="submiturl_'.
                 $bookmark_arr[$i]->urlID.
                 '">'.$submit_show_name.'</button>'.
                 '</td>';
        }

        echo '</tr>';
    }
?>
    </table>
    </form>
    <br />
<?php
}

function show_manageble_bookmarks($title, $bookmark_arr) {
?>
    <form action=<?php echo LINK_PROCESSOR;?> method='post'>
    <table align='center' border='1' width="85%"> 
    <tr align='center'>
        <td style="width:20%" colspan="3"><b><?php echo $title; ?></b></td>
    </tr>
<?php
    for($i = 0; $i < count($bookmark_arr); ++$i) {
        echo '<tr align="center">';
        echo '<td style="width:15%">';
        echo '<img style="width:7%" src=\''.BOOKMARK_ICON.'\'/> '.$bookmark_arr[$i]->name.
             '</td>';
        echo '<td style="width:30%">';
        echo $bookmark_arr[$i]->url.'</td>';
        echo '<td style="width:5%">'.
             '<button type="submit" name="submit_'.$bookmark_arr[$i]->ID.
             '">Go</button> '.
             '<button type="submit" name="submit_'.$bookmark_arr[$i]->ID.
             '" formaction="'.DELETE_PROCESSOR.'">Del</button>'.
             '</td>';
        echo '</tr>';
    }
?>
    </table>
    </form>
    <br />
<?php
}

function show_bookmark_added($markname, $markurl) {
    echo "Congratulations, you have successfully added the new bookmark.<br/>";
    echo "Name: ".htmlspecialchars($markname)."<br/>";
    echo "URL : ".htmlspecialchars($markurl)."<br/>";
}

function show_bookmark_deleted() {
    echo "Congratulations, you have successfully deleted the bookmark.<br/>";
}


function show_error_message($message) {
    echo '<p><i>Woops, there is something wrong: <br/></i></p>'.$message.'<br/>';
}

?>
