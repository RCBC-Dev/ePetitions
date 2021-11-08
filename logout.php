<?php 
session_start();
if(isset($_SESSION['userid']))
{
  $userid=$_SESSION['userid'];
  $actiondate=date("Y-m-d H:i:s");
  include 'dbconnect.php';
  $sql = "INSERT INTO ePetAudit (userid, action, actiondatetime) VALUES (?, 'Logged out successfully', ?);";
  $stmt = sqlsrv_query($conn, $sql, array($userid,$actiondate));
  if( $stmt === false )  
  {  
     echo "Error in statement preparation/execution.\n";  
     die( print_r( sqlsrv_errors(), true));  
  }     
  session_destroy();   
}
else
{
  session_destroy(); 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'header-inc.php' ?>
	<title>ePetitions - Login</title>
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
        <p class="lead">You have been logged out</p>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4">
      </div>
      <div class="col-lg-4 justify-content-center text-center">
        <h3>Thanks for using the ePetitions system</h3>
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