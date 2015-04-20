<?php
require_once('business_logic.php');
require_once('htmloutput.php');

forcessl();
login_detect();

if(!isset($_POST['name']) && !isset($_POST['url'])) {
    header("Location:".BOOK_MARK_ADD_PAGE);
    exit;
}

show_header("Add Bookmark");

$username = $_SESSION['username'];
$markname = trim($_POST['name']);
$markurl  = trim($_POST['url']);

if(empty($markname) || empty($markurl)) {
    show_error_message("Please fill both the bookmark name and the correct URL.");
    show_footer();
    exit;
}

try{
    add_new_bookmark($username, $markname, $markurl);
    show_bookmark_added($markname, $markurl);     
}
catch(Exception $e) {
    show_error_message($e->getMessage());
}

show_footer();
?>
