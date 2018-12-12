<?php
    //testing
	ini_set('display_errors', 1);//Remove later
	require_once('../mysqli_connection_data.php'); //adjust the relative path as necessary to find your config file
	//$fullQuery = "SELECT BOOK_TITLE,BOOK_YEAR,BOOK_COST,BOOK_SUBJECT FROM FACT_BOOK";
    $fullQuery = "SELECT * FROM CONSTELLATION";
	$borderQuery = "SELECT A.constellationName, A.abbreviation, A.borderingConstellation, B.abbreviation borderingAbbreviation FROM CONSTELLATION A, CONSTELLATION B WHERE A.borderingConstellation = B.constellationName ORDER BY A.constellationName";
	$fullResult = mysqli_query($dbc, $fullQuery);
	$borderResult = mysqli_query($dbc, $borderQuery);
	//Fetch all rows of result as an associative array
	if($fullResult)
		mysqli_fetch_all($fullResult, MYSQLI_ASSOC);
	else { 
		echo mysqli_error($fullResult);  //Change to a generic message error before deployment
		mysqli_close($dbc);
		exit;
	} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<link href="main.css" rel="stylesheet">
    <title>Constellations</title>
	<meta charset ="utf-8"> 
	<h1>Team Data</h1>
</head>
<body>
	<header>
		<h1>List of All Constellations</h1>
	</header>
	<form action="starPage.php">
		<input type="submit" value="Back" />
	</form>
	<br>
	<table>
		<tr>
			<th>Constellation Name</th>
			<th>Abbreviation</th>
		</tr>
		<!-- Output the results table one row at a time -->
		<?php foreach ($fullResult as $one_const) { ?>
		<tr>
			<!-- Each row is an array. -->
			<!-- Each item in a row is referenced using the db attribute as the index -->
			<td><?php echo $one_const['constellationName']; ?></td>
			<td><?php echo $one_const['abbreviation']; ?></td>
			
		</tr>
		<?php } ?>  		
	</table>
	<header>
		<h1>List of Bordering Constellations</h1>
	</header>
	<table>
		<tr>
			<th>Constellation Name</th>
			<th>Abbreviation</th>
			<th>Bordering Constellation</th>
			<th>Bordering Constellation Abbreviation</th>
		</tr>
		<!-- Output the results table one row at a time -->
		<?php foreach ($borderResult as $one_const) { ?>
		<tr>
			<!-- Each row is an array. -->
			<!-- Each item in a row is referenced using the db attribute as the index -->
			<td><?php echo $one_const['constellationName']; ?></td>
			<td><?php echo $one_const['abbreviation']; ?></td>
			<td><?php echo $one_const['borderingConstellation']; ?></td>
			<td><?php echo $one_const['borderingAbbreviation']; ?></td>
		</tr>
		<?php } ?>  		
	</table>
</body>
</html>