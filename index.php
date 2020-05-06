<?php
session_start();
include "includes/library.php";
$loggedin = false;
if(isset($_SESSION['user'])){
  $loggedin = true;
}
?>

<!DOCTYPE html>
<html>

<head>
<?php
$PAGETITLE = "Home";
include "includes/head.php";
?>
</head>
<body>
 <div class="home-img">
 <?php
 include "includes/header.php";
 ?>
   <h1 class="quote typewriter">Create a bucket list of your dreams...</h1>
 </div>
</body>
</html>
