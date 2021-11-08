<?php
//This function is used to send logged in users away from public pages (including admin)
function publicPage()
{
	if(isset($_SESSION['userid']))
	{
		// Logged in user attempts to go to a public page
		die(header("Location: /epetition/home.php"));
		// Send them back to home page - they can logout if they want to sign something
	}
}

//This function is used to send public (not logged in) and admins back to the home/approvals page
function userPage()
{
	if(isset($_SESSION['userid']) && isset($_SESSION['key']))
	{
		// Admin have clicked on a link that is meant for users
		die(header("Location: /epetition/admin.approvals.php"));
		// Send them back to the admin approvals page
	}
	if(!isset($_SESSION['userid']))
	{
		// User not logged in attempting to see page that requires login
		die(header("Location: /epetition/"));  
		// Send them back to /epetition/ (root)
	}
}

function adminPage()
{
	if(isset($_SESSION['userid']) && isset($_SESSION['key']))
	{
		if(!password_verify($_SESSION['userid']."s3cr3t!",$_SESSION['key']))
		{
			// User not administrator - trying to access admin page
			die(header("Location: /epetition/")); // Key check failed
		}
	}
	elseif (isset($_SESSION['userid']))
	{  // User logged in but not admin
		die(header("Location: /epetition/home.php"));
		// Send them to /epetition/home.php
	}
	else
	{   // User not logged in - possibly attempting to circumvent security
		die(header("Location: /epetition/"));
		// Send them to /epetition/ (root)
	}
}

function userNav()
{
	// We use this to modify the navigation bar for logged in users
	if(isset($_SESSION['userid']))
	{
    echo '<script>$("#nav1").remove();</script>';
    echo '<script>$("#nav2").replaceWith(\'<a class="nav-link active" href="/epetition/newpetition.php">New Petition</a>\');</script>';
    echo '<script>$("#nav3").replaceWith(\'<a class="nav-link" href="/epetition/home.php">My ePetitions</a>\');</script>';
    echo '<script>$("#nav4").replaceWith(\'<a class="nav-link" href="/epetition/logout.php">Logout</a>\');</script>';
	}
}

?>