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
$PAGETITLE = "Public List";
include "includes/head.php";
?>
</head>

<body>
  <div class="buck-img">
  <?php
  include "includes/header.php";
  include "includes/feellucky.php";
  ?>
 <h1>Publicly viewable bucket list items</h1>
 <div class = "list-container"> <!--White Background -->
   <button onclick="luckylistitem(<?php echo ($loggedin ? $_SESSION['id'] : "")?>)" id="modalbutton" style="width:auto;">I feel lucky</button>
 <?php
 $pdo = & connectDB();

 $sql = "SELECT id, listtitle, listcompleted, listpublic, listcompletedate, listdescription, listimage FROM bucketItems WHERE listpublic = :public";
 $stmt = $pdo->prepare($sql);
 $stmt -> bindValue(":public", "1");
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

     echo '<div class="item-container"><div class= "list-item"><div class="item-img"><img src="';
     echo $listimage;
     echo '" class="bucket-image"></div><div class="item-title"><h1>';
     echo $listtitle;
     echo '</h1><div class = "icons"> </div><div class="date-completion"><!--completed at--><p><span>Date completed:</span>';
     echo $listcompletedate;
     echo '</p><div class="item-description"><p><span>Description:</span>';
     echo $listdescription;
     echo '</div></div></div></div></div>';
   }
 }
 ?>
 </div>
 </div>
</body>
</html>
