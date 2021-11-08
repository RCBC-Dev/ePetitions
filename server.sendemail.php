<?php
session_start(); 
$htmlContent = "";
$emailTitle = $_SESSION['title'];
// Get Title from $_SESSION
$htmlAppend = $_SESSION['content'];
$emailTo = $_SESSION['email'];
$forwardurl = $_SESSION['success'];

//Update to our own logo on ePetitions.redcar-cleveland.gov.uk
$logoURL="https://selectivelicensing.redcar-cleveland.gov.uk/css/Logo.jpg";

$htmlContent = "<img src='$logoURL'><h1>$emailTitle</h1>$htmlAppend";

// Used to send to a specific mailbox   
$mailrecipient="shawn.carter@redcar-cleveland.gov.uk"; //Just while we are testing (but we could save all out emails to a mailbox)
   
//recipient
$to = $mailrecipient.','.$emailTo;

//sender
$from = 'epetitions@redcar-cleveland.gov.uk';
$fromName = 'Redcar and Cleveland ePetitions';

//email subject
$subject = $emailTitle;

//email body content
	
//header for sender info
$headers = "From: $fromName"." <".$from.">";

//boundary 
$semi_rand = md5(time()); 
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

//headers for attachment 
$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

//multipart boundary 
$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
"Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n"; 

//preparing attachment

$message .= "--{$mime_boundary}--";
$returnpath = "-f" . $from;

//send email
$mail = @mail($to, $subject, $message, $headers, $returnpath); 

//email sending status
if($mail)
{
	unset($_SESSION['title']);
	unset($_SESSION['content']);
	unset($_SESSION['email']);
	unset($_SESSION['success']);
	header("Location: $forwardurl");
}
else
{
	echo "Issue sending email!";
	//die(header("Location: /epetition/error.php"));
}