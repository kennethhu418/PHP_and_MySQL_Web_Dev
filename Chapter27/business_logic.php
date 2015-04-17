<?php
require('config.php');
require('bookmark_define.php');

function forcessl() {
    if(!(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')) {
        header("Location: https://".$_SERVER['SERVER_ADDR'].$_SERVER['PHP_SELF']);
        exit;
    }
}

// Login Detection must be called at the very first
// before any html output because of session start.
function login_detect() {
    session_start();
    if(empty($_SESSION['username'])) {
        //user is not logged in. Redirect him to login
        show_login_warning();
        exit;
    }
}

function validate_user_login_input_by_policy($name, $pwd, $mail) {
    if(empty($name) || empty($pwd)) {
        throw new Exception("Please fill both username and password.");
    }

    if(strlen($name) > MAX_USER_NAME_LEN ||
       strlen($pwd) > MAX_PWD_LEN ||
       strlen($pwd) < MIN_PWD_LEN) {
        throw new Exception("Name len should be less than ".MAX_USER_NAME_LEN.
            "; Password len should be between ".MIN_PWD_LEN." and ".MAX_PWD_LEN."!");

    }

    // do not validate email
}

function register_user($name, $pwd, $mail) {
    $db = NULL;
    try{
        $db = database_connect();

        $pwd = sha1($pwd);
        $query = 'insert into User values ('.
                  $name.', '.
                  $pwd.', '.
                  $mail.
                  ' where not exists ('.
                    'select * from User where username = "'.
                    $name.'" or email = "'.$mail.
                  ' ));'
        $db->query($query);
        if($result === false)
            throw new Exception("DB Query Error: ".$db->$error);

        if($db->affected_rows == 0)
            throw new Exception("Your name or email already exists.");
    }
    catch(Exception $e) {
        if(!$db->$connect_error) {
            $db->close();
        }
        throw $e;
    }
    $db->close();
}

function validate_login($username, $pwd) {
    $db = NULL;
    try{
        $db = database_connect();

        $pwd = sha1($pwd);
        $query = 'select * from User where username = "'.
                 $username.'" and pwd = "'.$pwd.'";';
        $result = $db->query($query);
        if($result === false)
            throw new Exception("DB Query Error: ".$db->$error);

        if($result->num_rows == 0)
            throw new Exception("Your name does not exist or password is wrong.");
    }
    catch(Exception $e) {
        if(!$db->$connect_error) {
            $db->close();
        }
        throw $e;
    }
    $db->close();
}

function change_user_password($name, $oldpwd, $newpwd, $newpwd2) {
    $db = NULL;
    try{
        validate_user_login_input_by_policy($name, $newpwd, 'useless@useless.com');
        if($newpwd != $newpwd2)
            throw new Exception("The two new passwords you input are inconsistent.");

        $db = database_connect();

        $oldpwd = sha1($pwd);
        $newpwd = sha1($newpwd);

        $query = 'update User set pwd = "'.$newpwd.'" where username = "'.
                  $name.'" and pwd = "'.$oldpwd.'"';
        
        if($result === false)
            throw new Exception("DB Query Error: ".$db->$error);

        if($db->$affeted_rows == 0)
            throw new Exception("Your old password is wrong. Please retry.");
    }
    catch(Exception $e) {
        if($db != NULL && !$db->$connect_error) {
            $db->close();
        }
        throw $e;
    }
    $db->close();

}



// Connect database
function database_connect() {
    $db = new mysqli(DATABASE_ADDR, DATABASE_ADMIN_NAME, DATABASE_ADMIN_PWD, 'Bookmark');
    if($db->connect_error) {
        throw new Exception("DB connection failed: ".$db->connect_error);
    }
    return $db;
}

function database_close($db) {
    $db->close()
}

function acquire_bookmarks($username, $max_count) {
    $resultArr = array();

    try {
        $db = database_connect();
        $query = 'select Bookmark.bookmarkname, Bookmark.urlID, URL.url, Bookmark.visitfreq from Bookmark, URL where Bookmark.username = "'.
                 $username.
                 '" and Bookmark.urlID = URL.urlID ordered by Bookmark.visitfreq DESC limit '.$max_count.';';
        $result = $db->query($query);
        if($result === false) {
            database_close();
            throw new Exception("DB QUERY error: ".$db->$error);
        }

        for($i = 0; $i < $result->num_rows; ++$i) {
            $curRes = $result->fetch_assoc();
            $resultArr[$i] = new Bookmark;
            $resultArr[$i]->$name = $curRes['bookmarkname'];
            $resultArr[$i]->$url = $curRes['url'];
            $resultArr[$i]->$urlID = $curRes['urlID'];
            $resultArr[$i]->$visitfreq = $curRes['visitfreq'];
        }

        $result->free();
        database_close(); 
    }
    catch(Exception $e) {
        throw $e;
    }

    return $resultArr;
}

function recommend_bookmarks($username, $max_count) {
    $resultArr = array();

    try {
        $db = database_connect();
        $query = 'select distinct(b2.username) from Bookmark as b1, Bookmark as b2 where b1.username = "'.$username.'" and b2.username != $username and b1.urlID = b2.urlID limit '.$max_count.';';
        $users = $db->query($query);

        $targetUserArr = array();

        for($i = 0; $i < $users->num_rows; ++$i) {
            $r = $users->fetch_assoc();
            $other_username = $r['username'];
            $query = 'select count(*) from Bookmark as b1, Bookmark as b2 where b1.username="'.$username.'" and b2.username ="'.$other_username.'" and b1.urlID = b2.urlID';
            $common_url_count = $db->query();
            if($common_url_count === false) {
                $users->free();
                $db->close();
                throw new Exception($db->$error);
            }
            if($common_url_count > RECOMMEND_COMMON_URL_LIMIT) {
                $targetUserArr->push($other_username);
            }
            $common_url_count->free();
        }
        $users->free();

        $query = 'select urlID from Bookmark where username in ';
        for($i = 0; $i < count($targetUserArr); ++$i) {
            $query = $query.$targetUserArr[$i];
            if($i != count($targetUserArr) - 1)
                $query = $query.', ';        
        }
        $query = $query.' and urlID not in (select urlID from Bookmark where username = "'.
                 $username.'")';
        $query = 'select urlID, url, visitfreq from URL where urlID in ('.$query.') ordered by visitfreq DESC limit '.$max_count.';';
        
        $result = $db->query($query);
        if($result === false) {
            $db->close();
            throw new Exception($db->$error);
        }
        for($i = 0; $i < $result->num_rows; ++$i) {
            $curRes = $result->fetch_assoc();
            $resultArr[$i] = new Bookmark;
            $resultArr[$i]->$url = $curRes['url'];
            $resultArr[$i]->$urlID = $curRes['urlID'];
            $resultArr[$i]->$visitfreq = $curRes['visitfreq'];

            $query = 'select bookmarkname from Bookmark where urlID = "'.$curRes['urlID'].'" ordered by visitfreq limit 1;';
            $nameres = $db->query($query);
            $bestname = $nameres->fetch_assoc();
            $resultArr[$i]->$name = $bestname['bookmarkname'];
            $nameres->free();
        }
        $result->free();
        database_close(); 
    }
    catch(Exception $e) {
        throw $e;
    }

    return $resultArr;
}
    
?>
