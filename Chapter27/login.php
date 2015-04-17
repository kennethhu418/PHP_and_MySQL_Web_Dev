<?php
   require('config.php'); 
   require('htmloutput.php');

   forcessl();
   session_start();


   if(!empty($_SESSION['username'])) {
    header("Location:".HOME_PAGE);
    exit;
   }

   if(!isset($_POST['name']) && !isset($_POST['pwd'])) {
       compose_login_page();
   }
   else {
       try {
           $name = $_POST['name'];
           $pwd = $_POST['pwd'];

           validate_login($name, $pwd);
           header("Location:".HOME_PAGE); 
       }
       catch(Exception $e) {
           show_login_fail($e->getMessage()); 
       }
   }

?>
