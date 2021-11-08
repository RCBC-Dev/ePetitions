<?php 
session_start();
if(isset($_SESSION['userid']))
{
    die(header("Location: /epetition/home.php"));  //Send them back to login page.
}
if((!isset($_GET['id']))||(!isset($_GET['key'])))
{
  die(header("Location: /epetition/"));  //Should only be coming here with sigid and key set
}
$id=htmlspecialchars($_GET['id']); //Always use htmlspecialcharacters - especially with GET!
$key=htmlspecialchars($_GET['key']);

include 'dbconnect.php';
$sql="select email from eSignatures where sigid=? and activatekey=?;";
$stmt = sqlsrv_query($conn, $sql, array($id,$key));
if( $stmt === false ){die(header("Location: /epetition/error.php"));}
if( sqlsrv_fetch( $stmt ) === false){die(header("Location: /epetition/error.php"));}

$email = sqlsrv_get_field( $stmt, 0); 
if(!$email) // This key used already, or submitted key has been altered - just gonna redirect to home page
{           // I could give the user an error, but might be confusing - if they just clicked twice
    die(header("Location: /epetition/"));
}           // Also not giving potential hackers any information about userID's that exist
else
{
    $verifieddate=date("Y-m-d H:i:s");
    $sql = "UPDATE eSignatures SET verifieddate = ?, activatekey = '0', status=1 WHERE sigid = ?;";
    $stmt = sqlsrv_query($conn, $sql, array($verifieddate, $id));
    if( $stmt === false ){echo $sql;}
    header("Location: /epetition/successsignature.php");
}
?>
