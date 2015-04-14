<html>
<head>
    <title>File Upload Status</title>
</head>
<body>
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


   echo "<h2>Successfully Uploaded File: ".$basename."</h2>";
}
?>
</body>
</html>
