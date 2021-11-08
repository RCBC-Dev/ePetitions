<?php
session_start();
require('epetition.php');
publicPage();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'header-inc.php' ?>
	<title>ePetitions - New User Registered</title>
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
        <p class="lead">Register for an account</p>

          <h3>Thanks for providing your details</h3>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-12 text-center">
        You can now login to create an ePetition<br><br>
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