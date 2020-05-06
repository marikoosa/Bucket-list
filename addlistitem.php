<?php
session_start();
include "includes/checklogin.php";
include "includes/library.php";
  if (isset($_POST['submit'])){

    $pdo = & connectDB();

    $formtitle = filter_var($_POST['e-title'], FILTER_SANITIZE_STRING);
    $formpublic = $_POST['public'];
    if ($formpublic == "1") {$formpublic="b'1'";}
    else {$formpublic = "b'0'";}

    //check for existing email
    $sql="INSERT INTO bucketItems(listtitle, listpublic, listuserid) VALUES (:listtitle, :listpublic, :listuserid)";
    $stmt=$pdo->prepare($sql);
    $stmt -> bindParam(":listtitle", $formtitle);
    $stmt -> bindParam(":listpublic", $formpublic);
    $stmt -> bindParam(":listuserid", $_SESSION["id"]);
    $stmt->execute();

    header("Location:bucketlist.php");
  }
  else {
    header("Location:index.php");
  }
 ?>
<!DOCTYPE html>
<html>
<head>
<?php
$PAGETITLE = "Add item";
include "includes/head.php";
?>
</head>
<body>
  <body>
   <div class="modal-container">
<div class = "list-container"> <!--White Background -->
<h2 class="add-listitem">Add a list item</h2>
  <div class="item-container"> <!-- padding for list items -->
    <div class= "list-item" id="item-add"> <!--bucket list element -->
      <form action="addlistitem.php" class="additem-form">
      <label for="title">List item title</label>
      <input type="text" id="element-title" name="e-title"><br>
      <label for="public-list">Make list public</label>
      <input type="checkbox" name="public" id="make-public" value="1"></br>
      <input type="submit" name="submit" value="Add Item" id="add-item">
    </form>
        </div>
      </div>
  </div>
</div>
</div>
</div>
</body>
</html>
