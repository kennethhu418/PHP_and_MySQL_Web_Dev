<?php

function forcessl($scriptname) {
    if(!(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')) {
        header("Location: https://".$_SERVER['SERVER_ADDR'].$_SERVER['PHP_SELF']);
        exit;
    }
}

?>
