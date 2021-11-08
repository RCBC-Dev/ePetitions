<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'header-inc.php' ?>
	<title>ePetitions - Password Reset</title>
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
        <p class="lead">ePetition - Password Reset</p>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4">
      </div>
      <div class="col-lg-4 justify-content-center text-center">
        <h2>Your password was successfully reset</h2>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-3">
      </div>
      <div class="col-lg-6 ustify-content-center text-center">
        <h3>You can now login</h3>
      </div>
      <div class="col-lg-3 justify-content-center text-center">
        <h4></h4>
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

</body>

</html>