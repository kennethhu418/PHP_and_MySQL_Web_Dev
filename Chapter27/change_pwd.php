<?php
   require_once('business_logic.php'); 
   require_once('htmloutput.php');

   forcessl();
   login_detect();

   if(!isset($_POST['oldpwd']) && !isset($_POST['newpwd']) && !isset($_POST['newpwd_confirm'])) {
       compose_change_pwd_page();
   }
   else {
       try {
           $name   = $_SESSION['username'];
           $oldpwd = $_POST['oldpwd'];
           $newpwd = $_POST['newpwd'];
           $newpwd2 = $_POST['newpwd_confirm'];

           change_user_password($name, $oldpwd, $newpwd, $newpwd2);
           show_change_pwd_success($name); 
       }
       catch(Exception $e) {
           show_change_pwd_fail($e->getMessage()); 
       }
   }

?>
