<?php
require_once('business_logic.php');
require_once('htmloutput.php');

forcessl();
login_detect();

show_header("Manage Bookmarks");

show_add_bookmark();

//List all his bookmarks
try {
    $bookmarks = acquire_bookmarks($_SESSION['username'], 1000000); 
    show_manageble_bookmarks("Your Bookmarks", $bookmarks);
}
catch(Exception $e) {
    show_error_message($e->getMessage());
}

show_footer();
?>
