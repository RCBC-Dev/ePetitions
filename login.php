<?php 
session_start();
require('epetition.php');
publicPage();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'header-inc.php' ?>
	<title>ePetitions - Login</title>
</head>

<body>

  <!-- Navigation -->
  <?php include 'navigation-inc.php' ?>

  <!-- Page Content -->
  <div class="container">
  <?php include 'logo-inc.php' ?>
    <div class="row">
      <div class="col-lg-12 text-center">
        <h1 class="mt-5">ePetitions</h1>
        <p class="lead">Login to your account</p>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4">
      </div>
      <div class="col-lg-4 justify-content-center text-center">
        <form action="server.checkuser.php" method="POST">
          <label for="loginEmail" class="form-label">Email address</label>
          <input type="email" class="form-control" id="loginEmail" name="email" placeholder="email" aria-describedby="emailHelp" required>
          <div id="emailHelp" class="form-text"></div>
          <label for="loginPassword" class="form-label">Password</label>
          <input type="password" class="form-control" id="loginPassword" name="password" placeholder="password" aria-describedby="passwordHelp" autocomplete="off" required>
          <span id="loginhelp" class="text-danger"></span>
          <br>
          <br>
          <button type="submit" id="submit" name="login" class="btn btn-primary align-content-center">Log In</button>
        </form>
      </div>
    </div>
    <br>
    <?php include 'footer-inc.php' ?>
  </div>

<!-- Bootstrap core JavaScript -->
<?php include 'jsincludes-inc.php' ?>

<?php
if (isset($_GET['email'])&&isset($_GET['error'])){
    $email = htmlspecialchars($_GET['email']);
    $error = htmlspecialchars($_GET['error']);
    echo "<script>$('#loginhelp').text('$error');$('#loginEmail').val('$email');</script>";
}
?>
<?php echo '<script>$("#nav3").addClass("active");</script>';?>
</body>

</html>