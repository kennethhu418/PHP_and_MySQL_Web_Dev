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

    // if(!isset($_POST['tireqty'] || !isset($_POST['oilqty']) || !isset($_POST['sparkqty'])) {
    if($tireqty == 0 || $oilqty == 0 || $sparkqty == 0) {   
        echo "<p>Sorry. Please fill the form completely!</p>";
    }
    else {

        define(TIREPRICE, 12);
        define(OILPRICE, 10);
        define(SPARKPRICE, 5);
        define(TAXRATE, 0.03);

        echo '<p><strong>Your order has been successfully processed at <br />';
        echo date("l jS \of F Y h:i:s A")."</strong></p><br />";
        
        $totalPrice = $tireqty*TIREPRICE + $oilqty*OILPRICE + SPARKPRICE*$sparkqty;
        
        echo "<p>Total Price before taxing: ".$totalPrice."</p><br />"; 
        echo "<p>Total Price including tax: ".($totalPrice*(1.0 + TAXRATE))."</p><br />";

        echo "<i>You know us from source $source. We will call you for feedback in two ~ three days. Thanks.</i>";
    }
?>
</body>
</html>
