# ePetitions
A quite simple web application for public electronic petitions.  See attached user guides for more information on how it works.

History

Our online ePetitions system which was built in Sharepoint stopped working, but we were getting rid of Sharepoint soon, so I was asked to create a tactical solution.
Initial development done, the urgency for this wained - so I took the opportunity to add a few more features.
Unfortunately the solution was never adopted, which is a shame because it is a working solution.
After discussion with managers, it was decided to release the sourcecode - as it might save some other authority the time if this is what they need.

Requirements

Windows Server running IIS
PHP 8.x
PHP SQL Server extension
MS SQL Server

Instructions

Copy the folder to your IIS server, create a new site using this folder.
Create your DB and tables using the scripts in 'sql table create scripts' - this will help create the 4 tables that the application uses.
Update your dbconnect.php with your SQL Server name, SQL UserID and password.
Replace the Logo.jpg with your own, and make changes wherever there is mention of Redcar and Cleveland.
Make sure that your server is able to send emails via your SMTP server (this is configured in your php.ini).
Create a new user, once your email is working...
I never created facility to change user rights from normal user to admin, as we were only going to have 1 administrator.
To change this, you need to do it manually using SSMS - changing userlevel in the ePetLogin table to value 2.

Feel free to use/reuse this code/make changes as you see fit - open source, no license required.
