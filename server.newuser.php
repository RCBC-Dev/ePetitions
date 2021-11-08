<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
{
  $userid = htmlspecialchars($_POST['userid']);
  $key = htmlspecialchars($_POST['key']);
  $fullname = htmlspecialchars($_POST['fullname'],ENT_QUOTES);
  $address = htmlspecialchars($_POST['address'],ENT_QUOTES);
  $connection = htmlspecialchars($_POST['connection']);
  $postcode = htmlspecialchars($_POST['postcode']);
  $phonenumber = htmlspecialchars($_POST['phonenumber'],ENT_QUOTES);
  $mobilenumber = htmlspecialchars($_POST['mobilenumber'],ENT_QUOTES);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  //Hashed password
  
  include 'dbconnect.php';
  $activateddate=date("Y-m-d H:i:s");
  $sql = "UPDATE ePetLogin SET name = ?, address = ?, postcode = ?, phonenumber=?, mobilenumber=?, 
  password = ?, accountactivated = ?, connection = ?, userlevel='1', accountstatus='Active', activatekey='NULL' WHERE userid = ? AND activatekey = ?;";
  
  $stmt = sqlsrv_query($conn, $sql, array($fullname,$address,$postcode,$phonenumber,$mobilenumber,$password,$activateddate,$connection,$userid,$key));
  if( $stmt === false )  
  {  
    echo "Error in statement preparation/execution.\n";  
    die( print_r( sqlsrv_errors(), true));  
  }
  $sql = "INSERT INTO ePetAudit (userid, action, actiondatetime) VALUES (?, 'Activated account', ?);";
  $stmt = sqlsrv_query($conn, $sql, array($userid,$activateddate));
  if( $stmt === false )  
    {  
      echo "Error in statement preparation/execution.\n";  
      die( print_r( sqlsrv_errors(), true));  
    }
  header("Location: /epetition/successregistered.php");
}
else
{
    die(header("Location: /epetition/"));  //Should only be coming here with POST Request
}
?>