<html>
<head>
    <title>Upload Files</title>
</head>
<body>
<!== first get the whether information of London  ==>
<h2 align='center'> Whether Information:</h2>
<div align='center'>
<?php 
    echo date('l jS \of F Y h:i:s A').'<br/>';

    $whether_query = 'http://api.openweathermap.org/data/2.5/weather?q=London,uk';
    $whether_json  = file_get_contents($whether_query);
    if(false === $whether_json) {
        echo "Currently cannot contact the weather server. Please retry later.<hr/>";
    }
    else {
        $obj = json_decode($whether_json, true);
        if($obj === false || $obj == NULL) {
            echo "Error decoding json<br/>";
            echo json_last_error_msg();
        }
        else {
            echo "City: London\tWeather: ".
                    $obj['weather'][0]['description']."\tTemp: ".
                    $obj['main']['temp'].'<br/><br>';
        }
    }
?>
</div>

<!== Now let user upload the files ==>
<form action='process_upload.php' method='post' align='center' enctype='multipart/form-data'>
    <h2>Please select the files you want to upload:</h2>
    <input type='file' name='userfile[]' width='60' multiple/>
    <input type='submit' name='submit' value='Upload' />
</form>
</body>
</html>
