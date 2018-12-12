<?php
    session_start();
    
    if($_SESSION['username']!=null){
        $currentUser = $_SESSION['username'];
        $logStatus = "Logout";
    } else {
		$currentUser = "Not Logged In";
		$logStatus = "Login";
    }
    $buildNewTable = false;
    if ($currentUser != "Not Logged In"){
        ini_set('display_errors', 1);//Remove later
        require_once('../mysqli_connection_data.php'); //adjust the relative path as necessary to find your config file
		
		//add new bookmarks if needed
		if (array_key_exists("newBookmarks", $_GET) and $currentUser != "Not Logged In") {
			$newBookMarks = $_GET["newBookmarks"];
			foreach ($newBookMarks as $starName) {
				$star = mysqli_query($dbc, "SELECT * FROM STAR WHERE starProperName = '" . $starName . "'") -> fetch_assoc();
				$const = $star["constellation"];
				mysqli_query($dbc, "INSERT INTO BOOKMARK (constellation, star, userID) VALUES ('". $const . "', '" . $starName . "', '" . $currentUser . "')");
			}
		}
		
        $bookmarkQuery = "SELECT * FROM BOOKMARK WHERE userID = '$currentUser' ORDER BY constellation asc";

        $bookmarkResults = mysqli_query($dbc, $bookmarkQuery);
        //Fetch all rows of result as an associative array
        if($bookmarkResults) {
            mysqli_fetch_all($bookmarkResults, MYSQLI_ASSOC);
        } else {
            echo mysqli_error($dbc);  //Change to a generic message error before deployment
            mysqli_close($dbc);
            exit;
        }

    }
	else{
        echo "Please Log In to view your Bookmarks";?> 
        <form action="Login.php" method="get">
        <?php
            $_SESSION['username'] = null;
        ?>
        <input type="submit" value=<?php echo $logStatus?>>
        </form>
    <?php }
    
?>


<!doctype html>

<html lang="en">

<head>
  <meta charset="utf-8">
  <link href="main.css" rel="stylesheet">
  <title>Star Catalogue</title>
  <meta name="description" content="A catalogue of stars">
  <meta name="author" content="Team Data (CSC 455 Fall 2018)">
  
</head>

<body>
  <h1>Book Marks</h1>
  <h2><?php echo $currentUser?></h2>
  <form action="">
    Search bookmarks: <input type="search" name="search"><br>
    <input type="submit" value="Submit">
  </form>
  <form action="starPage.php">
		<input type="submit" value="Back" />
  </form>
  <form method="POST" action=''>
        Show detailed view: <input type="submit" name = "detailBtn" value="Show">
    </form>
</body>
<main>
<?php
    if (array_key_exists("search", $_GET)) {
        //search for star proper names for LIKE what the user input in the search box
        $searchVal = strtolower($_GET["search"]);
        $query = "SELECT * FROM BOOKMARK WHERE lower(star) LIKE " . "'%" . $searchVal . "%'";
                
		$bookmarkResults = mysqli_query($dbc, $query);
		//replace $starResults with all the stars with the refined $newStarResults
		
		//display number of rows found
		$numResults = $bookmarkResults->num_rows;
		echo "<br>Found " . $numResults . " results.";
    }

    if(isset($_POST['detailBtn'])){
        $query = "SELECT STAR.starProperName, STAR.bayerDesignation,STAR.variableStar, 
        STAR.henryDraperCatalogue, STAR.hipparcos, STAR.rightAscension, STAR.declination,
         STAR.apparentMagnitude, STAR.absoluteMagnitude, STAR.cosmicDistanceLadder, 
         STAR.stellarClassification, STAR.notes, BOOKMARK.constellation FROM BOOKMARK, 
         STAR WHERE BOOKMARK.star = STAR.starProperName and BOOKMARK.userID = '" . $currentUser . "' 
         ORDER BY BOOKMARK.constellation ASC";

        $bookmarkResults = mysqli_query($dbc, $query);
		
        $buildNewTable = true;
        //$numResults = $bookmarkResults->num_rows;
    }
?>

<br> 
<!-- Output table -->
	<table>
		<tr>
			<th>Star Proper Name</th>
            <?php
            if($buildNewTable){
            echo "<th>Bayer Designation</th>";
			echo "<th>Variable Star</th>";
			echo "<th>Henry Draper Catalogue</th>";
			echo "<th>Hipparcos</th>";
			echo "<th>Right Ascension</th>";
			echo "<th>Declination</th>";
			echo "<th>Apparent Magnitude</th>";
			echo "<th>Absolute Magnitude</th>";
			echo "<th>Cosmic Distance Ladder</th>";
			echo "<th>Stellar Classification</th>";
			echo "<th>Notes</th>";
            }
            ?>
			<th>Constellation</th>
		</tr>
		<!-- Output the results table one row at a time -->
		<?php 
			//create table
			foreach ($bookmarkResults as $one_star) { ?>
				<tr>
					<!-- Each row is an array. -->
					<!-- Each item in a row is referenced using the db attribute as the index -->
					
					<?php
                    if($buildNewTable){
                        echo "<td>" . $one_star['starProperName'] . "</td>";
                        echo "<td>" . $one_star['bayerDesignation'] . "</td>";
						echo "<td>" . $one_star['variableStar'] . "</td>";
						echo "<td>" . $one_star['henryDraperCatalogue'] . "</td>";
						echo "<td>" . $one_star['hipparcos'] . "</td>";
						echo "<td>" . $one_star['rightAscension'] . "</td>";
						echo "<td>" . $one_star['declination'] .  "</td>";
						echo "<td>" . $one_star['apparentMagnitude'] . "</td>";
						echo "<td>" . $one_star['absoluteMagnitude'] . "</td>";
						echo "<td>" . $one_star['cosmicDistanceLadder'] . "</td>";
						echo "<td>" . $one_star['stellarClassification'] . "</td>";
                        echo "<td>" . $one_star['notes'] . "</td>";
                        echo "<td>" . $one_star['constellation'] . "</td>";
                    }
                    else{
                        echo "<td>" . $one_star['star'] . "</td>";
                        echo "<td>" . $one_star['constellation'] . "</td>";
                    }
                    ?>
					
				</tr>
		<?php } ?>
	</table>
<br>