<?php
include 'includes/library.php';
$pdo =  & connectDB();

$useremail = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);

$sql = "SELECT useremail FROM bucketUsers WHERE useremail = :useremail";
$stmt=$pdo->prepare($sql);
$stmt -> bindParam(":useremail", $useremail);
$stmt->execute();
if($stmt->fetchColumn()){
  echo true;
} else {
  echo false;
}
?>
