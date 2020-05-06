<?php
include 'includes/library.php';
$pdo =  & connectDB();

$itemid = $_GET['itemid'];

$sql = "SELECT listuserid, listtitle, listcompleted, listpublic, listcompletedate, listdescription, listimage FROM bucketItems WHERE id = :itemid";
$stmt = $pdo->prepare($sql);
$stmt -> bindParam(":itemid", $itemid);
$stmt -> execute();
$row = $stmt -> fetch();
$listid = $row["listuserid"];
$listtitle = $row["listtitle"];
$listcompleted = $row["listcompleted"];
$listpublic = $row["listpublic"];
$listcompletedate = $row["listcompletedate"];
$listdescription = $row["listdescription"];
$listimage = $row["listimage"];

if ($listpublic == "1") {
  $listpublic = true;
}
else {
  $listpublic = false;
}

if ($listcompleted == "1") {
  $listcompleted = true;
}
else {
  $listcompleted = false;
}
$getObj = new \stdClass();
$getObj->listtitle = $listtitle;
$getObj->listdescription = $listdescription;
$getObj->listcompletedate = $listcompletedate;
$getObj->listpublic = $listpublic;
$getObj->listcompleted = $listcompleted;

$getJSON = json_encode($getObj);

echo $getJSON;
?>
