<?php
   session_start();
   unset($_SESSION["username"]);
   unset($_SESSION["password"]);
   echo 'You has logged out !)';
   header('Refresh: 2; URL = login.php');
   // http://php.net/manual/en/function.header.php
?>