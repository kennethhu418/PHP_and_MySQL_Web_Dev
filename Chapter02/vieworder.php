<!DOCTYPE HTML>
<html>
<head>
  <title>Bob's Auto Parts - Order List</title>
</head>
<body>
<h1>Bob's Auto Parts</h1>
<h2>Order List</h2>
<?php
    $fd = fopen("../orders/customer_order.txt", "rb");
    if(!$fd) :
        echo "Sorry, failed to open database. Please contact your administrator. <br\>";
    else :
        flock($fd, LOCK_SH);
        $recordindex = 1;
        while(!feof($fd)) {
            $recorddata = fgetcsv($fd, 1000, "\t");
            if(count($recorddata) < 6)
                break;
            echo "<h2>Order $recordindex:</h2>";
            echo "Date: $recorddata[0] <br />";
            echo "Tire Count: $recorddata[1] <br />";
            echo "Oil Count: $recorddata[2] <br/>";
            echo "Spark Count: $recorddata[3] <br />";
            echo "Delivery Address: <i>$recorddata[4]</i> <br />";
            echo "Total Price: $recorddata[5] <br />";
            $recordindex++;
        }
        flock($fd, LOCK_UN);
        fclose($fd);
    endif;
?>
</body>
</html>
