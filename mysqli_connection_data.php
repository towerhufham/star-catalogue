<?php 

// This file contains the database access information. 
// This file establishes a connection to MySQL and selects the database.

// Set the database access information as constants:
DEFINE ('DB_USER', 'cns5289');  //replace yourusername with your own username
DEFINE ('DB_PASSWORD', '1003072Cs'); //replace yourDBpassword with your own DB password which is your username
DEFINE ('DB_HOST', 'localhost'); //don't change
DEFINE ('DB_NAME', 'CSC455FA18Data'); //replace yourusername with your own username

// Make the connection:
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );
//echo "Connection successful!";  //remove this statement once you know it is working
mysqli_set_charset($dbc,"utf8");
?>
