<!DOCTYPE HTML>
<html>
<head>
  <title>Bob's Auto Parts - Order Results</title>
</head>
<body>
<h1>Bob's Auto Parts</h1>
<h2>Order Results</h2>
<?php
    $petPrice = array('Cat'=>100.0, 'Dog'=>320.0, 'Bunny'=>30.0);

    $hasOrder = false;
    $totalPrice = 0;
    $date_of_order = date("l jS \of F Y h:i:s A");

    $fd = fopen("../orders/customer_order.txt", "a");
    if(!$fd) { 
        echo "Failed to save your order into our database. Please contact the administrator. <br />";
        echo "</body>";
        echo "</html>";
        exit;
    }
    else {
        $datarecord = $date_of_order;

        while(list($name, $price) = each($petPrice)) {
            $cur_qty = $_POST[$name.'_qty'];
            if($cur_qty > 0) {
                $hasOrder = true;
                $totalPrice += $price * $cur_qty;
            }   
        }

        $datarecord = $datarecord."\t".$totalPrice;
        $datarecord = $datarecord."\t".$_POST['address'];

        reset($petPrice);

        while(list($name, $price) = each($petPrice)) {
            $cur_qty = $_POST[$name.'_qty'];
            if($cur_qty > 0) {
                echo $name.': '.$cur_qty.'<br />';
                $datarecord = $datarecord."\t".$name."\t".$cur_qty;
            }   
        }
        
        $datarecord = $datarecord."\n";

        flock($fd, LOCK_EX);
        fwrite($fd, $datarecord);
        flock($fd, LOCK_UN);
        fclose($fd);
    }

    echo '<p><strong>Your order has been successfully processed at <br />';
    echo $date_of_order."</strong></p><br />";
    
    echo "<p>Total Price: ".$totalPrice."</p><br />";

    echo "We will deliver to the following address:<br />";
    echo $_POST['address'].'<br />';

    echo "<i>You know us from source ".$_POST['find'].". We will call you for feedback in two ~ three days. Thanks.</i>";
?>
</body>
</html>
