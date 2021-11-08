<?php
session_start();
require('epetition.php');
publicPage();

if(isset($_GET['search'])) // If we are using this page to search - get the search term
{
  $search=preg_replace('/[^A-Za-z0-9\-]/', '', $_GET['search']); // remove anything other than AZ-az-09
}

if(isset($_GET['inactive'])) // If we are looking for inactive ePetitions
{
  $inactive=true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include 'header-inc.php' ?>
	<title>ePetitions - Existing Petitions</title>
</head>

<body>
  <!-- Navigation -->
  <?php include 'navigation-inc.php' ?>
  <!-- Page Content -->
  <div class="container">
  <?php include 'logo-inc.php' ?>
    <div class="row">
      <div class="col-lg-12 justify-content-center text-center table-responsive">
      <?php
        include 'dbconnect.php';
        $today=date("Y-m-d H:i:s");
        if(isset($search))
        {
          echo "<h3>Search Results</h3>";
          $sql = "select 
          P.petid, P.title, P.detail, Count(S.status) as numsigs,
          P.petitionstatus, P.petitioncreated, P.petitiondisabled from ePetition as P
          left join eSignatures as S 
          ON S.petid = P.petid 
          WHERE (petitionstatus='Approved' OR petitionstatus='Archived') AND disabledreason is NULL AND S.status=1 AND P.title like ?
          GROUP BY S.Status, P.petid,P.title, P.detail, P.petitionstatus, P.petitioncreated, P.petitiondisabled
		  ORDER BY petitioncreated DESC;";
          $stmt = sqlsrv_query($conn, $sql, array('%'.$search.'%'));       
        }
        elseif (isset($inactive))
        {
          echo "<h3>Inactive ePetitions</h3>";
          $sql = "select 
          P.petid, P.title, P.detail, Count(S.status) as numsigs,
          P.petitionstatus, P.petitioncreated, P.petitiondisabled from ePetition as P
          left join eSignatures as S 
          ON S.petid = P.petid 
          WHERE (petitionstatus='Approved' OR petitionstatus='Archived') AND disabledreason is NULL AND S.status=1 AND P.petitiondisabled < '$today'
          GROUP BY S.Status, P.petid,P.title, P.detail, P.petitionstatus, P.petitioncreated, P.petitiondisabled
		  ORDER BY petitioncreated DESC;";
          $stmt = sqlsrv_query($conn, $sql);          
        }
        else
        {
          echo "<h3>Active ePetitions</h3>";
          $sql = "select 
          P.petid, P.title, P.detail, Count(S.status) as numsigs,
          P.petitionstatus, P.petitioncreated, P.petitiondisabled from ePetition as P
          left join eSignatures as S 
          ON S.petid = P.petid 
          WHERE petitionstatus='Approved' AND S.status=1 AND P.petitiondisabled >= GETDATE()
          GROUP BY S.Status, P.petid,P.title, P.detail, P.petitionstatus, P.petitioncreated, P.petitiondisabled
		  ORDER BY petitioncreated DESC;";
          $stmt = sqlsrv_query($conn, $sql);          
        }

        if( $stmt === false )  
        {  
          echo "Error in statement preparation/execution.\n";  
          die( print_r( sqlsrv_errors(), true));  
        }  
        $somerows=0;
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) 
        {
          if($somerows==0) // So we only do this on the first run through the loop
          {
            echo "<table class='table'><thead class='bg-primary text-white'><tr><th scope='col'>Title</th><th class='d-none d-sm-table-cell'>Start Date</th><th class='d-none d-sm-table-cell'>End Date</th><th>Signature Count</th></tr></thead>";
          }
          $somerows=1;
          $petid=$row['petid'];
          echo "<tr><td><a href='/epetition/details.php?id=".$row['petid']."'>".$row['title']."</td></a><td class='d-none d-sm-table-cell'>".$row['petitioncreated']->format('d/m/Y')."</td><td class='d-none d-sm-table-cell'>".$row['petitiondisabled']->format('d/m/Y')."</td><td>".$row['numsigs']."</td></tr>";
        }
        echo "</table>";
        if($somerows==0){
            if(isset($search))
            {
              echo "No matching results found";
            }
            elseif (isset($inactive))
            {
              echo "There are no inactive petitions at the moment";
            }
            else
            {
              echo "There are no active petitions at the moment";
            }
            
        }
        sqlsrv_close($conn); 
        ?>
      </div>
    </div>
    <?php if(isset($inactive))
    { echo '
      <div class="row">
          <ul class="pager">
            <li><a href="/epetition/">Active ePetitions</a></li>
          </ul>
      </div>';  
    }
    else
    { echo '
      <div class="row">
        <ul class="pager">
          <li><a href="/epetition/?inactive=true">Inactive ePetitions</a></li>
        </ul>
      </div>';  
    }
    ?>

    <div class="row">
      <div class="col-md-3">
      </div>
      <div class="col-md-6 col-md-offset-3">
        <div class="input-group">
          <input type="search" class="form-control rounded" id="searchtext" placeholder="Search for a petition" aria-label="Search"
            aria-describedby="search-addon" />
          <button type="button" id="search" class="btn btn-outline-primary">search</button>
        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-lg-12 text-center">
        If you want to create a new ePetition you will need to <a href="/epetition/register.php">Register</a> on this site.<br><br>
        You can sign an ePetition if you live, work or study in the Redcar and Cleveland area.<br>
        To sign a petition you will need to provide a valid email address, and validate your email (by clicking on the link we will send you)<br>
        Only signatures that have been validated will be counted in the petition total.<br>
      </div>
    </div>
    <br>
    <?php include 'footer-inc.php' ?>
  </div>

<!-- Bootstrap core JavaScript -->
<?php include 'jsincludes-inc.php' ?>

<?php
  echo '<script>$("#nav1").addClass("active");</script>';
?>

</body>
</html>