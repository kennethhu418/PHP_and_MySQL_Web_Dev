<html>
<head><title>Bob's Auto Parts</title></head>
<body>
<h1 style="font-size:130%" align="center">Bob's Auto Parts - Order Page</h1>
<form action="processorder.php" method="post">
<table border="0" align="center">
<tr bgcolor="#cccccc">
  <td width="150">Item</td>
  <td width="15">Show</td>
  <td width="15">Quantity</td>
</tr>

<?php
    $pets = array('Cat'=>'cat.jpg', 'Dog'=>'dog.jpg', 'Bunny'=>'bunny.jpg');    

    while(list($name, $pic) = each($pets)) {
        echo "<tr>";
        // name
        echo "<td>";
        echo $name;
        echo "</td>";

        // pic
        echo "<td>";
        echo '<img src='.$pic.' style="width:64"';
        echo "</td>";

        // quantity
        echo "<td>";
        echo '<input type="text" align="center" name='.$name.'_qty />';
        echo "</td>";

        echo "</tr>";
    }
?>

<tr>
  <td>How did you find Bob's?</td>
  <td colspan="2"><select name="find">
        <option value = "a">I'm a regular customer</option>
        <option value = "b">TV advertising</option>
        <option value = "c">Phone directory</option>
        <option value = "d">Word of mouth</option>
      </select>
  </td>
</tr>
<tr>
  <td>Address</td>
  <td colspan="2"><input type="text" name="address" value="Please enter your address here" /></td>
</tr>
<tr>
  <td colspan=3" align="center"><input type="submit" value="Submit Order" /></td>
</tr>
</table>
</form>
</body>
</html>
