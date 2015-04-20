<?php
require_once('business_logic.php');
require_once('htmloutput.php');

forcessl();
login_detect();

show_header("Home Page");

//List all his bookmarks
try {

    $bookmarks_top = acquire_bookmarks($_SESSION['username'], BOOKMARK_DISPLAY_COUNT); 
    show_bookmarks("Your Bookmarks", $bookmarks_top, LINK_PROCESSOR, 'Go', true);

    //List recommended bookmarks
    $bookmarks_top = recommend_bookmarks($_SESSION['username'], BOOKMARK_RECOMMEND_COUNT);
    show_bookmarks("Recommendations for you", $bookmarks_top, LINK_PROCESSOR, 'Go', false);
}
catch(Exception $e) {
    show_error_message($e->getMessage());
}

show_footer();
?>
