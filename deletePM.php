<?php
//Add beginning code to 
//1. Require the needed 3 files
//2. Connect to your database
//3. Output a message, if there is one
	require_once("session.php"); 
	require_once("included_functions.php");
	require_once("database.php");

	ini_set("display_errors", 1);
	error_reporting(E_ALL);
	new_header("Personal Finance Management"); 
	$mysqli = Database::dbConnect();
	$mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	if (($output = message()) !== null) {
		echo $output;
	}
	
  	if (isset($_GET["id"]) && $_GET["id"] !== "") {
//////////////////////////////////////////////////////////////////////////////////////				
	  //Prepare and execute a query to DELETE FROM using GET id in criterion - WHERE songID = ?
	  //Use $stmt
		$stmt = $mysqli-> prepare("DELETE FROM Transactions WHERE TransactionID=?");
		// Execute query
		$delete = $stmt -> execute([$_GET['id']]);
  
		if ($delete) {
			//Create SESSION message that the song was successfully deleted
			$_SESSION['message'] = "Transaction #". $_GET['id'] ." was successfully deleted!";
		}
		else {
			//Create SESSION message that the song could not be deleted
			$_SESSION['message'] = "Transaction could not be deleted!";
		}
		
		//************** Redirect to readS24.php
		redirect("readPM.php");
		
//////////////////////////////////////////////////////////////////////////////////////				
	}
	else {
		$_SESSION["message"] = "Transaction could not be found!";
		redirect("readPM.php");
	}

			
			
//Define footer with the phrase "Top 40 Songs"
//Disconnect from database
	new_footer("Personal Finance Management");	
	Database::dbDisconnect($mysqli);
?>