<html>
<head>
  <title>Book-O-Rama Search Results</title>
</head>
<body>
<h1>Book-O-Rama Search Results</h1>
<?php
  // create short variable names
  $searchtype=$_POST['searchtype'];
  $searchterm=trim($_POST['searchterm']);
  $magic_quotes = get_magic_quotes_gpc();

  if (!$searchtype || !$searchterm) {
     echo 'You have not entered search details.  Please go back and try again.';
     exit;
  }

  if (!$magic_quotes){
    $searchtype = addslashes($searchtype);
    $searchterm = addslashes($searchterm);
  }

  @ $db = new mysqli('localhost', 'kenneth', 'kenneth', 'books');

  if (mysqli_connect_errno()) {
     echo 'Error: Could not connect to database.  Please try again later.';
     echo "</body></html>";
     exit;
  }

  $query = "select * from Books where ".$searchtype.' regexp "'.$searchterm.'";';
  $result = $db->query($query);

  if(!$result) {
    echo "<span style=\"color:red\"><i>SQL Query Error. Please check your SQL sentense:</i></span><br />";
    echo $query."<br />";
    echo "</body></html>";
    exit;    
  }

  $num_results = $result->num_rows;

  echo "<p>Number of books found: ".$num_results."</p>";

  for ($i=0; $i <$num_results; $i++) {
     $row = $result->fetch_object();
     echo "<p><strong>".($i+1).". Title: ";
     echo htmlspecialchars($magic_quotes?$row->Title:stripslashes($row->Title));
     echo "</strong><br />Author: ";
     echo $magic_quotes?$row->Author:stripslashes($row->Author);
     echo "<br />ISBN: ";
     echo $magic_quotes?$row->ISBN:stripslashes($row->ISBN);
     echo $magic_quotes?$row->Price:stripslashes($row->Price);
     echo "</p>";
  }

  $result->free();
  $db->close();

?>
</body>
</html>
