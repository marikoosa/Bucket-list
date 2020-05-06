<?php
if(!isset($_SESSION['user'])){
  $loggedin = false;
  header("Location:login.php");
  exit();
}
else{
  $loggedin = true;
}
?>
