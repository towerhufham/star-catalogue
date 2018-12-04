<?php
	ini_set('display_errors', 1);//Remove later
	require_once('../mysqli_connection_data.php'); //adjust the relative path as necessary to find your config file
	//$query = "SELECT BOOK_TITLE,BOOK_YEAR,BOOK_COST,BOOK_SUBJECT FROM FACT_BOOK";
    $query = "SELECT * FROM STAR ORDER BY starProperName asc";
	$result = mysqli_query($dbc, $query);
	//Fetch all rows of result as an associative array
	if($result)
		mysqli_fetch_all($result, MYSQLI_ASSOC);
	else {
		echo mysqli_error($result);  //Change to a generic message error before deployment
		mysqli_close($dbc);
		exit;
	}
?>

<!doctype html>

<html lang="en">

<head>
  <meta charset="utf-8">

  <title>Star Catalogue</title>
  <meta name="description" content="A catalogue of stars">
  <meta name="author" content="Team Data (CSC 455 Fall 2018)">

</head>

<body>
  <h1>Stars</h1>

  <form action="">
    Username: <input type="text" name="username"><br>
    Password: <input type="password" name="password"><br>
    Login <input type="radio" name="accountAction" value="login" checked>
    Register <input type="radio" name="accountAction" value="register"><br>
    <input type="submit" value="Go">
  </form>
  <br>
  <form action="">
    Search stars: <input type="search" name="search"><br>
    <input type="submit" value="Submit">
  </form>
  <!-- login stuff, search, ..? --->

  <main>
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
			<?php foreach ($result as $one_star) { ?>
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
