<?php
session_start();
	include 'dbconnect.php';
	$startdate=date("Y-m-d", strtotime("0 day"));
	$displaystart=date("d/m/Y", strtotime("0 day"));
	$enddate=date("Y-m-d", strtotime("+4 day"));
	$displayend=date("d/m/Y", strtotime("+4 day"));
	$output=$output."<h3>Between ".$displaystart." and ".$displayend."</h3>";
	$sql = "select 
	P.petid, P.title, P.detail, Count(S.status) as numsigs,
	P.petitionstatus, P.petitioncreated, P.petitiondisabled from ePetition as P
	left join eSignatures as S 
	ON S.petid = P.petid 
	WHERE petitionstatus='Approved' AND S.status=1 AND P.petitiondisabled > '$startdate' AND p.petitiondisabled < '$enddate'
	GROUP BY S.Status, P.petid,P.title, P.detail, P.petitionstatus, P.petitioncreated, P.petitiondisabled";
	$stmt = sqlsrv_query($conn, $sql);          

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
		$output=$output."<table class='table'><thead class='bg-primary text-white'><tr><th scope='col'>Title</th><th class='d-none d-sm-table-cell'>Start Date</th><th class='d-none d-sm-table-cell'>End Date</th><th>Signature Count</th></tr></thead>";
	  }
	  $somerows=1;
	  $petid=$row['petid'];
	  $output=$output."<tr><td><a href='https://shawn-dev.redclev.net/epetition/details.php?id=".$row['petid']."'>".$row['title']."</td></a><td class='d-none d-sm-table-cell'>".$row['petitioncreated']->format('d/m/Y')."</td><td class='d-none d-sm-table-cell'>".$row['petitiondisabled']->format('d/m/Y')."</td><td>".$row['numsigs']."</td></tr>";
	}
	$output=$output."</table>";
	if($somerows==1)
	{
		echo $output;
		$_SESSION['title']="Petitions ending soon - ".$displaystart;
		$_SESSION['content']=$output;
		$_SESSION['email']="shawn.carter@redcar-cleveland.gov.uk";
		$_SESSION['success']="";
		
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
		}
		else
		{
			echo "Issue sending email!";
			//die(header("Location: /epetition/error.php"));
		}
	}
		else
	{
		echo "Nothing to send";
	}
	sqlsrv_close($conn); 
?>
