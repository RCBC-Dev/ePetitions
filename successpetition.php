<?php 
session_start();
require('epetition.php');
userPage();
?>

<!--We'll only get here if we are logged in-->
<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'header-inc.php' ?>
	<title>ePetitions - ePetition Submitted</title>
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
        <p class="lead">ePetition Submitted</p>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4">
      </div>
      <div class="col-lg-4 justify-content-center text-center">
        <h2>Your petition has been successfully submitted</h2>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-4">
      </div>
      <div class="col-lg-4 justify-content-center text-center">
        <h4>Someone will check and then publish your petition if it meets our guidelines, you will receive an email when this happens</h4>
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
    echo '<script>$("#nav1").remove();</script>';
    echo '<script>$("#nav2").replaceWith(\'<a class="nav-link" href="/epetition/newpetition.php">New Petition</a>\');</script>';
    echo '<script>$("#nav3").replaceWith(\'<a class="nav-link" href="/epetition/home.php">My ePetitions</a>\');</script>';
    echo '<script>$("#nav4").replaceWith(\'<a class="nav-link" href="/epetition/logout.php">Logout</a>\');</script>';
?>

</body>

</html>