<?php 
session_start();
if(isset($_SESSION['userid']))
{
	// This is a comment for the 404 page
    die(header("Location: /epetition/home.php"));  //Send them back to home page.
}
else
{
	die(header("Location: /epetition/"));  //Send them back to login page.
}
?>