<?php
session_start();
require('epetition.php');
publicPage();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'header-inc.php' ?>
	<title>ePetitions - Sign ePetition</title>
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
        <p class="lead">Sorry!</p>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4">
      </div>
      <div class="col-lg-4 justify-content-center text-center">
        <h2>You've already signed this petition</h2>
      </div>
    </div>
    <br>
    <?php include 'footer-inc.php' ?>
  </div>

<!-- Bootstrap core JavaScript -->
<?php include 'jsincludes-inc.php' ?>

</body>

</html>