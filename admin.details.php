<?php 
session_start();
require('epetition.php');
adminPage();

if(!isset($_GET['id'])) // If there is no id in the request
{
  die(header("Location: /epetition/admin.approvals.php"));  //Send them back to main page as we need an ID to show the details of
}

$id=htmlspecialchars($_GET['id']);
$id=str_replace("e","",$id); //strip out e - because 1e3 is numeric (1e3 is 1000 btw)
if(!is_numeric($id)||$id!=floor($id))
{
  die(header("Location: /epetition/admin.approvals.php"));  //Send them back to main page.
}
// This shouldn't be required, as we are admin - but it's better safe than sorry!
?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'header-inc.php' ?>
	<title>ePetitions Details</title>
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
        <p class="lead">ePetition Details</p>
        <?php
          include 'dbconnect.php';
          $sql = "select distinct P.petid, P.title, P.detail, COUNT(S.sigid) as numsigs, P.petitionstatus, P.petitioncreated, P.petitiondisabled, E.userid,
		  E.name, E.email, E.address, E.postcode, E.phonenumber, E.mobilenumber, E.accountcreated, E.accountstatus, E.connection
		  from ePetition as P
          left join ePetLogin as E 
          ON P.userid = E.userid
          left join eSignatures as S
          ON P.petid = S.petid
          WHERE P.petid=? AND S.status=1
          GROUP BY P.petid, P.title, P.detail, P.petitionstatus, P.petitioncreated, P.petitiondisabled, E.userid, E.name, 
          E.email, E.address, E.postcode, E.phonenumber, E.mobilenumber, E.accountcreated, E.accountstatus, E.connection"; 
          //We grab the petition and user details using parameters
                    
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
            $name=$row['name'];
            $email=$row['email'];
            $address=$row['address'];
            $postcode=$row['postcode'];
            $phone=$row['phonenumber'];
            $mobile=$row['mobilenumber'];
            $connection=$row['connection'];
            $accountcreated=$row['accountcreated']->format('d/m/Y');
            $title=$row['title'];
            $detail=$row['detail'];
            $startdate=$row['petitioncreated']->format('d/m/Y');
            $enddate=$row['petitiondisabled']->format('d/m/Y');
            $numsigs=$row['numsigs'];
            $petitionstatus=$row['petitionstatus'];
            $accountstatus=$row['accountstatus'];
          }
          $_SESSION['petid']= $id;
          $_SESSION['petemail']=$email;
          $_SESSION['pettitle']=$title;
          $today = date('d/m/Y');
          $finished = $today>=$enddate;
          sqlsrv_close($conn);
          if($rows==0)
          {
            die(header("Location: /epetition/"));  //Send them back to main page.
          }
          echo $_SESSION['pettitle'];
          ?>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3">
      <?php echo"<h3>Petitioner Details</h3>"; ?>
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
        <?php echo "<h5>$phone</h5>"; ?>
      </div>
      <div class="col-lg-4">
        <?php echo "<h5>$mobile</h5>"; ?>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-4">
        <?php echo "<h5>Account Created: $accountcreated</h5>"; ?>
      </div>
      <div class="col-lg-4">
        <?php echo "<h5>Account Status: $accountstatus</h5>"; ?>
      </div>
      <div class="col-lg-4">
        <?php echo "<h5>$connection in Redcar and Cleveland</h5>"; ?>
      </div>      
    </div>
    <br>
    <div class="row">
      <div class="col-lg-3">
        <h3>Petition Details</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4">
        <?php echo "<h5>Start: $startdate</h5>"; ?>
      </div>
      <div class="col-lg-4">
        <?php echo "<h5>End: $enddate</h5>"; ?>
      </div>
      <div class="col-lg-4">
        <?php echo "<h5>Status: $petitionstatus</h5>"; ?>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-12">
      <?php echo "<h4>$title</h4>";?>
      </div>
    </div>    
    <div class="row">
      <div class="col-lg-12">
      <?php echo"<h5>$detail</h5>"; ?>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-12 text-center">
      <?php echo"<h5>$numsigs Signatures</h5>"; ?>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 text-center">
        <button type="button" id="seesignatures" class="btn btn-info align-content-center">See Signatures</button>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div id="signaturetable"></div>
      </div>
    </div>    
    <br>
    <div class="row">
      <div class="col-lg-12 justify-content-center text-center">
        <button type="button" id="approve" onclick="this.disabled=true;" class="btn btn-success align-content-center">Approve/Activate</button>
        <button type="button" id="decline" class="btn btn-danger align-content-center">Decline/Deactivate</button>
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
          <h2 class="modal-title" id="modBranchName">Reason for ePetition Decline</h2>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="declinepetition">
            <div class="form-group">
              <label for="category" class="col-form-label">Category</label>
              <select class="form-control" name="category" id="category" required>
                <!--We add more categories here-->
                <option value disabled selected>Please select a category</option>
                <option value='facts'>Factually Incorrect</option>
                <option value='offensive'>Offensive</option>
                <option value='language'>Use of Language</option>
                <option value='equality-diversity'>Equality/Diversity</option>
                <option value='planning'>Planning Issue</option>
                <option value='other'>Other</option>
              </select>
            </div>
            <div class="form-group">
              <label for="comments">Comments</label>
              <textarea class="form-control" id="comments" name="comments" rows="3" maxlength="255" required></textarea>
            </div>
          </form>                 
        </div>
        <div class="modal-footer">
          <button class="btn btn-info" type="button" data-dismiss="modal">Go Back</button>
          <button class="btn btn-danger" id="declinePetition" type="button" onclick="this.disabled=true;">Decline ePetition</button>
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
    echo '<script>$("#nav2").replaceWith(\'<a class="nav-link" href="/epetition/admin.showall.php">Show All ePetitions</a>\');</script>';
    echo '<script>$("#nav3").replaceWith(\'<a class="nav-link" href="/epetition/admin.approvals.php">ePetitions Approvals</a>\');</script>';
    echo '<script>$("#nav4").replaceWith(\'<a class="nav-link" href="/epetition/logout.php">Logout</a>\');</script>';
}
if($petitionstatus=='Approved')
{
    echo '<script>$("#approve").prop("disabled", true);</script>';
}
if($petitionstatus=='Declined')
{
    echo '<script>$("#decline").prop("disabled", true);</script>';
}
if($petitionstatus=='Archived' || $finished)
{
    echo '<script>$("#approve").prop("disabled", true);$("#decline").prop("disabled", true);</script>';
}
?>
</body>
</html>