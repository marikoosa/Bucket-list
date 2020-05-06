<?php
  session_start();
  $formuser="";
  if (isset($_COOKIE['rememberme'])){
    $formuser = $_COOKIE['rememberme'];
  }

  if(isset($_POST['submit'])){
    $formuser = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $formpass = $_POST['password'];

    if (isset($_POST['rememberme'])){
      setcookie('rememberme', $formuser, time()+60+60+24+30);
    } else {
        unset($_COOKIE['rememberme']);
    }

    include 'includes/library.php';
    $pdo = & connectDB();

    $sql = "SELECT userpass, id FROM bucketUsers WHERE username= :username";
    $stmt = $pdo->prepare($sql);
    $stmt -> bindParam(':username', $formuser);
    $stmt -> execute();
    $row = $stmt -> fetch();

    if (password_verify($formpass, $row['userpass'])) {
      if (password_needs_rehash($row['userpass'], PASSWORD_DEFAULT, $options)) {
        $newHash = password_hash($formpass, PASSWORD_DEFAULT, $options);
      }
      //redirect to main page
      $_SESSION['user']=$formuser;
      $_SESSION['id']=$row['id'];
      header("Location:index.php");
    }
    else {
    $error=false;
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
$PAGETITLE = "Login";
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
    <form id="form-login" name="form-login" action="login.php" method="post">
      <h1>Login</h1>
      <div class="form-group">
        <input type="username" name="username" placeholder="Username" value="<?php echo $formuser ?>" required>
        <span class="input-icon"><i class="fa fa-user"></i></span>
      </div>
      <div class="form-group">
        <input type="password" name="password" placeholder="Password" required>
        <span class="input-icon"><i class="fa fa-lock"></i></span>
      </div>
      <div>
        <label for="rememberme" class="remember"> Remember Me </label>
        <input type="checkbox" name="rememberme" id="rememberme" checked="checked" value="Y" />
      </div>
      <div class="btn-container">
      <div>
        <input class="login-btn" type="submit" name="submit" value="LOGIN" />
      </div>
    </div>
      <a class="reset-psw" href="register.php">Need an account?</a>
      <a class="reset-psw" href="resetpassword.php">Forgot your password?</a>
    </form>
  </div>
</div>
</div>
</body>
</html>
