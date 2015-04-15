<?php
session_start();
require('forcessl.php');
require('config.php');

forcessl($_SERVER['PHP_SELF']);

$username = $_SESSION[SS_USER_NAME];

$db = new mysqli(DATABASE_ADDR, DATABASE_ADMIN_NAME, DATABASE_ADMIN_PWD, BOOK_DATABASE);
if($db->connect_error) {
    echo "Books Database connection error: ".$db->connect_error;
    exit;
}

$query = "select Orders.OrderID, Orders.Date, Orders.Amount from Customers, Orders where Customers.Name = '$username' and Orders.CustomerID = Customers.CustomerID;";
$result = $db->query($query);
if($result === false) {
    echo "Some thing is wrong with customer database query: ".$query.'<br/>';
    $db->close();
    exit;
}

for($curOrder = 0; $curOrder < $result->num_rows; ++$curOrder) {
    $order_result = $result->fetch_assoc();
    echo '<h2>Order '.(1+$curOrder).'</h2>';
    echo 'Date:  '.$order_result['Date'].'<br/>';
    echo 'Amount:  '.$order_result['Amount'].'<br/><br/>';
   
    $query = "select Books.Title, Order_Items.Quantity from Order_Items, Books where Order_Items.OrderID = '".$order_result['OrderID']."' and Order_Items.ISBN = Books.ISBN;";
    $order_result = $db->query($query);
    if($order_result === false) {
        echo "Some thing is wrong with order database query: ".$query.'<br/>';
        $result->free();
        $db->close();
        exit;
    }

    echo '<table border="1">';
    echo '<tr><td>Book Title</td><td>Quantity</td></tr>';
    for($i = 0; $i < $order_result->num_rows; ++$i) {
        $book = $order_result->fetch_assoc();
        echo '<tr>';
        echo '<td>'.$book['Title'].'</td>';
        echo '<td>'.$book['Quantity'].'</td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '<br/>';

    $order_result->free();
}

$result->free();
$db->close();
?>
