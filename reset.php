<?php 
session_start();
require('epetition.php');
publicPage();

if((!isset($_GET['user']))||(!isset($_GET['key'])))
{
  die(header("Location: /epetition/"));  //Should only be coming here with user and key set
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'header-inc.php' ?>
	<title>ePetitions - Reset Password</title>
</head>

<body>

  <!-- Navigation -->
  <?php include 'navigation-inc.php' ?>

  <?php
  $user=htmlspecialchars($_GET['user']); //Always use htmlspecialcharacters - especially with GET!
  $key=htmlspecialchars($_GET['key']);
  include 'dbconnect.php';
  $sql="select email from ePetLogin where userid=? and resetkey=?;";
  $stmt = sqlsrv_query($conn, $sql, array($user,$key));
    if( $stmt === false )  
    {  
      echo "Error in statement preparation/execution.\n";  
      die( print_r( sqlsrv_errors(), true));  
    }
    if( sqlsrv_fetch( $stmt ) === false)  
    {  
      echo "Error in retrieving row.\n";  
      die( print_r( sqlsrv_errors(), true));  
    }
    $email = sqlsrv_get_field( $stmt, 0); 
    
    if(!$email)
    {
        $error="No match";
    }
  ?>

  <!-- Page Content -->
  <div class="container">
    <?php include 'logo-inc.php' ?>
    <div class="row">
      <div class="col-lg-12 text-center">
        <?php if(isset($error))
        {
        echo "<h1>Error - User or Key not matched</h1><br>";
        echo "<h2>This user doesn't exist, or the key is incorrect or old</h2>";
        echo "<p>Please reset your password to get a new link</p>";
        die();  // Don't continue
        }
        ?>
        <h1 class="mt-5">ePetitions</h1>
        <p class="lead">Your Details</p>
      </div>
    </div>
    <form action="server.changepassword.php" method="POST">
    <div class="row">
      <div class="col-md-4">
      </div>
      <div class="col-md-4">
        <label for="password">Password (min strength 3/4)</label>
        <input type="password" class="password form-control" id="password" name="password" placeholder="Password" pattern=".{6,50}" maxlength=50 autocomplete="off" required>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
      </div>
      <div class="col-md-4 text-center">
        <span id="passwordwarning" class="text-danger"></span>
      </div>
    </div>
    <br>
    <div class="row">
    <div class="col-md-4">
      
    </div>
    <div class="col-md-4 text-center">
      <span id="passwordstrength" >Password Strength: 0/4</span>
    </div>
    </div>
    <div class="row">
      <div class="col-lg-12 text-center" id="passwordadvice">
        We use a library to check if your password is secure<br>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-4">
        <input type="text" class="form-control" id="userid" name="userid" value="<?php echo $user; ?>" hidden>
      </div>
      <div class="col-md-4">
        <input type="key" class="form-control" id="key" name="key" value="<?php echo $key; ?>" hidden>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4">
      </div>
      <div class="col-lg-4 justify-content-center text-center">
          <button type="submit" onclick="this.disabled=true;this.value='Sending, please wait...';this.form.submit();" id="sendpassword" class="passwordcheck btn btn-primary align-content-center" disabled>Change my Password</button>
      </div>
    </div>
    <br>
    <?php include 'footer-inc.php' ?>
    </form>
  </div>

<!-- Bootstrap core JavaScript -->
<?php include 'jsincludes-inc.php' ?>

<script src="resources/js/zxcvbn.js"></script>

</body>

</html>