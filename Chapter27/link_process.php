<?php
require_once('business_logic.php');
require_once('htmloutput.php');

forcessl();
login_detect();

$is_url = false;
if(($bookmark = extract_bookmark($is_url)) === false) {
    header("Location:".$_SERVER['HTTP_REFERER']);
    exit;
}

$username = $_SESSION['username'];

try{
    $url = account_bookmark_link($username, $bookmark, $is_url);
    header('Location: '.$url);
}
catch(Exception $e) {
    show_header('Redirect');
    show_error_message($e->getMessage());
    show_footer();
}

?>
