<?php
session_start();
if (!isset($_SESSION['petid']))  // We haven't got a petition ID
{
  die(header("Location: /epetition/"));  //Should only be here from the sign page
}
if($_SERVER["REQUEST_METHOD"] == "POST")
{
  $petitionid=$_SESSION['petid'];
  $fullname = htmlspecialchars($_POST['fullName'],ENT_QUOTES);
  $email = htmlspecialchars($_POST['email']);
  $address = htmlspecialchars($_POST['address'],ENT_QUOTES);
  $postcode = htmlspecialchars($_POST['postcode']);
  $connection = htmlspecialchars($_POST['connection']);
  
  // check that data is as we expect - hackers can manipulate the form data requirements
  if(!filter_var($email, FILTER_VALIDATE_EMAIL))
  {
      echo "Email Validate Failed";
      //die(header("Location: /epetition/error.php"));  // Return with error 3 (Not an email format)
  }
  if(strlen($address)<6 || strlen($postcode)<6 || strlen($connection) < 4 || strlen($fullname) < 6)
  {
      echo "Issue with address or postcode length";
      //die(header("Location: /epetition/error.php"));  // Data is not as expected (hack attempt)
  }
  if($petitionid<0)
  {
      echo "Petition ID less than 0";
      //die(header("Location: /epetition/error.php"));  // Data is not as expected (hack attempt)
  }
  
  include 'dbconnect.php';
  $signeddate=date("Y-m-d H:i:s");
  $sql = "SELECT email from eSignatures where petid=? and email=?;"; //Checking if already signed
  $stmt = sqlsrv_query($conn, $sql, array($petitionid, $email));
  if( $stmt === false )  
  {  
    echo "Error in statement preparation/execution.\n";  
    die( print_r( sqlsrv_errors(), true));  
  }
  while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
  {
    die(header("Location: /epetition/alreadysigned.php"));  //Should only be here from the sign page
  }
  function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) 
        {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
  $randomKey = generateRandomString(10).date('siHdmy').generateRandomString(10); // Create an empty key
  $sql = "INSERT INTO eSignatures (petid, name, email, address, postcode, connection, signeddate, activatekey, status) OUTPUT INSERTED.sigid VALUES (?,?,?,?,?,?,?,?,?);";
  $stmt = sqlsrv_query($conn, $sql, array($petitionid, $fullname, $email, $address, $postcode, $connection, $signeddate, $randomKey, 0));
  if( $stmt === false ){echo "Error in statement preparation/execution.\n";die( print_r( sqlsrv_errors(), true));}
  $row = sqlsrv_fetch_array($stmt);
  $sigid=$row['sigid'];
  $_SESSION['email']=$email;
  $_SESSION['title']="Redcar and Cleveland - ePetition Verify Signature";
  $_SESSION['content']="Please click <a href='https://shawn-dev.redclev.net/epetition/verifyemail.php?id=$sigid&key=$randomKey'>here</a>  to verify your email address.";
  $_SESSION['success']="/epetition/signed.php";  // Url to forward to if we send successfully
  header("Location: /epetition/server.sendemail.php"); // We set up required session variables and go to the sendemail page
}
else
{
    die(header("Location: /epetition/"));  //Should only be coming here with POST Request
}
?>