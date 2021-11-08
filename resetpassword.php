<?php 
session_start();
require('epetition.php');
publicPage();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'header-inc.php' ?>
	<title>ePetitions - Reset your password</title>
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
        <p class="lead">Reset Your Password</p>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4">
      </div>
      <div class="col-lg-4 justify-content-center text-center">
        <form action="server.resetpassword.php" method="POST">
        <label for="resetEmail" class="form-label">Email address</label>
        <input type="email" class="form-control" id="resetEmail" name="email" aria-describedby="emailHelp" required>
        <br>
        <button type="submit" onclick="this.disabled=true;this.value='Sending, please wait...';this.form.submit();" id="submitbutton" class="btn btn-primary align-content-center">Reset Password</button>
        <br>
        <span id="resethelp" class="text-danger"></span>
        </form>
      </div>
    </div>
    <br>
       
    <div class="row">
      <div class="col-lg-12 text-center">
        <p>Copyright 2021 Redcar and Cleveland Borough Council</p>
      </div>
    </div>
  </div>

<!-- Bootstrap core JavaScript -->
<?php include 'jsincludes-inc.php' ?>

<?php
if (isset($_GET['email'])&&isset($_GET['error'])){
    $email = htmlspecialchars($_GET['email']);
    $error = htmlspecialchars($_GET['error']);
    echo "<script>$('#resethelp').text('$error');$('#resetEmail').val('$email');</script>";
}
?>

<?php echo '<script>$("#nav4").addClass("active");</script>';?>
</body>

</html>