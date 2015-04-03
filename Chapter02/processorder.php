<!DOCTYPE HTML>
<html>
<head>
  <title>Bob's Auto Parts - Order Results</title>
</head>
<body>
<h1>Bob's Auto Parts</h1>
<h2>Order Results</h2>
<?php
    $tireqty = $_POST['tireqty'];
    $oilqty = $_POST['oilqty'];
    $sparkqty = $_POST['sparkqty'];
    $source = $_POST['find'];
    $address = $_POST['address'];

    // if(!isset($_POST['tireqty'] || !isset($_POST['oilqty']) || !isset($_POST['sparkqty'])) {
    if($tireqty == 0 || $oilqty == 0 || $sparkqty == 0) {   
        echo "<p>Sorry. Please fill the form completely!</p>";
    }
    else {

        define(TIREPRICE, 12);
        define(OILPRICE, 10);
        define(SPARKPRICE, 5);
        define(TAXRATE, 0.03);

        $date_of_order = date("l jS \of F Y h:i:s A");

        echo '<p><strong>Your order has been successfully processed at <br />';
        echo $date_of_order."</strong></p><br />";

        echo "You ordered $tireqty  tires<br />";
        echo "You ordered $oilqty   oil<br />";
        echo "You ordered $sparkqty spark<br />";
        
        $totalPrice = $tireqty*TIREPRICE + $oilqty*OILPRICE + SPARKPRICE*$sparkqty;
        
        echo "<p>Total Price before taxing: ".$totalPrice."</p><br />"; 

        $totalPrice *= (1.0 + TAXRATE);
        echo "<p>Total Price including tax: ".$totalPrice."</p><br />";

        echo "We will deliver to the following address:<br />";
        echo $address.'<br />';

        echo "<i>You know us from source $source. We will call you for feedback in two ~ three days. Thanks.</i>";

        $fd = fopen("../orders/customer_order.txt", "a");
        if(!$fd) { 
            echo "Failed to save your order into our database. Please contact the administrator. <br />";
        }
        else {
            $datarecord = $date_of_order."\t".$tireqty."\t".$oilqty."\t".$sparkqty."\t".$address."\t".$totalPrice."\n";
            flock($fd, LOCK_EX);
            fwrite($fd, $datarecord);
            flock($fd, LOCK_UN);
            fclose($fd);
        }
    }
?>
</body>
</html>
