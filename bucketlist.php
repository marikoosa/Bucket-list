<?php
session_start();
include "includes/checklogin.php";
include "includes/library.php";
$loggedin = false;
if(isset($_SESSION['user'])){
  $loggedin = true;
}
if (isset($_POST['addto-list'])){

  $pdo = & connectDB();

  $formtitle = filter_var($_POST['uname'], FILTER_SANITIZE_STRING);
  if (!isset($_POST['public'])) {
    $formpublic = "0";

  } else {
    $formpublic = '1';
  }

  //check for existing email
  $sql="INSERT INTO bucketItems(listtitle, listpublic, listuserid) VALUES (:listtitle, :listpublic, :listuserid)";
  $stmt=$pdo->prepare($sql);
  $stmt -> bindParam(":listtitle", $formtitle);
  $stmt -> bindParam(":listpublic", $formpublic);
  $stmt -> bindParam(":listuserid", $_SESSION["id"]);
  $stmt->execute();
}

if (isset($_POST['edititem'])){

  $pdo = & connectDB();

  $userid = $_SESSION['id'];
  $formid = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);
  $formtitle = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
  $formdescription = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
  $formcompletedate = filter_var($_POST['date-completed'], FILTER_SANITIZE_STRING);
  $formpublic = !isset($_POST['public']) ? "0" : "1";
  $formcompleted = !isset($_POST['completed']) ? "0" : "1";

  $formimage = "";
  $sqlimg = "";
  $target_dir = "../../www_data/";
  $target_file = $target_dir . $_SESSION['id'] . $formid . basename(filter_var($_FILES["avatar"]["name"], FILTER_SANITIZE_STRING));
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["avatar"]["tmp_name"]);
    if($check !== false) {
      //echo "File is an image - " . $check["mime"] . ".";
      $uploadOk = 1;
    } else {
      //echo "File is not an image.";
      $uploadOk = 0;
    }
  }
  // check file size (5MB)
  if ($_FILES["avatar"]["size"] > 5000000) {
    //echo "Sorry, your file is too large.";
    $uploadOk = 0;
  }
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
    //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
  }
  // Check if file already exists
  if (file_exists($target_file)) {
    //echo "Sorry, file already exists.";
    $uploadOk = 0;
  }
  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    //echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
      //echo "The file ". basename( $_FILES["avatar"]["name"]). " has been uploaded.";
      $formimage = $target_file;
      $sqlimg = " listimage=:listimage,";
    } else {
      //echo "Sorry, there was an error uploading your file.";
    }
  }

  //check for existing email
  $sql="UPDATE bucketItems SET listtitle=:listtitle, listdescription=:listdescription, listcompletedate=:listcompletedate," . $sqlimg . " listpublic=:listpublic, listcompleted=:listcompleted WHERE id=:id AND listuserid=:userid";
  $stmt=$pdo->prepare($sql);
  $stmt -> bindParam(":listtitle", $formtitle);
  $stmt -> bindParam(":listdescription", $formdescription);
  $stmt -> bindParam(":listcompletedate", $formcompletedate);
  if ($sqlimg != "") {$stmt -> bindParam(":listimage", $formimage);}

  $stmt -> bindParam(":listpublic", $formpublic);
  $stmt -> bindParam(":listcompleted", $formcompleted);
  $stmt -> bindParam(":id", $formid);
  $stmt -> bindParam(":userid", $userid);
  $stmt->execute();
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
$PAGETITLE = "Bucketlist";
include "includes/head.php";
?>
</head>
  <body>
   <div class="buck-img">
   <?php
   include "includes/additemmodal.php";
   include "includes/edititemmodal.php";
   include "includes/header.php";
   ?>
<h1>Manage your bucketlist</h1>
<div class = "list-container"> <!--White Background -->
  <button onclick="document.getElementById('modal-window').style.display='block'"
  id="modalbutton" style="width:auto;">Add a list item</button>
<!-- <h2 class="add-listitem">Add a list item <i class="fa fa-plus" style="font-size:24px"></i></h2> -->
  <?php
  $pdo = & connectDB();
  
  $userid = $_SESSION["id"];
  $sql = "SELECT id, listtitle, listcompleted, listpublic, listcompletedate, listdescription, listimage FROM bucketItems WHERE listuserid = :userid ORDER BY listcompleted ASC";
  $stmt = $pdo->prepare($sql);
  $stmt -> bindParam(":userid", $userid);
  if ($stmt -> execute()){
    while ($row = $stmt -> fetch()){
      $listid = $row["id"];
      $listtitle = $row["listtitle"];
      $listcompleted = $row["listcompleted"];
      $listpublic = $row["listpublic"];
      $listcompletedate = $row["listcompletedate"];
      $listdescription = $row["listdescription"];
      $listimage = $row["listimage"];

      if ($listimage == "") {
        $listimage = "./img/placeholder.png";
      }
      if ($listcompleted == "1") {
        $listcompleted = "checked";
      }
      else {
        $listcompleted = "";
      }
      echo '<div class="item-container"><div class= "list-item" id="';
      echo $listid;
      echo '" ><div class="item-img"><img src="';
      echo $listimage;
      echo '" class="bucket-image"></div><div class="item-title"><h1>';
      echo $listtitle;
      echo '</h1><div class = "icons"><i class="fa fa-edit" onClick="editlistitem(this, ';
      echo $userid;
      echo ');"></i><i class="fa fa-trash-o" onClick="deletelistitem(this, ';
      echo $userid;
      echo ');" id="listitemdelete"></i></i></div><div class="date-completion"><p><span>Date completed:</span>';
      echo $listcompletedate;
      echo '</p><div class="item-description"><p><span>Description:</span>';
      echo $listdescription;
      echo '</p></div><div class= "item-completion"> <label for="item-complete">Completed </label><input type="checkbox" id="item-complete" value="completed" ';
      echo $listcompleted;
      echo '></div></div></div></div></div>';
    }
  }
  ?>
</div>
</div>
</body>
</html>
