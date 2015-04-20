<?php
require_once('business_logic.php');
require_once('htmloutput.php');

forcessl();
login_detect();

$is_urlid = false;
if(($bookmarkid = extract_bookmark($is_urlid)) === false) {
    header("Location:".$_SERVER['HTTP_REFERER']);
    exit;
}

show_header("Delete Bookmark");

$username = $_SESSION['username'];

try{
    delete_bookmark($bookmarkid);
    show_bookmark_deleted();     
}
catch(Exception $e) {
    show_error_message($e->getMessage());
}

show_footer();
?>
