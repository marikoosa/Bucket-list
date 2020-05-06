<div class="nav-container">
  <div class="navbar">
    <a href="index.php">Home</a>
    <a href="publiclists.php">Public List</a>

    <?php
    if(!$loggedin){
     $acchref = "login.php";
     $acclabel = "Login";
     $buckethref = "addlist.php";
     $bucketlabel = "Create a Bucket List";
    }
    else{
     $acchref = "account.php";
     $acclabel = "My Account";

     $pdo = & connectDB();

     $sql = "SELECT * FROM bucketItems WHERE listuserid = :id";
     $stmt = $pdo->prepare($sql);
     $stmt -> bindParam(':id', $_SESSION["id"]);
     $stmt -> execute();

     if(!($stmt -> fetch())){
       $buckethref = "addlist.php";
       $bucketlabel = "Create a Bucket List";
     }
     else {
       $buckethref = "bucketlist.php";
       $bucketlabel = "Manage Your Bucket List";
     }
    }

    ?>
    <a href="<?php echo $buckethref?>"><?php echo $bucketlabel?></a>
    <a href="<?php echo $acchref?>"><?php echo $acclabel?></a>
  </div>
</div>
