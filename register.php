<?php 
session_start();
require('epetition.php');
publicPage();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'header-inc.php' ?>
  <title>ePetitions - Register</title>
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
        <h2 class="lead">Register for an account</h2>
          <h3>You only need an account if you want to create a petition</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4">
      </div>
      <div class="col-lg-4 justify-content-center text-center">
        <form action="server.register.php" method="POST">
        <label for="InputEmail1" class="form-label">Email address</label>
        <input type="email" class="form-control" id="InputEmail1" name="email" aria-describedby="emailHelp" required>
        <div id="emailHelp" class="form-text">We will never share your email with anyone else.</div>
        <br>
        <button type="submit" onclick="this.disabled=true;this.value='Sending, please wait...';this.form.submit();" id="submitbutton" class="btn btn-primary align-content-center">Register</button>
        </form>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-12 text-center">
        You will need to activate your account by clicking on the link we will send you.<br><br>
        This will allow you to create a new password, and give us your details<br>
        Your details won't be used for any other purpose.<br>
      </div>
    </div>
    <br>
  <?php include 'footer-inc.php' ?>
  </div>

<!-- Bootstrap core JavaScript -->
<?php include 'jsincludes-inc.php' ?>

<?php // Look for errors when we get to this page
    if(isset($_GET["e"]))
    {
        $error=htmlspecialchars($_GET["e"]);
        $email=htmlspecialchars($_GET["email"]);
        echo "<script>$('#emailHelp').addClass('text-danger'); $('#InputEmail1').val('$email');</script>"; // fill in the email 
        if($error==1)  // Contains Special Characters
        {
          echo "<script>$('#emailHelp').html('Your email address cannot contain special characters!');</script>"; 
        }
        if($error==2)  // Account exists but not been activated
        {
          echo "<script>$('#emailHelp').html('Email address is already registered - please activate your account by clicking on the link you have been emailed');</script>"; 
        }
        if($error==3)  // Contains Special Characters
        {
          echo "<script>$('#emailHelp').html('Email format not valid!');</script>"; 
        }
        if($error==4)  // Account exists and has been activated
        {
          echo "<script>$('#emailHelp').html('Email address registered - please login or reset your password');</script>"; 
        }
    }
?>
<?php echo '<script>$("#nav2").addClass("active");</script>';?>
</body>
</html>