<html>
<head>
  <title>Book-O-Rama Book Entry Results</title>
</head>
<body>
<h1>Book-O-Rama Book Entry Results</h1>
<?php
  // create short variable names
  $isbn=trim($_POST['ISBN']);
  $author=trim($_POST['Author']);
  $title=trim($_POST['Title']);
  $price=trim($_POST['Price']);

  if (!$isbn || !$author || !$title || !$price || !is_numeric($isbn) || !is_numeric($price)) {
     echo "You have not entered all the required details or you entered invalid number.<br />"
          ."Please go back and try again.";
     echo '</body></html>';
     exit;
  }

  $isbn = htmlspecialchars($isbn);
  $author = htmlspecialchars($author);
  $title = htmlspecialchars($title);

  $db = new mysqli("localhost", "kenneth", "kenneth", "books");
  if(mysqli_connect_errno()) {
    echo "Fail to connect to the books database.<br/>";
    echo '</body></html>';
    exit;
  }

  $query = "insert LOW_PRIORITY into Books values (\"$isbn\", \"$author\", \"$title\", \"$price\");";
  if($db->query($query) === false) {
    echo "Fail to query database with: <i>".$query."</i><br />";
    echo '</body></html>';
    $db->close();
    exit;
  }

  $db->close();
  echo "<strong>Successfully insert your book into the store. Thanks for your visit.<strong><br/><br/>";
  echo "Inserted Book:<br/>";
?>

  <table border="1">
  <tr>
    <td>"ISBN"</td>
    <td><?php echo $isbn; ?></td>
  <tr>
  <tr>
    <td>"Author"</td>
    <td><?php echo $author; ?></td>
  <tr>
  <tr>
    <td>"Title"</td>
    <td><?php echo $title; ?></td>
  <tr>
  <tr>
    <td>"Price"</td>
    <td><?php echo $price; ?></td>
  <tr>
  <table>

</body>
</html>
