<?php 
session_start();
if(isset($_SESSION['userid']))
{
    die(header("Location: /epetition/home.php"));  //Send them back to login page.
}
if((!isset($_GET['user']))||(!isset($_GET['key'])))
{
  die(header("Location: /epetition/"));  //Should only be coming here with user set
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'header-inc.php' ?>
	<title>ePetitions - New User</title>
</head>

<body>

  <!-- Navigation -->
  <?php include 'navigation-inc.php' ?>

  <?php
  $user=htmlspecialchars($_GET['user']); //Always use htmlspecialcharacters - especially with GET!
  $key=htmlspecialchars($_GET['key']);
  include 'dbconnect.php';
  $sql="select email from ePetLogin where userid=? and activatekey=?;";
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
    <form action="server.newuser.php" method="POST">
    <?php include 'logo-inc.php' ?>
    <div class="row">
      <div class="col-lg-12 text-center">
        <?php if(isset($error))
        {
        echo "<h1>Error - User or Key not matched</h1><br>";
        echo "<h2>This key may have already been used if you have already registered</h2>";
        echo "<p>If you have already registered, please login - or reset your password</p>";
        die();  // Don't continue
        }
        ?>
        <h1 class="mt-5">ePetitions</h1>
        <p class="lead">Your Details</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-4">
          <label for="fullname">Full Name: </label>
          <input type="text" class="form-control include" id="fullname" name="fullname" placeholder="Full Name (Min 4 characters)" pattern=".{4,50}" title="Please enter your Full Name, minimum 4 characters" required maxlength=50>
      </div>
      <div class="col-md-4">
        <label for="address">Address</label>
        <input type="text" class="form-control" id="address" name="address" placeholder="Address" pattern=".{4,50}" title="Please enter a valid Address" maxlength=50 required>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-4">
        <span id="nameadvice" class="text-danger"></span>
      </div>
      <div class="col-md-4">
        <span id="addressadvice" class="text-danger"></span>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-4">
        <label for="connection">Which of the following applies to you</label>
        <select class="custom-select" id="connection" name="connection" required>
          <option value="" disabled selected>Choose...</option>
          <option value="Lives">Live in Redcar and Cleveland</option> 
          <option value="Works">Work in Redcar and Cleveland</option>
          <option value="Studies">Study in Redcar and Cleveland</option>
          <option value="Uses Services">Use Services in Redcar and Cleveland</option>
        </select>
        <div class="invalid-feedback">
            You are required to answer this question
        </div>
      </div>
      <div class="col-md-4">
        <label for="postcode">Post Code</label>
		<!-- This regular expression will only allow valid postcode formats for all UK postcodes-->
        <input type="text" class="form-control" id="postcode" name="postcode" placeholder="Post Code" pattern="^(([gG][iI][rR] {0,}0[aA]{2})|((([a-pr-uwyzA-PR-UWYZ][a-hk-yA-HK-Y]?[0-9][0-9]?)|(([a-pr-uwyzA-PR-UWYZ][0-9][a-hjkstuwA-HJKSTUW])|([a-pr-uwyzA-PR-UWYZ][a-hk-yA-HK-Y][0-9][abehmnprv-yABEHMNPRV-Y]))) {0,}[0-9][abd-hjlnp-uw-zABD-HJLNP-UW-Z]{2}))$" title="Please enter a valid Postcode" maxlength=12 required>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-4">
        <span id="connectionadvice" class="text-danger"></span>
      </div>
      <div class="col-md-4">
        <span id="postcodeadvice" class="text-danger"></span>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-4">
          <label for="phonenumber">Phone Number:</label>
          <input type="text" name="phonenumber" class="form-control include" id="phonenumber" name="phonenumber" placeholder="Phone Number" pattern=".{0,12}" maxlength=12>
      </div>
      <div class="col-md-4">
          <label for="mobilenumber">Mobile Number:</label>
          <input type="text" name="mobilenumber" class="form-control include" id="mobilenumber" name="mobilenumber" placeholder="Mobile Number" pattern=".{0,12}" maxlength=12>
      </div>
    </div>
    <br>
   <div class="row">
      <div class="col-md-4">
      </div>
      <div class="col-md-4">
        <label for="password">Password (minimum password strength 3/4)</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" pattern=".{6,50}" maxlength=50 aria-describedby="passwordHelp" autocomplete="off" required>
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
      <div class="col-lg-12 text-center">
        You need to provide information that can identify you, and let us know that you live, work or study in the area.<br>
        If you create a petition and we cannot identify you - it will not be published, and your account will be disabled.<br><br>
        You will be able to login once you have created a password and provided your details<br>
        Your details won't be used for any other purpose<br>
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
          <button type="submit" id="createaccount" class="passwordcheck btn btn-primary align-content-center" disabled>Create my Account</button>
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
<script src="resources/js/zxcvbn.js"></script>

</body>

</html>