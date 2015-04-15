<?php
session_start();
require('forcessl.php');
require('config.php');

forcessl($_SERVER['PHP_SELF']);

$username = $_SESSION[SS_USER_NAME];

$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 24*3600*7,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]);
}

// Finally, destroy the session.
session_destroy();

echo "You have been logged out successfully, ".$username.".<br/>";
?>
