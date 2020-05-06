<?php
  if (isset($_POST['submit'])){
    include "includes/library.php";
    $pdo = & connectDB();

    $errors=array();

    $formuser = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $formsecurityq1 = filter_var($_POST['securityq1'], FILTER_SANITIZE_STRING);
    $formsecuritya1 = $_POST['securitya1'];
    $formsecurityq2 = filter_var($_POST['securityq2'], FILTER_SANITIZE_STRING);
    $formsecuritya2 = $_POST['securitya2'];

    //Checking if the email is already used
    $sql="SELECT 1 FROM bucketUsers WHERE username = :username";
    $stmt=$pdo->prepare($sql);
    $stmt -> bindParam(":username", $formuser);
    $stmt->execute();

    $formpass = $_POST['password'];
    $formpassconfirm = $_POST['passwordconfirm'];

    if($stmt->fetchColumn()){
      $errors[] = "The username you entered already exists";
    }

    if ($formpass != $formpassconfirm){
      $errors[]="The passwords that you entered do not match";
    }
    // SANITIZE the emails
    $formemail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    // Validate the email
    if (!filter_var($formemail, FILTER_VALIDATE_EMAIL)){
      $errors[]="Please enter a valid email address";
    } else {
      //Checking if the email is already registered
      $sql="SELECT 1 FROM bucketUsers WHERE useremail = :useremail";
      $stmt=$pdo->prepare($sql);
      $stmt -> bindParam(":useremail", $formemail);
      $stmt->execute();
      if($stmt->fetchColumn()){
        $errors[] = "Email already used";
      }
    }

    //if the errors array is empty connect to the database
    if(sizeof($errors)==0){
      $hashpass = password_hash($formpass, PASSWORD_DEFAULT);
      $hashsecuritya1 = password_hash($formsecuritya1, PASSWORD_DEFAULT);
      $hashsecuritya2 = password_hash($formsecuritya2, PASSWORD_DEFAULT);

      $sql="INSERT INTO bucketUsers (username, useremail, userpass, usersecurityq1, usersecuritya1, usersecurityq2, usersecuritya2) VALUES (:username,:useremail,:userpass,:usersecurityq1,:usersecuritya1,:usersecurityq2,:usersecuritya2)";
      $stmt = $pdo -> prepare($sql);
      $stmt -> bindParam(":username", $formuser);
      $stmt -> bindParam(":useremail", $formemail);
      $stmt -> bindParam(":userpass", $hashpass);
      $stmt -> bindParam(":usersecurityq1", $formsecurityq1);
      $stmt -> bindParam(":usersecuritya1", $hashsecuritya1);
      $stmt -> bindParam(":usersecurityq2", $formsecurityq2);
      $stmt -> bindParam(":usersecuritya2", $hashsecuritya2);
      $stmt -> execute();
      //redirect to index.php
      $_SESSION['user'] = $formuser;
      header("Location:index.php");
      exit();
    }
  }
  $loggedin = false;
  if(isset($_SESSION['user'])){
    $loggedin = true;
  }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php
  $PAGETITLE = "Register";
  include "includes/head.php";
  ?>
  <link rel="stylesheet" href="./css/passtrength.css">

</head>
<body>
  <div class="login-img">
    <?php
    include "includes/header.php";
    ?>
  <div class="reg-container">
  <div class="login-form">
    <form id="form-register" name="form-r" action="register.php" method="post">
      <h1>Register</h1>
      <div class="form-group">
        <input type="username" name="username" placeholder="Username">
        <span class="input-icon"><i class="fa fa-user"></i></span>
      </div>
      <div class="form-group">
        <input type="email" name="email" placeholder="E-mail Address">
        <span class="input-icon"><i class="fa fa-envelope"></i></span>
      </div>
      <div class="form-group">
        <input type="password" name="password" placeholder="Password">
        <span class="input-icon"><i class="fa fa-lock"></i></span>
      </div>
      <div class="form-group">
        <input type="password" name="passwordconfirm" placeholder="Re-enter Password">
        <span class="input-icon"><i class="fa fa-lock"></i></span>
      </div>
      <div class="form-group">
        <input name="securityq1" placeholder="Security Question 1">
        <span class="input-icon"><i class="fa fa-question" style="font-size:24px"></i></span>
      </div>
      <div class="form-group">
        <input name="securitya1" placeholder="Security Q1 Answer">
        <span class="input-icon"><i class="fa fa-pencil-square-o" style="font-size:20px"></i></span>
      </div>
      <div class="form-group">
        <input name="securityq2" placeholder="Security Question 2">
        <span class="input-icon"><i class="fa fa-question" style="font-size:24px"></i></span>
      </div>
      <div class="form-group">
        <input name="securitya2" placeholder="Security Q2 Answer">
        <span class="input-icon"><i class="fa fa-pencil-square-o" style="font-size:20px"></i></span>
      </div>
      <div>
        <input class="login-btn" type="submit" name="submit" value="REGISTER" />
      </div>
      <a class="reset-psw" href="login.php">Already have an account?</a>
    </form>
  </div>
</div>
</div>
</body>
</html>
