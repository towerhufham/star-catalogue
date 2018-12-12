<?php
    session_start();
    
    if($_SESSION['username']!=null){
        $currentUser = $_SESSION['username'];
        $logStatus = "Logout";
    } else {
		$currentUser = "Not Logged In";
		$logStatus = "Login";
    }

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
</body>
<main>
<?php
    if (array_key_exists("search", $_GET)) {
        //search for star proper names for LIKE what the user input in the search box
        $searchVal = strtolower($_GET["search"]);
        $query = "SELECT * FROM BOOKMARK WHERE lower(star) LIKE " . "'%" . $searchVal . "%'";
                
		$newBookmarkResults = mysqli_query($dbc, $query);
		//replace $starResults with all the stars with the refined $newStarResults
		
		$bookmarkResults = $newBookmarkResults;
		//display number of rows found
		$numResults = $bookmarkResults->num_rows;
		echo "<br>Found " . $numResults . " results.";
    }
?>

<br> 
<!-- Output table -->
	<table>
		<tr>
			<th>Star Proper Name</th>
			
			<th>Constellation</th>
		</tr>
		<!-- Output the results table one row at a time -->
		<?php 
			//create table
			foreach ($bookmarkResults as $one_star) { ?>
				<tr>
					<!-- Each row is an array. -->
					<!-- Each item in a row is referenced using the db attribute as the index -->
					<td><?php echo $one_star['star']; ?></td>
					
					<td><?php echo $one_star['constellation']; ?></td>
				</tr>
		<?php } ?>
	</table>
</br>