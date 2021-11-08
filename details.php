<?php 
session_start();
require('epetition.php');
publicPage();

if(!isset($_GET['id'])) // If there is no id in the request (this is mandatory for this page!)
{
  die(header("Location: /epetition/index.php"));  //Send them back to main page.
}
$id=htmlspecialchars($_GET['id']); //remove special characters
$id=str_replace("e","",$id); //strip out e - because 1e3 is numeric (1e3 is 1000 btw)
if(!is_numeric($id)||$id!=floor($id)) // checking that the id is a number
{
  die(header("Location: /epetition/index.php"));  //Send them back to main page.
}
// Just making sure we got a full integer (as 2.3 or 1e3 are numeric)
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
          $sql = "select 
          distinct P.petid, P.title, P.detail, COUNT(S.sigid) as numsigs, P.petitionstatus, P.petitioncreated, P.petitiondisabled from ePetition as P
          left join eSignatures as S 
          ON S.petid = P.petid
          WHERE P.petid=? AND S.status=1 AND (p.petitionstatus='Approved' OR p.petitionstatus='Archived')
          GROUP BY P.petid, P.title, P.detail, P.petitionstatus, P.petitioncreated, P.petitiondisabled"; 
          //We grab the title and details from DB using parameters
          
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
            $startdate=$row['petitioncreated'];
            $enddate=$row['petitiondisabled']->format("Y-m-d");
			$displaydate=$row['petitiondisabled']->format("d/m/Y");
            $sigcount=$row['numsigs'];
          }
          sqlsrv_close($conn);
          if($rows==0)
          {
			// Attempting to get details for ID that doesn't exist
            die(header("Location: /epetition/index.php"));  //Send them back to main page.
          }
		  // If we get here, we have a valid petitionID
		  $_SESSION['petid']= $id;
		  // Store the id in a session variable so we can use it on other pages
          echo "<h2>$title</h2>";?>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-12 text-center">
      <?php echo"<h3>$detail</h3>"; ?>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-12 justify-content-center text-center">
      <?php
        if($sigcount>1)
        {
          echo "<h4>$sigcount people have already signed this petition</h4>";
        }
        elseif($sigcount==1)
        {
          echo "<h4>One person has signed this petition</h4>";
        }
        else
        {
          echo "<h4>No-one has signed this petition yet</h4>";
        }
        ?>
      </div>
    </div>
    <br>
    <?php 
    $today=strtotime(date("Y-m-d H:i:s"));
    $checkdate=strtotime($enddate);
    $cansign=$checkdate-$today;
    
    if($cansign>0)
    {
    echo '
    <div class="row">
      <div class="col-lg-4 offset-lg-4 justify-content-center text-center">
        <form action="sign.php?id='.$id.'" method="POST">
          <button type="submit" id="sign" class="btn btn-primary align-content-center">I want to sign this ePetition</button>
        </form>
      </div>
    </div>';  
    }
    else
    {
    echo '
    <div class="row">
      <div class="col-lg-4 offset-lg-4 justify-content-center text-center">
        <span>You can no longer sign this petition as it expired on '.$displaydate.'</span>
      </div>
    </div>';
    }
    ?>
         
    <br>
    <?php include 'footer-inc.php' ?>
  </div>

<!-- Bootstrap core JavaScript -->
<?php include 'jsincludes-inc.php' ?>

</body>

</html>