<html>
<head>
    <title>Test Only</title>
</head>
<body>
<?php 
    //$pathname = "http://www.w3schools.com/php/func_filesystem_pathinfo.asp";
    $pathname = $_SERVER['PHP_SELF'];
    $info = pathinfo($pathname);
    print_r($info);
?>
</body>
</html>
