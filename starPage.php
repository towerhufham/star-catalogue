<?php
    session_start();
    if($_SESSION['username']){
        //echo "login successful";
        $currentUser = $_SESSION['username'];
        $logStatus = "Logout";
        $_SESSION['username'] = $currentUser;
    }
    else{
        $currentUser = "Not Logged In";
        $logStatus= "Login";
    }
	ini_set('display_errors', 1);//Remove later
	require_once('../mysqli_connection_data.php'); //adjust the relative path as necessary to find your config file
    $starQuery = "SELECT * FROM STAR ORDER BY constellation asc";
    //$accountQuery = "SELECT username, password FROM USER";
    $constellationQuery = "SELECT * FROM CONSTELLATION ORDER BY constellationName ASC"; 

	$starResults = mysqli_query($dbc, $starQuery);
    //$accountResults = mysqli_query($dbc, $accountQuery);
    $constellationResults = mysqli_query($dbc, $constellationQuery);
	//Fetch all rows of result as an associative array
	if($starResults) {
		mysqli_fetch_all($starResults, MYSQLI_ASSOC);
	} else {
		echo mysqli_error($dbc);  //Change to a generic message error before deployment
		mysqli_close($dbc);
		exit;
    }
?>

<!doctype html>

<html lang="en">

<head>
  <meta charset="utf-8">
  <link href="main.css" rel="stylesheet">
  <title>Star Catalogue</title>
  <meta name="description" content="A catalogue of stars">
  <meta name="author" content="Team Data (CSC 455 Fall 2018)">
  
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
</head>

<body>
  <h1>Star Catalogue Team Data</h1>
  <h2>
    <?php echo $currentUser; ?>
    <form action='' method="post">
    <input type="submit" name='logout' id=logout value=<?php echo $logStatus?>> 
    </form>
    </h2>
    <?php
    function logout(){
        $_SESSION['username'] = null;
        $currentUser = "Not Logged In";
        $logStatus = "Login";
        header('Location: Login.php');
    }

    if(array_key_exists('logout',$_POST)){
        echo "logout";
        logout();
    }
    ?>
	<form method= "POST" action =''>
		Reset star chart: <input type = "submit" name="resetBtn" value="reset">
	</form>
	<form action="constellationPage.php">
		See constellations: <input type="submit" value="Go" />
	</form>
	
  <form action= "bookmarks.php" method="get">
        Open bookmarks: <input type="submit" value="Go">
        <br>
  </form>

  <form action="">
    Search stars: <input type="search" name="search">
    <input type="submit" value="Submit">
  </form>
  <br>
  <form method="POST" action=''>
		Show most popular Stars: <input type="submit" name="popularBtn" value="Show">
  </form>
</body>
<main>
	<form method="POST" action=''>
		Sort by Bayer Designation: <input type="submit" name="bayerBtn" value="Show">
	</form>
    <form action = "select_stars.php" method="get">
		<!-- Use a PHP loop to generate a select list of constellations in the DB -->
		Explore the constellation you are lookin for: 
		<select name="constellation">
		<?php foreach ($constellationResults as $constellation) {
			$constellationNameID = $constellation['constellationName'];
			echo "<option value=\"$constellationNameID\">$constellationNameID</option>";
		} ?>
		</select>
		<input type="submit" value="Find Stars">
	</form>

    <!--save bookmarks for user button -->
    <form action ="bookmarks.php" method="get">
        Save bookmarks selected via this button: 
        <input type="submit" value="Save">
	
		<br> <!-- Output table -->
			<table>
				<tr>
					<th>Bookmark</th>
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
				//searching
				if (array_key_exists("search", $_GET)) {
					$query = "SELECT * FROM STAR WHERE starProperName LIKE " . "'%" . $_GET["search"] . "%'";
					//echo $query;
					$newStarResults = mysqli_query($dbc, $query);
					global $starResults;
					$starResults = $newStarResults;
					$numResults = $starResults->num_rows;
					echo "<br>Found " . $numResults . " results.";
				}
				if(isset($_POST['popularBtn'])){
					$query = "SELECT STAR.starProperName, STAR.bayerDesignation,STAR.variableStar, 
					STAR.henryDraperCatalogue, STAR.hipparcos, STAR.rightAscension, STAR.declination,
					 STAR.apparentMagnitude, STAR.absoluteMagnitude, STAR.cosmicDistanceLadder, 
					 STAR.stellarClassification, STAR.notes, BOOKMARK.constellation 
					 FROM BOOKMARK, STAR 
					 WHERE BOOKMARK.star = STAR.starProperName
					 ORDER BY BOOKMARK.constellation ASC";
					$newStarResults = mysqli_query($dbc, $query);
					global $starResults;
					$starResults = $newStarResults;
					//$numResults = $starResults->num_rows;
				}
				else if(isset($_POST['bayerBtn'])){
					$bayerQuery = "SELECT * FROM STAR WHERE STAR.bayerDesignation <> ''";

					$starResults = mysqli_query($dbc, $bayerQuery);
				}
				else if(isset($_POST['resetBtn'])){
					$starResults = mysqli_query($dbc, $starQuery);
				}
				foreach ($starResults as $one_star) { ?>
						<tr>
							<!-- Each row is an array. -->
							<!-- Each item in a row is referenced using the db attribute as the index -->
							<td><input type='checkbox' name='newBookmarks[]' value= "<?php echo $one_star['starProperName'] ?>" ></td>
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
				<?php }?>
			</table>
		<br>
	</form>
</main>