<?php
include 'includes/library.php';
$pdo =  & connectDB();

$id = $_POST['itemid'];
$userid = $_POST["userid"];

$sql = "DELETE FROM bucketItems WHERE id = :id AND listuserid=:listuserid";
$stmt=$pdo->prepare($sql);
$stmt -> bindParam(":id", $id);
$stmt -> bindParam(":listuserid", $userid);
$stmt->execute();
?>
