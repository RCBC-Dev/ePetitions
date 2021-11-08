<?php session_start(); ?>
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
        <p class="lead">Signature Submitted</p>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4">
      </div>
      <div class="col-lg-4 justify-content-center text-center">
        <h2>A link has been emailed to you to validate your email address</h2>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-4">
      </div>
      <div class="col-lg-4 justify-content-center text-center">
        <h4>Your signature won't be counted until you validate your email address</h4>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 text-center">
        <p>Copyright 2021 Redcar and Cleveland Borough Council</p>
      </div>
    </div>
  </div>

<!-- Bootstrap core JavaScript -->
<?php include 'jsincludes-inc.php' ?>

<?php
if(isset($_SESSION['userid']))
{
    echo '<script>$("#nav2").replaceWith(\'<a class="nav-link" href="/epetition/newpetition.php">New Petition</a>\');</script>';
    echo '<script>$("#nav3").replaceWith(\'<a class="nav-link" href="/epetition/home.php">My ePetitions</a>\');</script>';
    echo '<script>$("#nav4").replaceWith(\'<a class="nav-link" href="/epetition/logout.php">Logout</a>\');</script>';
}
?>

</body>

</html>