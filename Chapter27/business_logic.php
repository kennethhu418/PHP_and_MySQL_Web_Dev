<?php
require_once('config.php');
require_once('bookmark_define.php');

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
        $query = 'insert ignore into User values (\''.
                  $name.'\', \''.
                  $pwd.'\', \''.
                  $mail.
                  '\');'; 
        $result = $db->query($query);
        if($result === false)
            throw new Exception("DB Query Error: ".$db->error.'<br/>'.$query);

        if($db->affected_rows == 0)
            throw new Exception("Your name or email already exists.");
    }
    catch(Exception $e) {
        if(!$db->connect_error) {
            database_close($db);
        }
        throw $e;
    }
    database_close($db);
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
            throw new Exception("DB Query Error: ".$db->error);

        if($result->num_rows == 0)
            throw new Exception("Your name does not exist or password is wrong.");
    }
    catch(Exception $e) {
        if(!$db->connect_error) {
            database_close($db);
        }
        throw $e;
    }
    database_close($db);
}

function change_user_password($name, $oldpwd, $newpwd, $newpwd2) {
    $db = NULL;
    try{
        validate_user_login_input_by_policy($name, $newpwd, 'useless@useless.com');
        if($newpwd != $newpwd2)
            throw new Exception("The two new passwords you input are inconsistent.");

        $oldpwd = sha1($oldpwd);
        $newpwd = sha1($newpwd);

        $db = database_connect();
        $query = 'select * from User where username="'.$name.'" and pwd="'.$oldpwd.'"';
        if(($result = $db->query($query)) === false)
            throw new Exception("DB Query Error: ".$db->error);
        if($result->num_rows == 0)
            throw new Exception("Your old password is wrong. Please retry.");

        $query = 'update User set pwd = "'.$newpwd.'" where username = "'.
                  $name.'"';
        $result = $db->query($query);
        if($result === false)
            throw new Exception("DB Query Error: ".$db->error);
    }
    catch(Exception $e) {
        if($db != NULL && !$db->connect_error) {
            database_close($db);
        }
        throw $e;
    }
    database_close($db);

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
    $db->close();
}

function acquire_bookmarks($username, $max_count) {
    $resultArr = array();

    try {
        $db = database_connect();
        $query = 'select Bookmark.bookmarkid, Bookmark.bookmarkname, Bookmark.urlID, URL.url, Bookmark.visitfreq from Bookmark, URL where Bookmark.username = "'.
                 $username.
                 '" and Bookmark.urlID = URL.urlID order by Bookmark.visitfreq DESC limit '.$max_count.';';
        $result = $db->query($query);
        if($result === false) {
            throw new Exception("DB QUERY error: ".$db->error."<br>".$query);
            database_close($db);
        }

        for($i = 0; $i < $result->num_rows; ++$i) {
            $curRes = $result->fetch_assoc();
            $resultArr[$i] = new Bookmark;
            $resultArr[$i]->name = $curRes['bookmarkname'];
            $resultArr[$i]->url = $curRes['url'];
            $resultArr[$i]->urlID = $curRes['urlID'];
            $resultArr[$i]->ID = $curRes['bookmarkid'];
            $resultArr[$i]->visit_freq = $curRes['visitfreq'];
        }

        $result->free();
        database_close($db); 
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
        $query = 'select distinct(b2.username) from Bookmark as b1, Bookmark as b2 where b1.username = "'.$username.'" and b2.username != "'.$username.'" and b1.urlID = b2.urlID limit '.$max_count.';';
        if(($users = $db->query($query)) === false) 
            throw new Exception("DB Query Error: ".$db->error.'<br>'.$query);

        $targetUserArr = array();

        for($i = 0; $i < $users->num_rows; ++$i) {
            $r = $users->fetch_assoc();
            $other_username = $r['username'];
            $query = 'select count(*) from Bookmark as b1, Bookmark as b2 where b1.username="'.$username.'" and b2.username ="'.$other_username.'" and b1.urlID = b2.urlID';
            $common_url_count_result = $db->query($query);
            if($common_url_count_result === false) {
                $users->free();
                database_close($db);
                throw new Exception($db->error);
            }
            $common_url_count = $common_url_count_result->fetch_assoc();
            $common_url_count = $common_url_count['count(*)'];
            $common_url_count_result->free();

            if($common_url_count >= RECOMMEND_COMMON_URL_LIMIT) {
                array_push($targetUserArr, $other_username);
            }
        }
        $users->free();

        if(count($targetUserArr) > 0) {

            $query = 'select urlID from Bookmark where username in (';
            for($i = 0; $i < count($targetUserArr); ++$i) {
                $query = $query.'"'.$targetUserArr[$i].'"';
                if($i != count($targetUserArr) - 1)
                    $query = $query.', ';        
            }
            $query = $query.')';
            $query = $query.' and urlID not in (select urlID from Bookmark where username = "'.
                     $username.'")';
            $query = 'select urlID, url, visitfreq from URL where urlID in ('.$query.') order by visitfreq DESC limit '.$max_count.';';
            
            $result = $db->query($query);
            if($result === false) {
                throw new Exception($db->error.'<br>'.$query);
                database_close($db);
            }
            for($i = 0; $i < $result->num_rows; ++$i) {
                $curRes = $result->fetch_assoc();
                $resultArr[$i] = new Bookmark;
                $resultArr[$i]->url = $curRes['url'];
                $resultArr[$i]->urlID = $curRes['urlID'];
                $resultArr[$i]->visitfreq = $curRes['visitfreq'];

                $query = 'select bookmarkname, bookmarkid from Bookmark where urlID = "'.$curRes['urlID'].'" order by visitfreq limit 1;';
                $nameres = $db->query($query);
                $bestname = $nameres->fetch_assoc();
                $resultArr[$i]->name = $bestname['bookmarkname'];
                $resultArr[$i]->ID = $bestname['bookmarkid'];
                $nameres->free();
            }
            $result->free();
        }
        database_close($db); 
    }
    catch(Exception $e) {
        throw $e;
    }

    return $resultArr;
}

function add_new_bookmark($username, $markname, $markurl) {
    $db = NULL;
    try{
        $db = database_connect();

        //check whether the same name exists.
        $query = 'select * from Bookmark where username="'.$username.'" and bookmarkname="'.$markname.'";';
        if(($result = $db->query($query)) === false){
            throw new Exception("DB query unique bookmarkname fails.");
        }
        if($result->num_rows > 0) {
            $bookmark = $result->fetch_assoc();
            $markurlid = $bookmark['urlID'];
            $query = 'select url from URL where urlID='.$markurlid;
            if(($curresult = $db->query($query)) === false){
                throw new Exception("DB query bookmark URL fails.");
            }
            $info = 'Sorry, a bookmark with the same name already exists:'.
                    'Name: '.$markname.'<br/>'.
                    'URL : '.$curresult.'<br/>';
            $curresult->free();
            $result->free();
            throw new Exception($info);
        }
        $result->free();
        
        $query = 'start transaction;';
        if($db->query($query) === false){
            throw new Exception("DB Transaction Start Fails.");
        }

        $query = 'insert into URL values (NULL, \''.$markurl.'\', 1) on duplicate key update visitfreq=visitfreq+1;';
        if($db->query($query) === false){
            throw new Exception("Insert URL fails.");
        }

        $query = 'select urlID from URL where url="'.$markurl.'";';
        if(($urlIDResult=$db->query($query)) === false){
            throw new Exception("DB Query URLID fails.");
        }
        $urlID = $urlIDResult->fetch_assoc();
        $urlID = $urlID['urlID'];
        $urlIDResult->free();

        $query = 'insert into Bookmark values (NULL, "'.$username.
                 '", "'.$markname.'", '.$urlID.', 1)'; 
        if($db->query($query) === false){
            throw new Exception("Insert bookmark fails.");
        }
        
        $query = "commit";
        if($db->query($query) === false){
            throw new Exception("Commit DB Tx fails.");
        }
    }
    catch(Exception $e) {
        if($db != NULL && !$db->connect_error) {
            database_close($db);
        }
        throw $e;
    }
    database_close($db);
}
    
function delete_bookmark($bookmarkid) {
    $db = NULL;
    try{
        $db = database_connect();

        $query = 'delete from Bookmark where bookmarkid='.$bookmarkid;
        if($db->query($query) === false)
            throw new Exception('Delete bookmark from Bookmark table fails');

    }
    catch(Exception $e) {
        if($db != NULL && !$db->connect_error) {
            database_close($db);
        }
        throw $e;
    }
    database_close($db);
}

function extract_bookmark(&$is_url){
    $post_keys  = array_keys($_POST);
    for($i = 0; $i < count($post_keys); ++$i) {
        if(ereg('^submit_.*', $post_keys[$i])) {
            $target_key = $post_keys[$i];
            if(isset($is_url))
                $is_url = false;
            return substr($target_key, strlen('submit_'));
        }
        else if(ereg('^submiturl_.*', $post_keys[$i])) {
            $target_key = $post_keys[$i];
            if(isset($is_url))
                $is_url = true;
            return substr($target_key, strlen('submiturl_'));
        }
    }
    return false;
}

function account_bookmark_link($username, $bookmark, $is_url_id) {
    $db = NULL;
    $url = NULL;
    try{
        $db = database_connect();
        $urlid = NULL;

        if($is_url_id) {
            $urlid = $bookmark;
            $query = 'select url from URL where urlID = '.$urlid;
            if(($urlResult = $db->query($query)) === false)
                throw new Exception("DB Query URL by URLID failed.");
            $url = $urlResult->fetch_assoc();
            $url = $url['url'];
            $urlResult->free();
        }
        else {
            $query = 'select urlID from Bookmark where bookmarkid = '.$bookmark;
            if(($urlidResult = $db->query($query)) === false)
                throw new Exception("DB Query URLID by bookmark name failed.");
            $urlid = $urlidResult->fetch_assoc();
            $urlid = $urlid['urlID'];
            $urlidResult->free();

            $query = 'select url from URL where urlID = '.$urlid;
            if(($urlResult = $db->query($query)) === false)
                throw new Exception("DB Query URL by URLID failed.");
            $url = $urlResult->fetch_assoc();
            $url = $url['url'];
            $urlResult->free();
        }
        
        // Account for this url access
        // No need to use transaction because we do not need strict consistent in accouting case.
        $query = 'update URL set visitfreq=visitfreq+1 where urlID = '.$urlid;
        if($db->query($query) === false)
            throw new Exception("Account for URL table failed.");
        
        $query = 'update Bookmark set visitfreq=visitfreq+1 where username="'.$username.'" and urlID = '.$urlid;
        if($db->query($query) === false)
            throw new Exception("Account for Bookmark table failed.");
    }
    catch(Exception $e) {
        if($db != NULL && !$db->connect_error) {
            database_close($db);
        }
        throw $e;
    }
    database_close($db);
    return $url;
    
}

?>
