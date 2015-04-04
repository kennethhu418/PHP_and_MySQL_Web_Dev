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
            $datarecord = fgets($fd, 1000);
            $splitArr = explode("\t", $datarecord);
            $splitCount = count($splitArr);
            if(count($splitArr) < 2)
                break;
            
            if($splitCount == 3) {
                continue;
            }

            echo "<h2>Order ".$recordindex."</h2>";
            echo "Time: ".$splitArr[0]."<br />";
            echo "Total Price: ".$splitArr[1]."<br />";
            echo "Delivery to: ".$splitArr[2]."<br /><br />";

            echo "<table border=\"1\">";
            echo "<tr>";
            echo "<td align=\"center\"><strong>Pet</strong></td>";
            echo "<td align=\"center\"><strong>Count</strong></td>";
            echo "</tr>";

            for($i = 3; $i < $splitCount; $i += 2) {
                echo "<tr>";
                echo "<td align=\"center\">$splitArr[$i]</td>";
                echo "<td align=\"center\">".$splitArr[$i + 1]."</td>";
                echo "</tr>";
            }

            echo "</table>";

            $recordindex++;
        }

        flock($fd, LOCK_UN);
        fclose($fd);
    endif;
?>
</body>
</html>
