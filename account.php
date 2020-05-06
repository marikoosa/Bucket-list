<?php
  session_start();
  include "includes/checklogin.php";

  include "includes/library.php";
  $pdo = & connectDB();
  $userid = $_SESSION['id'];

  $sql = "SELECT username, useremail, userpass, usersecurityq1, usersecuritya1, usersecurityq2, usersecuritya2 FROM bucketUsers WHERE id = :id";
  $stmt = $pdo->prepare($sql);
  $stmt -> bindParam(":id", $userid);
  $stmt -> execute();
  $row = $stmt -> fetch();

  $username = $row["username"];
  $useremail = $row["useremail"];
  $userpass = $row["userpass"];
  $usersecurityq1 = $row["usersecurityq1"];
  $usersecuritya1 = $row["usersecuritya1"];
  $usersecurityq2 = $row["usersecurityq2"];
  $usersecuritya2 = $row["usersecuritya2"];

  if (isset($_POST['submit'])){
    //if updating information
    $formoldpass = $_POST['oldpassword'];
    //check if old password is correct
    if (password_verify($formoldpass, $row['userpass'])){
      $formuser = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
      $formemail =  filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
      $formsecurityq1 = filter_var($_POST['securityq1'], FILTER_SANITIZE_STRING);
      $formsecuritya1 = $_POST['securitya1'];
      $formsecurityq2 = filter_var($_POST['securityq2'], FILTER_SANITIZE_STRING);
      $formsecuritya2 = $_POST['securitya2'];
      $errors = array();

      $formpass = $_POST['password'];
      $formpassconfirm = $_POST['passwordconfirm'];
      if ($formpass != ""){ //check if new password entered
        if ($formpass != $formpassconfirm){ //Passwords must be identical
          $errors[]="<h2>Passwords do not match</h2>";
        }
      }


      //if no errors update
      if(sizeof($errors)==0){

        // if no new password set new pass to old pass
        if ($formpass == ""){$hashpass = $userpass;}
        else {$hashpass = password_hash($formpass, PASSWORD_DEFAULT);}
        //if no new security a1
        if ($formsecuritya1 == ""){$hashsecuritya1 = $usersecuritya1;}
        else {$hashsecuritya1 = password_hash($formsecuritya1, PASSWORD_DEFAULT);}
        //if no new security a2
        if ($formsecuritya2 == ""){$hashsecuritya2 = $usersecuritya2;}
        else {$hashsecuritya2 = password_hash($formsecuritya2, PASSWORD_DEFAULT);}

        $sql = "UPDATE bucketUsers SET username = :username, useremail = :useremail, userpass = :userpass, usersecurityq1 = :usersecurityq1, usersecuritya1 = :usersecuritya1, usersecurityq2 = :usersecurityq2, usersecuritya2 = :usersecuritya2 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt -> bindParam(":username", $formuser);
        $stmt -> bindParam(":useremail", $formemail);
        $stmt -> bindParam(":userpass", $hashpass);
        $stmt -> bindParam(":usersecurityq1", $formsecurityq1);
        $stmt -> bindParam(":usersecuritya1", $hashsecuritya1);
        $stmt -> bindParam(":usersecurityq2", $formsecurityq2);
        $stmt -> bindParam(":usersecuritya2", $hashsecuritya2);
        $stmt -> bindParam(":id", $userid);
        $stmt -> execute();
        //redirecting to index.php
        $_SESSION['user'] = $formuser;
        header("Location:index.php");
        exit();
      }
    }
  }
  
  if (isset($_POST['delete'])){
    $formoldpass = $_POST['oldpassword'];
    if (password_verify($formoldpass, $row['userpass'])){
      $sql = "DELETE FROM bucketUsers WHERE id = :id";
      $stmt = $pdo->prepare($sql);
      $stmt -> bindParam(":id", $userid);
      $stmt -> execute();

      session_destroy();
      header("Location:index.php");
      exit();
    }
  }
  if (isset($_POST['logout'])){
    session_destroy();
    header("Location:index.php");
    exit();
  }
  $loggedin = false;
  if(isset($_SESSION['user'])){
    $loggedin = true;
  }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>

  <?php
  $PAGETITLE = "My Account";
  include "includes/head.php";?>
</head>
<body>
  <div class="login-img">
    <!-- navbar -->
    <?php
    include "includes/header.php";
    ?>

  <div class="account-container">
  <div class="login-form">
    <form id="form-register" name="form-register" action="account.php" method="post">
      <h1>My Account</h1>
      <div class="form-group">
        <input type="username" name="username" placeholder="Username" value="<?php echo $username ?>">
        <span class="input-icon"><i class="fa fa-user"></i></span>
      </div>

      <div class="form-group">

        <input type="email" name="email" placeholder="E-mail Address" value="<?php echo $useremail ?>">
        <span class="input-icon"><i class="fa fa-envelope"></i></span>
      </div>

      <div class="form-group">
        <input type="password" name="oldpassword" placeholder="Old Password">
        <span class="input-icon"><i class="fa fa-lock"></i></span>
      </div>

      <div class="form-group">
        <input type="password" name="password" placeholder="New Password">
        <span class="input-icon"><i class="fa fa-lock"></i></span>
      </div>
      <div class="form-group">
        <input type="password" name="passwordconfirm" placeholder="Re-enter New Password">
        <span class="input-icon"><i class="fa fa-lock"></i></span>
      </div>
      <div class="form-group">
        <input type="text" name="securityq1" placeholder="Security Question 1" value="<?php echo $usersecurityq1 ?>">
      </div>
      <div class="form-group">
        <input type="password" name="securitya1" placeholder="Security Q1 Answer">
        <span class="input-icon"><i class="fa fa-pencil-square-o"></i></span>
      </div>
      <div class="form-group">
        <input type="text" name="securityq2" placeholder="Security Question 2" value="<?php echo $usersecurityq2 ?>">
      </div>
      <div class="form-group">
        <input type="password" name="securitya2" placeholder="Security Q2 Answer">
        <span class="input-icon"><i class="fa fa-pencil-square-o"></i></span>
      </div>
      <div class= "btn-container">
      <div>
        <input class="login-btn" type="submit" name="submit" value="SAVE CHANGES" />
      </div>
      </div>

      <div class= "btn-container">
      <div>
        <input class="login-btn" type="submit" name="delete" value="DELETE ACCOUNT" />
      </div>
    </div>

      <div class= "btn-container">
      <div>
        <input class="login-btn" type="submit" name="logout" value="LOGOUT" />
      </div>
    </div>

  </form>
</div>
</div>
</div>
</body>
</html>
