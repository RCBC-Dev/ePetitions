<?php 
session_start();
require('epetition.php');
adminPage();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'header-inc.php' ?>
	<title>ePetitions - Approvals</title>
</head>

<body>
  <!-- Navigation -->
  <?php include 'navigation-inc.php' ?>
  <!-- Page Content -->
  <div class="container">
    <?php include 'logo-inc.php' ?>
    <div class="row">
      <div class="col-lg-12 text-center">
        <h1 class="mt-5">ePetitions Admin</h1>
        <p class="lead">Logged In</p>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 justify-content-center text-center">
        <h3>ePetitions Approvals</h3>
      <?php 
        include 'dbconnect.php';
        $userid=$_SESSION['userid'];
        $sql = "select 
        distinct P.petid, P.title, P.detail, COUNT(S.sigid) as numsigs, P.petitionstatus, P.petitioncreated, P.petitiondisabled from ePetition as P
        left join eSignatures as S 
        ON S.petid = P.petid
        WHERE P.petitionstatus ='Not Approved'
        GROUP BY P.petid, P.title, P.detail, P.petitionstatus, P.petitioncreated, P.petitiondisabled"; 
        $stmt = sqlsrv_query($conn, $sql);
        if( $stmt === false )  
        {  
          echo "Error in statement preparation/execution.\n";  
          die( print_r( sqlsrv_errors(), true));  
        }  
        $somerows=0;
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
        {
          if($somerows==0)
          {
            echo "<table class='table'><thead class='bg-primary text-white'><tr><th scope='col'>Title</th><th>Start Date</th><th>End Date</th><th>Approved</th></tr></thead>";
          }
          $petitionstatus=$row['petitionstatus'];
          $somerows=1;
          echo "<tr><td><a href='/epetition/admin.details.php?id=".$row['petid']."'>".$row['title']."</a></td></a><td>".$row['petitioncreated']->format('d/m/Y')."</td><td>".$row['petitiondisabled']->format('d/m/Y')."</td><td>".$petitionstatus."</td></tr>";
        }
        echo "</table>";
        if($somerows==0)
        {
            echo "There aren't any petitions to approve";
        }
        sqlsrv_close($conn); ?>
      </div>
    </div>
    <br>

    <br>
    <?php include 'footer-inc.php' ?>
  </div>

<!-- Bootstrap core JavaScript -->
<?php include 'jsincludes-inc.php' ?>

<?php
   echo '<script>$("#nav1").remove();</script>';
   echo '<script>$("#nav2").replaceWith(\'<a class="nav-link" href="/epetition/admin.showall.php">Show All ePetitions</a>\');</script>';
   echo '<script>$("#nav3").replaceWith(\'<a class="nav-link active" href="/epetition/admin.approvals.php">ePetitions Approvals</a>\');</script>';
   echo '<script>$("#nav4").replaceWith(\'<a class="nav-link" href="/epetition/logout.php">Logout</a>\');</script>';
?>

</body>

</html>