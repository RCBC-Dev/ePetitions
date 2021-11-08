<?php 
session_start();
require('epetition.php');
publicPage();

if(!isset($_GET['id'])) // If there is no id in the request
{
    die(header("Location: /epetition/index.php"));  //Send them back to main page.
}

$id=htmlspecialchars($_GET['id']); //Use htmlspecialchars to strip out any attempt to inject SQL
$id=str_replace("e","",$id); //strip out e - because 1e3 is numeric (1e3 is 1000 btw)
if(!is_numeric($id)||$id!=floor($id)) 
{
  die(header("Location: /epetition/index.php"));  //Send them back to main page - as they are attempting to use wrong id
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'header-inc.php' ?>
	<title>ePetitions - Sign this ePetition</title>
</head>

<body>

  <!-- Navigation -->
  <?php include 'navigation-inc.php' ?>

  <!-- Page Content -->
  <div class="container">
    <?php include 'logo-inc.php' ?>
    <form action="server.sign.php" method="POST">    
    <div class="row">
      <div class="col-lg-12 text-center">
        <h1 class="mt-5">ePetitions</h1>
        <p class="lead">Sign this ePetition</p>
        <?php include 'dbconnect.php';
        $sql = "select 
          petid, title, detail, petitionstatus, petitiondisabled from ePetition
          WHERE petid=? and petitionstatus='Approved' and petitiondisabled > GETDATE()
          "; //We grab the title and details from DB
          $stmt = sqlsrv_query($conn, $sql, array($id));
          if( $stmt === false )  
          {  
            echo "Error in statement preparation/execution.\n";  
            die( print_r( sqlsrv_errors(), true));  
          }
          $rows=0;
          while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
          {
            $rows=1;
            $title=$row['title'];
            $detail=$row['detail'];
            $enddate=$row['petitiondisabled']->format("Y-m-d H:i:s");
          }
          $_SESSION['petid']= $id;
          sqlsrv_close($conn);
          if($rows==0) // ePetition doesn't exist
          {
            die(header("Location: /epetition/index.php"));  //Send them back to main page.
          }
          echo "<h2>$title</h2>";?>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 text-center">
        <?php echo"<h3>$detail</h3>"; ?>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-4">
          <label for="textFullname">Full Name</label>
          <input type="text" name="fullName" class="form-control" id="textFullname" placeholder="Full Name" pattern=".{4,50}" title="Please enter your Full Name" required maxlength=50>
      </div>
      <div class="col-md-4">
          <label for="textFullname">Email</label>
          <input type="email" name="email" class="form-control" id="textFullname" placeholder="Email Address" pattern=".{6,50}" title="Please enter your Email Address (minimum 6 characters)" required maxlength=50>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-4">
        <label for="Address">Address</label>
        <input type="text" class="form-control" id="address" name="address" placeholder="Address" pattern=".{6,50}" title="Please enter a valid Address" maxlength=50 required>
      </div>
      <div class="col-md-4">
        <label for="PostCode">Post Code</label>
		<!-- This regular expression will only allow valid postcode formats for all UK postcodes-->
		<input type="text" class="form-control" id="postcode" name="postcode" placeholder="Post Code" pattern="^(([gG][iI][rR] {0,}0[aA]{2})|((([a-pr-uwyzA-PR-UWYZ][a-hk-yA-HK-Y]?[0-9][0-9]?)|(([a-pr-uwyzA-PR-UWYZ][0-9][a-hjkstuwA-HJKSTUW])|([a-pr-uwyzA-PR-UWYZ][a-hk-yA-HK-Y][0-9][abehmnprv-yABEHMNPRV-Y]))) {0,}[0-9][abd-hjlnp-uw-zABD-HJLNP-UW-Z]{2}))$" title="Please enter a valid Postcode" maxlength=12 required>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-5">
        <label for="connection">Please select which of the following applies to you</label>
        <select class="custom-select" id="connection" name="connection" required>
          <option value="" disabled selected>Choose...</option>
          <option value="Lives">Live in Redcar and Cleveland</option> 
          <option value="Works">Work in Redcar and Cleveland</option>
          <option value="Studies">Study in Redcar and Cleveland</option>
          <option value="Uses Services">Use Services in Redcar and Cleveland</option>
        </select>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-12 text-center">
        You need to provide information that can identify you, and let us know that you live, work or study in the area.<br><br>
        You will be sent an email to verify your email address is valid<br>
		<strong>If you don't verify your email address - your signature will not be counted</strong><br>
        Your details won't be used for any other purpose<br>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-4">
      </div>
      <div class="col-lg-4 justify-content-center text-center">
          <button type="submit" id="signsubmit" class="btn btn-primary align-content-center">Sign this ePetition</button>
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
if(isset($_SESSION['userid']))
{
    echo '<script>$("#nav2").replaceWith(\'<a class="nav-link" href="/epetition/newpetition.php">New Petition</a>\');</script>';
    echo '<script>$("#nav3").replaceWith(\'<a class="nav-link" href="/epetition/home.php">My ePetitions</a>\');</script>';
    echo '<script>$("#nav4").replaceWith(\'<a class="nav-link" href="/epetition/logout.php">Logout</a>\');</script>';
}
?>
</body>
</html>