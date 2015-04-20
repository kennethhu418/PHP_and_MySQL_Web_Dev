<?php
   require_once('business_logic.php'); 
   require_once('htmloutput.php');

   forcessl();
   session_start();


   if(!empty($_SESSION['username'])) {
    header("Location:".HOME_PAGE);
    exit;
   }

   if(!isset($_POST['email'])) {
       compose_register_page();
   }
   else {
       try {
           $name = trim($_POST['name']);
           $pwd = $_POST['pwd'];
           $mail = trim($_POST['email']);

           validate_user_login_input_by_policy($name, $pwd, $mail);
           register_user($name, $pwd, $mail);       
           $_SESSION['username'] = $name;

           show_register_success($name);
       }
       catch(Exception $e) {
           show_register_fail($e->getMessage());
       }
   }
?>
