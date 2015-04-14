<html>
<head>
    <title>View Files</title>
</head>
<body>

<h2>List of Files in your working directory:</h2><hr/>

<?php 
//require('config.php');
define("UPLOAD_DIR", 'upload/');

$dir = opendir(UPLOAD_DIR);
if($dir === false) {
    echo "Error to open your directory.<br/>";
}
else {
    while(($file = readdir($dir)) !== false) {
        if($file == '.' || $file == '..')
            continue;
        $filelink = pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME);
        $filelink = $filelink.'/'.UPLOAD_DIR.$file;
        echo "<a href=\"$filelink\" style=\"font-size:120%\">";
        echo $file."<hr/>";
        echo "</a>";
    }
}
?>
</body>
</html>
