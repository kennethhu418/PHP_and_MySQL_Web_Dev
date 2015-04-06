<?php

function throwExceptionTest() {
  throw new Exception("A terrible error has occurred", 42);
}

try  {
    throwExceptionTest();
}
catch (Exception $e) {
  echo "Exception ". $e->getCode(). ": ". $e->getMessage()."<br />".
  " in ". $e->getFile(). " on line ". $e->getLine(). "<br />";
  echo "Backtrace:<br /><pre style=\"font-size:130%\">".$e->getTraceAsString()."</pre><br />";
}

?>
