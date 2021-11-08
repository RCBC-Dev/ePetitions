<?php 
session_start();
require('epetition.php');
adminPage();

if(!isset($_SESSION['petid']))
{
    die(header("Location: /epetition/")); 
}

$id=$_SESSION['petid'];

include 'dbconnect.php';
$sql = "select distinct ROW_NUMBER() OVER(ORDER BY S.sigid ASC) as rownum, S.sigid, S.name, S.address, S.postcode, S.email, S.connection, S.signeddate, S.status
from ePetition as P
left join eSignatures as S
ON P.petid = S.petid
WHERE P.petid=? AND S.status=1
GROUP BY S.name, S.address, S.email, S.postcode, S.connection, S.signeddate, S.status, S.sigid";
          
$stmt = sqlsrv_query($conn, $sql, array($id));
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
    echo "<table class='table'><thead class='thead-light'><tr><th scope='col'>Count</th><th>Name</th><th>Email</th><th>Address</th><th>Post Code</th><th>Connection</th><tr></thead>";
  }
  $somerows=1;
  echo "<tr><td>".$row['rownum']."</td><td><a href='/epetition/admin.signaturedetails.php?id=".$row['sigid']."'>".$row['name']."</a></td></a><td>".$row['email']."</td><td>".$row['address']."</td><td>".$row['postcode']."</td><td>".$row['connection']."</td></tr>";
  //echo $row['title']." ".$row['petitioncreated']->format('Y-m-d H:i:s')." ".$petitionstatus."<br>";
}
echo "</table>";
if($somerows==0)
{
    echo "There aren't any signatures to show!";
}
sqlsrv_close($conn); ?>
