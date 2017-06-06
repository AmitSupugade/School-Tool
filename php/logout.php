<?php
session_start();
session_unset();  //Unset all the session variables
if(session_destroy()) // Destroying All Sessions
{
	echo "index"; // Redirecting To Home Page
}
?>