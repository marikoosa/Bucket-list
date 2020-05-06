<?php
include 'includes/library.php';
$pdo =  & connectDB();
$userid = $_GET['id'];
$sqlid = "";
if ($userid != "") {
  $sqlid = " AND NOT listuserid=:listuserid";
}


$sql = "SELECT listtitle, listcompletedate, listdescription, listimage FROM bucketItems WHERE listpublic=:public" . $sqlid . " ORDER BY Rand() LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt -> bindValue(":public", "1");
if ($userid != "") {$stmt -> bindParam(":listuserid", $userid);}
$stmt -> execute();
$row = $stmt -> fetch();
$listtitle = $row["listtitle"];
$listcompletedate = $row["listcompletedate"];
$listdescription = $row["listdescription"];
$listimage = $row["listimage"];

$getObj = new \stdClass();
$getObj->listtitle = $listtitle;
$getObj->listdescription = $listdescription;
$getObj->listcompletedate = $listcompletedate;
$getObj->listimage = $listimage;

$getJSON = json_encode($getObj);

echo $getJSON;

?>
