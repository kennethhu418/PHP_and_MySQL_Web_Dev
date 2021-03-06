<?php
require_once('config.php');

function redirect() {
    echo "<span style=\"color:red\">You did not upload any file. Please upload again.</span><br/><br/>";
    $dirname = 'http://'.$_SERVER['SERVER_ADDR'];
    $dirname = $dirname.'/'.pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME);
    $dirname = $dirname.'/'.'upload.html';
    echo '<a href="'.$dirname.'">Retry</a>';
    echo "</body></html>";
    exit;
}

function backup_file_connect() {
    $conn_handle = ftp_connect(BACKUP_HOST, BACKUP_PORT);
    $login_result = ftp_login($conn_handle, BACKUP_USERNAME, BACKUP_PWD);
    if(!$conn_handle || !$login_result) {
        return false;
    }
    if(!ftp_chdir($conn_handle, BACKUP_DIR)){
        return false;
    }
    return $conn_handle;
}

function backup_file_by_ftp($conn_handle, $filepath, $filename) {
    if(false === ftp_put($conn_handle, $filename, $filepath.$filename, FTP_BINARY)) 
        return false;
    return true;
}

function backup_file_close($conn_handle) {
    ftp_close($conn_handle);
}
?>

<html>
<head>
    <title>File Upload Status</title>
</head>
<body>

<?php
$filecount = count($_FILES['userfile']['tmp_name']);
$fileerror = false;

for($i = 0; $i < $filecount; ++$i) {
    if($_FILES['userfile']['error'][$i] > 0) {
        echo '<span style="color:red">Problem of file '.$_FILES['userfile']['name'][$i].': ';
        switch ($_FILES['userfile']['error'][$i])
        {
          case 1:	echo 'File exceeded upload_max_filesize';
                    break;
          case 2:	echo 'File exceeded max_file_size';
                    break;
          case 3:	echo 'File only partially uploaded';
                    break;
          case 4:   redirect();
                    break;
          case 6:   echo 'Cannot upload file: No temp directory specified.';
                    break;
          case 7:   echo 'Upload failed: Cannot write to disk.';
                    break;
          default:
            echo $_FILES['userfile']['error'][$i];
        }
        echo "</span><br/>";
        $fileerror = true;
    }
}

if($fileerror) {
    echo "<br/><b>Sorry, fail to upload file(s). Please retry.</b><br/>";
    echo "</body></html>";
    exit;
}

if(false === ($ftphandle = backup_file_connect())) {
    echo "Fail to connect to the backup server. Sorry.<br/>";
    echo "</body></html>";
    exit;
}

for($i = 0; $i < $filecount; ++$i) {
   if(is_uploaded_file($_FILES['userfile']['tmp_name'][$i]) === false) {
    echo "<span style=\"color:red\">Found dangerous file: ".$_FILES['userfile']['tmp_name'][$i]."</span> <hr />";
    continue;
   } 
   
   $basename = basename($_FILES['userfile']['name'][$i], ".php");
   if(move_uploaded_file($_FILES['userfile']['tmp_name'][$i], UPLOAD_DIR.$basename) === false) {
    echo "<span style=\"color:red\">Error to move file from tmp directory to destination directory: ".$basename."</span><hr />"; 
    continue;
   }

   // Now backup the file
   if(backup_file_by_ftp($ftphandle, UPLOAD_DIR, $basename) === false)
    echo "<i>\t\tFail to backup the file. However, your file still could be uploaded to our main server.</i><br/>";

   echo "<h2>Successfully Uploaded File: ".$basename."</h2>";
}

backup_file_close($ftphandle);
?>
</body>
</html>
