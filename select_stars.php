<?php
	require_once('../mysqli_connection_data.php'); //adjust the relative path as necessary to find your config file
	//Filter the variable sent from the select list
	if(isset($_GET['constellation']))
		$constellation = strtolower(filter_var($_GET['constellation']));
	else
		$constellation = "andromeda"; //select a valid default value
	/* Without prepared statements
	$query = "SELECT AU_FNAME, AU_LNAME from FACT_AUTHOR where AU_ID=$authorID";
	$result = mysqli_query($dbc, $query);
	*/
	/*
	//With prepared statements:
	$query = "SELECT *FROM STAR where constellation=?";
	$stmt = mysqli_prepare($dbc, $query);
	mysqli_stmt_bind_param($stmt, "i", $constellation);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt); 
	//echo $constellation;
	if($result){ //it ran successfully
		$star= mysqli_fetch_assoc($result); //Fetches the row as an associative array with DB attributes as keys
		$properName = $star['starProperName'];
		//$constellation = $star['constellation'];
	}
	else {
		echo "That constellation was not found";
		mysqli_close($dbc);
		exit;
	}
	*/
	//Constellation found, retrieve stars within that constellation
		//With prepared statements:
		$query = "SELECT * FROM STAR WHERE constellation='$constellation'"; 
		//echo $query2;
		$stmt = mysqli_prepare($dbc, $query);
		mysqli_stmt_bind_param($stmt,"i",$constellation);
		mysqli_stmt_execute($stmt);
		$result= mysqli_stmt_get_result($stmt); 

		if($result) { //it ran successfully
		//Fetch all rows of result as an associative array
			mysqli_fetch_all($result, MYSQLI_ASSOC);
		}
		//remaining code the same for either of the above
		else { 
			echo "We were unable to retrieve the data for $constellation";
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
		<h2>Stars within <?php echo "$constellation"?>:</h2>
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
                            foreach ($result as $one_star) { ?>
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