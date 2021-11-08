<?php 
session_start();
if(isset($_SESSION['userid']))
{
    die(header("Location: /epetition/home.php"));  //Send them back to home page.
}
else
{
	die(header("Location: /epetition/"));  //Send them back to login page.
}
?>