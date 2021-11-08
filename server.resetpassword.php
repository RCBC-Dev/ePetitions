<?php
session_start(); 
if($_SERVER["REQUEST_METHOD"] == "POST")  // Check that we have got a POST request
{
  if(isset($_POST["email"]))  // and the email has been received from the form
  {           
    $email = htmlspecialchars($_POST["email"],ENT_QUOTES);
    $emailraw = $_POST["email"];
    
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        die(header("Location: /epetition/resetpassword.php?email=$email&error=Please use a valid email address"));  
        // Return with error 3 (Not an email format)
    }
    
    if ($emailraw !== $email) 
    {
        die(header("Location: /epetition/resetpassword.php?email=$email&error=Please use a valid email address"));  
        // Return with error 1 (special characters)
    }
    
    // We are happy we have a valid email address
    include 'dbconnect.php';

    $sql = "select userID, accountstatus from ePetLogin where email=?;"; 
    // Checking if this user already in the ePetLogin table
    $stmt = sqlsrv_query($conn, $sql, array($email));

    if( $stmt === false ){die(header("Location: /epetition/error.php"));}
    if( sqlsrv_fetch( $stmt ) === false){die(header("Location: /epetition/error.php"));}
    
    $userid = sqlsrv_get_field( $stmt, 0); 
    $accountstatus = sqlsrv_get_field( $stmt, 1); 
 
    if(!$userid)
    {
        die(header("Location: /epetition/resetpassword.php?email=$email&error=This account has not been registered with ePetitions"));
    }
    
    if($accountstatus=='Not Activated')
    {
      die(header("Location: /epetition/resetpassword.php?email=$email&error=This user account has not been verified, please activate your account via the link you have been emailed"));  // User already exists not activated
    }
    elseif($accountstatus=='Active')
    {
      echo "We'll send user an email";
    }
    else
    {
        die(header("Location: /epetition/resetpassword.php?email=$email&error=This user does not exist, please register a new account"));
    }
    
    // User account exists and is active
    
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
  
    $randomKey = generateRandomString(10).date('siHdmy').generateRandomString(10); // 
    
    $sql = "UPDATE ePetLogin SET resetkey = ? WHERE userid = ?;";
    $stmt = sqlsrv_query($conn, $sql, array($randomKey,$userid));
    if( $stmt === false )  
    {  
        echo "Error in statement preparation/execution.\n";  
        die( print_r( sqlsrv_errors(), true));  
    }
    
    $actiondate=date("Y-m-d H:i:s");
    
    $sql = "INSERT INTO ePetAudit (userid, action, actiondatetime) VALUES (?, 'Reset Password Request', ?);";
    $stmt = sqlsrv_query($conn, $sql, array($userid,$actiondate));
    if( $stmt === false ){die(header("Location: /epetition/error.php"));}
    
    // Prepare to sent to email.php
    $_SESSION['email']=$email;
    $_SESSION['title']="Redcar and Cleveland ePetitions - Password Reset";
    $_SESSION['content']="Here is a link to reset your password, if this was not requested by you - then you can ignore this email.<br><a href='https://shawn-dev.redclev.net/epetition/reset.php?user=$userid&key=$randomKey'>Please click this link to reset your password. </a>";
    $_SESSION['success']="/epetition/successresetsent.php";  // Url to forward to if we send successfully
    header("Location: /epetition/server.sendemail.php"); // We set up required session variables and go to the sendemail page
  }
}
else
{
    die(header("Location: /epetition/"));  //We can only access this page with a post request
}
?>