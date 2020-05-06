<?php
  session_start();
  $loggedin = false;
  if(isset($_SESSION['user'])){
    $loggedin = true;
  }
  $useremail = "";
  $usersecurityq1 = "";
  $usersecurityq2 = "";

  if(isset($_POST['check'])){
    $formemail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $formemail = filter_var($formemail, FILTER_VALIDATE_EMAIL);

    include 'includes/library.php';
    $pdo = & connectDB();

    $sql = "SELECT usersecurityq1, usersecuritya1, usersecurityq2, usersecuritya2 FROM bucketUsers WHERE useremail = :useremail";
    $stmt = $pdo->prepare($sql);
    $stmt -> bindParam(':useremail', $formemail);
    $stmt -> execute();
    $row = $stmt -> fetch();

    $useremail = $formemail;
    $usersecurityq1 = $row["usersecurityq1"];
    $usersecuritya1 = $row["usersecuritya1"];
    $usersecurityq2 = $row["usersecurityq2"];
    $usersecuritya2 = $row["usersecuritya2"];
  }
  
  if(isset($_POST['submit'])){
    $formemail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $formsecuritya1 = $_POST["securitya1"];
    $formsecuritya2 = $_POST["securitya2"];
    $password = $_POST["password"];

    include 'includes/library.php';
    $pdo = & connectDB();

    $sql = "SELECT usersecuritya1, usersecuritya2 FROM bucketUsers WHERE useremail = :useremail";
    $stmt = $pdo->prepare($sql);
    $stmt -> bindParam(':useremail', $formemail);
    $stmt -> execute();
    $row = $stmt -> fetch();

    if (password_verify($formsecuritya1, $row['usersecuritya1']) and password_verify($formsecuritya2, $row['usersecuritya2']) and $password != ""){
      $hashpass = password_hash($password, PASSWORD_DEFAULT);

      $sql = "UPDATE bucketUsers SET userpass = :userpass WHERE useremail = :useremail";
      $stmt = $pdo->prepare($sql);
      $stmt -> bindParam(":userpass", $hashpass);
      $stmt -> bindParam(":useremail", $formemail);
      $stmt -> execute();
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<?php
$PAGETITLE = "Reset Password";
include "includes/head.php";
?>
</head>
<body>
  <div class="login-img">
    <?php
    include "includes/header.php";
    ?>
  <div class="login-container">
  <div class="login-form">
    <form id="form-login" name="form-login" action="resetpassword.php" method="post">
      <h1>Reset Password</h1>
      <div class="form-group">
        <input type="email" name="email" placeholder="E-mail Address" value="<?php echo $useremail?>" required>
        <span class="input-icon"><i class="fa fa-envelope"></i></span>
      </div>

    <span id="securityq1"><?php echo $usersecurityq1?></span>
      <div class="form-group">
        <input type="text" name="securitya1" placeholder="Question 1 Answer">
      </div>
      <span id="securityq2"><?php echo $usersecurityq2?></span>
      <div class="form-group">
        <input type="text" name="securitya2" placeholder="Question 2 Answer" >
      </div>
      <span id="securityq2"></span>
      <div class="form-group">
        <input type="password" name="password" placeholder="New Password" >
        <span class="input-icon"><i class="fa fa-lock"></i></span>
      </div>
      <div class="btn-container">
      <div>
        <input class="login-btn" type="submit" name="submit" value="RESET" />
      </div>
    </div>
    </form>
  </div>
</div>
</div>
</body>
</html>
