<?php
session_start();
include "includes/checklogin.php";
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
    $PAGETITLE = "Create a List";
    include "includes/head.php";
    ?>
  </head>
  <body>
    <div class="buck-img">
      <?php
      include "includes/header.php";
      include "includes/additemmodal.php";
      ?>
      <div class="addlist-container">
        <h1>Create a bucket list</h1>
          <div class = "button-container">
              <button onclick="document.getElementById('modal-window').style.display='block'"
              id="addlist-button">Add a list item</button>
        </div>
      </div>
    </div>
  </body>
</html>
