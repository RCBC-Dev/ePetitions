<?php 
session_start();
require('epetition.php');
userPage();
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
        <p class="lead">Logged In</p>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 justify-content-center text-center">
        <h3>Your ePetitions</h3>
      <?php 
        include 'dbconnect.php';
        $userid=$_SESSION['userid'];
        $sql = "select 
        distinct P.petid, P.title, P.detail, COUNT(S.sigid) as numsigs, P.petitionstatus, P.petitioncreated, P.petitiondisabled from ePetition as P
        left join eSignatures as S 
        ON S.petid = P.petid
        WHERE userid = ?
        GROUP BY P.petid, P.title, P.detail, P.petitionstatus, P.petitioncreated, P.petitiondisabled"; 
        $stmt = sqlsrv_query($conn, $sql, array($userid));
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
            echo "<table class='table'><thead class='bg-primary'><tr><th scope='col'>Title</th><th class='d-none d-sm-table-cell'>Start Date</th><th class='d-none d-sm-table-cell'>End Date</th><th>Signatures</th><th class='d-none d-sm-table-cell'>Status</th></tr></thead>";
          }
          $somerows=1;
          $petitionstatus=$row['petitionstatus'];
          echo "<tr><td>".$row['title']."</td></a><td class='d-none d-sm-table-cell'>".$row['petitioncreated']->format('d/m/Y')."</td><td class='d-none d-sm-table-cell'>".$row['petitiondisabled']->format('d/m/Y')."</td><td>".$row['numsigs']."</td><td class='d-none d-sm-table-cell'>".$petitionstatus."</td></tr>";
        }
        echo "</table>";
        if($somerows==0){
            echo "You haven't created any petitions";
        }
        sqlsrv_close($conn); ?>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-4">
      </div>
      <div class="col-lg-4 justify-content-center text-center">
        <form action="newpetition.php" method="POST">
          <button type="submit" id="submit" class="btn btn-primary align-content-center">Create a new ePetition</button>
        </form>
      </div>
    </div>
    <br>
    <?php include 'footer-inc.php' ?>
  </div>

<!-- Bootstrap core JavaScript -->
<?php include 'jsincludes-inc.php' ?>

<?php
  echo '<script>$("#nav1").remove();</script>';
  echo '<script>$("#nav2").replaceWith(\'<a class="nav-link" href="/epetition/newpetition.php">New Petition</a>\');</script>';
  echo '<script>$("#nav3").replaceWith(\'<a class="nav-link active" href="/epetition/home.php">My ePetitions</a>\');</script>';
  echo '<script>$("#nav4").replaceWith(\'<a class="nav-link" href="/epetition/logout.php">Logout</a>\');</script>';
?>

</body>

</html>