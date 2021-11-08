<?php
session_start();
require('epetition.php');
userPage();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'header-inc.php' ?>
	<title>ePetitions - New ePetition</title>
</head>

<body>
  <!-- Navigation -->
  <?php include 'navigation-inc.php' ?>
  <!-- Page Content -->
  <div class="container">
    <form action="server.submitpetition.php" method="POST">
    <?php include 'logo-inc.php' ?>
    <div class="row">
      <div class="col-lg-12 text-center">
        <h1 class="mt-5">ePetitions</h1>
        <p class="lead">New ePetition</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-8">
        <label for="title">Petition Title</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Write a sentence that describes what action you would like the council to take (100 characters)" pattern=".{0,100}" maxlength=100 required>

      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-8">
        <label for="details">Petition Details</label>
        <textarea class="form-control" id="title" name="detail" rows="4" placeholder="More details about your petition (please do not type this in all capitals) - 1000 characters maximum" pattern=".{0,1000}" maxlength=1000 required></textarea>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-5">
        <label for="duration">How long would you like the ePetition to run for?</label>
        <select class="custom-select" id="duration" name="duration" required>
          <option value="" disabled selected>Choose...</option>
          <option value="31">1 month</option> 
          <option value="61">2 months</option>
          <option value="92">3 months</option>
          <option value="183">6 months</option>
        </select>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-12 text-center">
        Please make sure that your ePetition does not contain inappropriate language<br><br>
        You will be sent an email when your petition is approved for publishing<br>
        Your details won't be used for any other purpose<br>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-4">
      </div>
      <div class="col-lg-4 justify-content-center text-center">
        
          <button type="submit" id="submitpetition" class="btn btn-primary align-content-center">Submit this ePetition</button>
        
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-12 text-center">
        <p>Copyright 2021 Redcar and Cleveland Borough Council</p>
      </div>
    </div>
    </form>
  </div>

<!-- Bootstrap core JavaScript -->
<?php include 'jsincludes-inc.php' ?>

<?php
userNav();
?>

</body>
</html>