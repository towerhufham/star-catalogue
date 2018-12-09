<?php
	require_once('../mysqli_config.php'); //adjust the relative path as necessary to find your config file
	//Filter the variable sent from the select list
	if(isset($_GET['constellation']))
		$constellation = filter_var($_GET['constellation']);
	else
		$constellation = "andromeda"; //select a valid default value
	/* Without prepared statements
	$query = "SELECT AU_FNAME, AU_LNAME from FACT_AUTHOR where AU_ID=$authorID";
	$result = mysqli_query($dbc, $query);
	*/
	//With prepared statements:
	$query = "SELECT * from Star where constellation=?";
	$stmt = mysqli_prepare($dbc, $query);
	mysqli_stmt_bind_param($stmt, "i", $authorID);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt); 
	
	if($result){ //it ran successfully
		$star= mysqli_fetch_assoc($result); //Fetches the row as an associative array with DB attributes as keys
		$properName = $star['starProperName'];
		$constellation = $star['constellation'];
	}
	else {
		echo "That star was not found";
		mysqli_close($dbc);
		exit;
	}
	//Author found, retrieve books by that author
		/* Without prepared statements: 
		$query2 = "SELECT BOOK_TITLE,BOOK_YEAR,BOOK_COST,BOOK_SUBJECT FROM FACT_BOOK join FACT_WRITES using (BOOK_NUM) where AU_ID=$authorID"; 
		$result2 = mysqli_query($dbc, $query2);
		//Fetch all rows of result as an associative array
		if($result2)
			mysqli_fetch_all($result2, MYSQLI_ASSOC); */
		
		//With prepared statements:
		$query2 = "SELECT * FROM STAR where constellation=?"; 
		$stmt2 = mysqli_prepare($dbc, $query2);
		mysqli_stmt_bind_param($stmt2,"i",$authorID);
		mysqli_stmt_execute($stmt2);
		$result2= mysqli_stmt_get_result($stmt2); 
		if($result2) { //it ran successfully
		//Fetch all rows of result as an associative array
			mysqli_fetch_all($result2, MYSQLI_ASSOC);
		}
		//remaining code the same for either of the above
		else { 
			echo "We were unable to retrieve the data for $properName $constellation";
			mysqli_close($dbc);
			exit;
		} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Stars</title>
	<meta charset ="utf-8"> 
	<link href="main.css" rel="stylesheet">
	<!-- Name -->
</head>
<body>
	<header>
		<h2>Stars within <?php echo "$properName $constellation"?>:</h2>
	</header>
	<main>
    <br> <!-- Output table -->
                <table>
                    <tr>
                        <th>Star Proper Name</th>
                        <th>Bayer Designation</th>
                        <th>Variable Star</th>
                        <th>Henry Draper Catalogue</th>
                        <th>Hipparcos</th>
                        <th>Right Ascension</th>
                        <th>Declination</th>
                        <th>Apparent Magnitude</th>
                        <th>Absolute Magnitude</th>
                        <th>Cosmic Distance Ladder</th>
                        <th>Stellar Classification</th>
                        <th>Notes</th>
                        <th>Constellation</th>
                    </tr>
                    <!-- Output the results table one row at a time -->
                    <?php 
                            foreach ($starResults as $one_star) { ?>
                                <tr>
                                    <!-- Each row is an array. -->
                                    <!-- Each item in a row is referenced using the db attribute as the index -->
                                    <td><?php echo $one_star['starProperName']; ?></td>
                                    <td><?php echo $one_star['bayerDesignation']; ?></td>
                                    <td><?php echo $one_star['variableStar']; ?></td>
                                    <td><?php echo $one_star['henryDraperCatalogue']; ?></td>
                                    <td><?php echo $one_star['hipparcos']; ?></td>
                                    <td><?php echo $one_star['rightAscension']; ?></td>
                                    <td><?php echo $one_star['declination']; ?></td>
                                    <td><?php echo $one_star['apparentMagnitude']; ?></td>
                                    <td><?php echo $one_star['absoluteMagnitude']; ?></td>
                                    <td><?php echo $one_star['cosmicDistanceLadder']; ?></td>
                                    <td><?php echo $one_star['stellarClassification']; ?></td>
                                    <td><?php echo $one_star['notes']; ?></td>
                                    <td><?php echo $one_star['constellation']; ?></td>
                                </tr>
                    <?php } ?>
                </table>
    </main>
</body>
</html>