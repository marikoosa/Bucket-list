<?php
include 'includes/library.php';
$pdo =  & connectDB();

$username = filter_var($_GET['username'], FILTER_SANITIZE_STRING);

$sql = "SELECT username FROM bucketUsers WHERE username = :username";
$stmt=$pdo->prepare($sql);
$stmt -> bindParam(":username", $username);
$stmt->execute();
if($stmt->fetchColumn()){
  echo true;
} else {
  echo false;
}
?>
