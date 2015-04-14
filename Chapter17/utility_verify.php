<?php

define(AUTH_RESULT_SUCCESS, 0);
define(AUTH_RESULT_WRONG, 1);
define(AUTH_RESULT_DB_ERROR, 2);

define(AUTH_DB, "Authentication");
define(AUTH_TABLE, "User");
define(AUTH_DB_HOST, "localhost");
define(AUTH_DB_USER, "kenneth");
define(AUTH_DB_PWD, "kenneth");

function user_verify($username, $userpwd) {
    $username = htmlspecialchars(trim($username));
    $userpwd = htmlspecialchars(trim($userpwd));
    if(empty($username) || empty($userpwd)) {
        return AUTH_RESULT_WRONG;
    }

    $db = new mysqli(AUTH_DB_HOST, AUTH_DB_USER, AUTH_DB_PWD, AUTH_DB);
    if(mysqli_connect_error()) {
        return AUTH_RESULT_DB_ERROR;
    }

    // $user_sha = sha1($userpwd);
    $user_sha = $userpwd;
    $query_sentense = "select count(*) from ".AUTH_TABLE." where Name = $username and Pwd = $user_sha;";
    $result = $db->query($query_sentense);
    if($result === false) {
        $db->close();
        return AUTH_RESULT_DB_ERROR;
    }

    if($result == 0) {
        $db->close();
        $result->free();
        return AUTH_RESULT_WRONG;
    }

    $result->free();
    $db->close();
    return AUTH_RESULT_SUCCESS;
}

?>
