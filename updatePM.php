<?php 
//  NOTE: NOT all comments will reference the S24.php
//Add beginning code to 
//1. Require the needed 3 files
	require_once("session.php"); 
	require_once("included_functions.php");
	require_once("database.php");

	ini_set("display_errors", 1);
	error_reporting(E_ALL);
//2. Connect to your database
	new_header("Personal Finance Management"); 
	$mysqli = Database::dbConnect();
	$mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//3. Output a message, if there is one


	if (($output = message()) !== null) {
		echo $output;
	}

	echo "<h2>Update A Transaction</h3>";
	echo "<div class='row'>";
	echo "<label for='left-label' class='left inline'>";

	if (isset($_POST["submit"])) {
///////////////////////////////////////////////////////////////////////////////////////////				
		if (empty($_POST['account']) || empty($_POST['date']) || empty($_POST['amount']) || empty($_POST['description'])) {
			$_SESSION['message'] = "Error! All fields must be filled out.";
			redirect("readPM.php");
			exit;
		}
		//Step 2.
		//Create an UPDATE query using anonymous parameters and the criterion WHERE songID = ?	
		//Prepare and execute query (use $_POST values from submitted form)
		//Use $stmt
		$update ="UPDATE Transactions SET AccountID=?, Date=?, Amount=?, Description=?, CategoryID=? WHERE TransactionID=?";
		$stmt = $mysqli->prepare($update);
		$execute = $stmt->execute([$_POST['account'], $_POST['date'], $_POST['amount'], $_POST['description'], $_POST['category'], $_POST['TransactionID']]);
		//Verify $stmt executed 
		
		if($execute){
			$message =  "Transaction #".$_POST['TransactionID']." updated successfully";
		
		//If so, you need to also determine if a genre was selected and insert into songs_genres_S24 for this song - use stmt2 and row2
		//NOTE: You will need to verify this genre does not already exist for the song (you break your code if you try to insert a genre that already exists)
		//	    Create another query that selects - use stmtVerify
		//      $count = $stmtVerify -> rowCount(); returns the number of rows - if you need to add this genre you expect 0 rows
		//If the statement did not execute, create a SESSION message that the song already has this genre
	}
	 else{
		$_SESSION['message'] = "Error! Update not successful for transaction";
	 }
		
		//Redirect back to read.php


///////////////////////////////////////////////////////////////////////////////////////////

		//Output query results and return to read.php			
	
		redirect("readPM.php");
	}
	
///////////////////////////////////////////////////////////////////////////////////////////
	  //Step 1.
	  else if (isset($_GET["id"]) && $_GET["id"] !== "") {
	  //Use $stmt
		$query = "SELECT CONCAT(u.FName, ' ', u.LName) AS User, a.InstitutionName AS Institution, at.TypeName AS AccountType, 
		t.Date AS Date, t.Amount AS Amount, t.TransactionID AS TransactionID, t.Description AS Description, c.Name AS CategoryName, 
		ct.TypeName AS CategoryTypeName, t.AccountID AS AccountID, c.CategoryID AS CategoryID
		FROM Users u LEFT OUTER JOIN Accounts a ON u.UserID = a.UserID LEFT OUTER JOIN AccountTypes at ON a.AccountTypeID = at.AccountTypeID
		NATURAL JOIN Transactions t LEFT OUTER JOIN Categories c ON t.CategoryID = c.CategoryID 
		LEFT OUTER JOIN CategoryType ct ON c.TypeID = ct.TypeID WHERE TransactionID=?";
		$stmt = $mysqli->prepare($query);
		$stmt->execute([$_GET['id']]);
		
		//Verify statement successfully executed - I assume that results are returned to variable $stmt
		if ($row = $stmt->fetch(PDO::FETCH_ASSOC))  {
			echo "<form method='POST' action='updatePM.php'>";
			//Pre-populate form inputs with the song's information that we are updating
			//We will output all the genres but will not allow them to be edited
			//You also need to create a drop-down for the distinct genres - use stmt2 and row2
			//DON'T FORGET your submit button - use class attribute (i.e., class='button tiny round') and name is "Edit Song"
			
			//transactionID
			echo "<input type = 'hidden' name='TransactionID' value = ' ".$row['TransactionID']." ' />";
			//account
			// Prepare the query to fetch account details
			$stmt2 = $mysqli->prepare("SELECT AccountID, CONCAT(FName, ' ', LName) AS User, InstitutionName, TypeName FROM Users NATURAL JOIN Accounts NATURAL JOIN AccountTypes ORDER BY UserID ASC;");
			$stmt2->execute();

			echo "<label for='account'>Account: </label>";
			echo "<select id='account' name='account' required>";
			while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
				// Check if this account should be selected
				$selected = ($row2['AccountID'] == $row['AccountID']) ? 'selected' : '';
				echo "<option value='" . htmlspecialchars($row2['AccountID']) . "' $selected>" . htmlspecialchars($row2['User']) . ", " . htmlspecialchars($row2['InstitutionName']) . ", " . htmlspecialchars($row2['TypeName']) . "</option>";
			}
			echo "</select><br><br>";

			//date
			echo "<label for='date'>Date:</label>";
			echo "<input type='datetime-local' id='date' name='date' value='".$row['Date']."'><br><br>";
			//amount
			echo "<label for='amount'>Amount:</label>";
			echo "<input type='number' step='0.01' id='amount' name='amount' value='".$row['Amount']."'><br><br>";
			//Description
			echo "<label for='description'>Description:</label>";
			echo "<input type='text' id='description' name='description' value='".$row['Description']."'><br><br>";
			//Category
			echo "<label for='category'>Category:</label>";
			echo "<select id='category' name='category'>";
			$cat = $mysqli->prepare("SELECT CategoryID, Name FROM Categories ORDER BY Name ASC");
			$cat->execute();
			while ($rowC = $cat->fetch(PDO::FETCH_ASSOC)){
				$selected = ($rowC['CategoryID'] == $row['CategoryID']) ? 'selected' : ''; // Check if this category is the current category
   				echo "<option value='" . $rowC['CategoryID'] . "' $selected>" . htmlspecialchars($rowC['Name']) . "</option>";
				//echo "<option value='".$rowC['CategoryID']."'>".$rowC['Name']."</option>";
			}
			echo "</select><br><br>";

			echo "<input type='submit' name='submit' class='tiny round button' value='Update' />";
			echo "</form>";
			//UNCOMMENT ONCE YOU'VE COMPLETED THE FILE
			echo "<h3>Transaction #".$row["TransactionID"]." Information</h3>";
	
///////////////////////////////////////////////////////////////////////////////////////////

			echo "<br /><p>&laquo:<a href='readPM.php'>Back to Main Page</a>";
			//echo "</label>";
			echo "</div>";
		}
		//Query failed. Return to readS24.php and output error
		else {
			$_SESSION["message"] = "Transaction could not be found!";
			redirect("readPM.php");
		}
	  } else{
		echo "<br /><p>No ID provided. <a href='readPM.php'>Return to Main Page</a></p>";
	  }
					
//Define footer with the phrase "Top 40 Songs"
//Disconnect from database
	new_footer("Personal Finance Management");	
	Database::dbDisconnect($mysqli);

?>