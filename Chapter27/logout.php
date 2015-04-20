<?php
require_once('config.php');
require_once('htmloutput.php');
require_once('business_logic.php');

forcessl();
session_start();

show_header('Logout');

$_SESSION = array();
session_destroy();

show_logout();

show_footer();
?>
