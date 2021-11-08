<?php 
session_start();
require('epetition.php');
adminPage();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'header-inc.php' ?>
	<title>ePetitions - ePetition Signature Removed</title>
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
        <p class="lead">ePetition Signature</p>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4">
      </div>
      <div class="col-lg-4 justify-content-center text-center">
        <h2>Signature Removed</h2>
      </div>
    </div>
    <br>
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
  echo '<script>$("#nav2").replaceWith(\'<a class="nav-link" href="/epetition/admin.showall.php">Show All ePetitions</a>\');</script>';
  echo '<script>$("#nav3").replaceWith(\'<a class="nav-link" href="/epetition/admin.approvals.php">ePetitions Approvals</a>\');</script>';
  echo '<script>$("#nav4").replaceWith(\'<a class="nav-link" href="/epetition/logout.php">Logout</a>\');</script>';
?>

</body>

</html>