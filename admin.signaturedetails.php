<?php 
session_start();
require('epetition.php');
adminPage();

if(!isset($_GET['id'])) // If there is no id in the request
{
  die(header("Location: /epetition/"));  //Send them back to main page.
}

$id=htmlspecialchars($_GET['id']);
$id=str_replace("e","",$id); //strip out e - because 1e3 is numeric (1e3 is 1000 btw)
if(!is_numeric($id)||$id!=floor($id))
{
  die(header("Location: /epetition/"));  //Send them back to main page.
}
$_SESSION['sigid']=$id; // We need a Global session ID for this, as we are going to another page.
// Just making sure we got a full integer (as 2.3 or 1e3 are numeric)
?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'header-inc.php' ?>
	<title>Signature Details</title>
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
        <p class="lead">Signature Details</p>
        <?php
          include 'dbconnect.php';
          $sql = "SELECT distinct petid, name, email, address, postcode, connection, signeddate, verifieddate, status
          FROM eSignatures
          where sigid=$id"; //We grab the signature details
                    
          $stmt = sqlsrv_query($conn, $sql);
          if( $stmt === false )  
          {  
            echo "Error in statement preparation/execution.\n";  
            die( print_r( sqlsrv_errors(), true));  
          }
          $rows=0;
          while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
          {
            $rows=1;
            $petid=$row['petid'];
            $name=$row['name'];
            $email=$row['email'];
            $address=$row['address'];
            $postcode=$row['postcode'];
            $connection=$row['connection'];
            $signeddate=$row['signeddate']->format('d/m/Y H:i:s');
            if(isset($row['verifieddate']))
            {
              $verifieddate=$row['verifieddate']->format('d/m/Y H:i:s');
            }
            else
            {
              $verifieddate="Not Verified";  
            }
            if($signeddate===$verifieddate)
            {
				// User is originator of the ePetition - we should stop them being able to delete
				$originator=true;
            }
			if($name=='Anonymised' && $email==$name)
			{
				// ePetition is archived, can't delete Anonymised signatures
				$anonymised=true;
			}
          }
          sqlsrv_close($conn);
          if($rows==0)
          {
            die(header("Location: /epetition/"));  //Send them back to main page.
          }
          ?>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3">
      <?php echo"<h3>Signature Details</h3>"; ?>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4">
        <?php echo "<h5>$name</h5>"; ?>
      </div>
      <div class="col-lg-4">
        <?php echo "<h5>$address</h5>"; ?>
      </div>
      <div class="col-lg-4">
        <?php echo "<h5>$postcode</h5>"; ?>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-4">
        <?php echo "<h5>$email</h5>"; ?>
      </div>
      <div class="col-lg-4">
        <?php echo "<h5>Signed: $signeddate</h5>"; ?>
      </div>
      <div class="col-lg-4">
        <?php echo "<h5>Verified: $verifieddate</h5>"; ?>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-4">
        <?php echo "<h5>$connection in Redcar and Cleveland</h5>"; ?>
      </div>      
    </div>
    <br>
    <div class="row">
      <div class="col-lg-12 justify-content-center text-center">
        <button type="button" id="decline" class="btn btn-danger align-content-center">Remove This Signature</button>
      </div>
    </div>
    <br>
    <br>
    <?php include 'footer-inc.php' ?>
  </div>

  <!-- Details Modal-->
  <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title" id="modBranchName">Are you sure?  This can't be reversed.</h2>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="removesignature">
            <div class="form-group">
              <label for="reason" class="col-form-label">Reason</label>
              <select class="form-control" name="reason" id="reason" required>
                <!--We add more categories here-->
                <option value disabled selected>Please select a reason for removal</option>
                <option value='false'>Appears False</option>
                <option value='offensive'>Offensive</option>
                <option value='duplicate'>Attempt to sign multiple times</option>
                <option value='other'>Other</option>
              </select>
            </div>
          </form>                 
        </div>
        <div class="modal-footer">
          <button class="btn btn-info" type="button" data-dismiss="modal">Go Back</button>
          <button class="btn btn-danger" id="removeSignature" type="button">Confirm Removal</button>
        </div>
      </div>
    </div>
  </div>

<!-- Bootstrap core JavaScript -->
<?php include 'jsincludes-inc.php' ?>

<?php
if(isset($_SESSION['userid']))
{
    echo '<script>$("#nav1").remove();</script>';
    echo '<script>$("#nav2").replaceWith(\'<a class="nav-link" href="/epetition/showall.php">Show All ePetitions</a>\');</script>';
    echo '<script>$("#nav3").replaceWith(\'<a class="nav-link" href="/epetition/approvals.php">ePetitions Approvals</a>\');</script>';
    echo '<script>$("#nav4").replaceWith(\'<a class="nav-link" href="/epetition/logout.php">Logout</a>\');</script>';
}
if(isset($originator)||isset($anonymised))
{
    echo '<script>$("#decline").prop("disabled", true);</script>';
}
?>

</body>

</html>