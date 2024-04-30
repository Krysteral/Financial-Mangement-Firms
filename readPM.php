<?php 
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

	echo "<div class='row'>";
	echo "<center>";
	echo "<a href='createPM.php'>Add a Transaction</a> | <a href='addCategory.php'>Add a Category</a> | <a href='addUser.php'>Add an User</a> | <a href='addAccount.php'>Add an Account</a><br><br>";
	echo "<a href='Query1.php'>Query1 (Visho)</a> | <a href='Query2.php'>Query2 (Kien)</a> | <a href='Query3.php'>Query3 (Kien)</a> | <a href='Query4.php'>Query4 (Visho)</a><br><br>";

	echo "</center>";
	echo "</div>";

	//****************  Add Query
	

	$query2 = "SELECT CONCAT(u.FName, ' ', u.LName) AS User, a.InstitutionName AS Institution, at.TypeName AS AccountType, 
	t.Date AS Date, t.Amount AS Amount, t.TransactionID AS TransactionID, t.Description AS Description, 
	GROUP_CONCAT(DISTINCT c.Name ORDER BY c.NAME ASC SEPARATOR ', ') AS CategoryName, GROUP_CONCAT(DISTINCT ct.TypeName SEPARATOR ', ') AS CategoryTypeName 
	FROM Users u LEFT OUTER JOIN Accounts a ON u.UserID = a.UserID LEFT OUTER JOIN AccountTypes at ON a.AccountTypeID = at.AccountTypeID
	NATURAL JOIN Transactions t LEFT OUTER JOIN Categories c ON t.CategoryID = c.CategoryID LEFT OUTER JOIN CategoryType ct ON c.TypeID = ct.TypeID 
	GROUP BY TransactionID ORDER BY Date DESC";

	$stmt2 = $mysqli->prepare($query2);
	$execute2 = $stmt2->execute();	
				
///********************    Uncomment Once Code Completed  **************************  
	
	if ($execute2) {
		echo "<div class='row'>";
		echo "<center>";
		echo "<h2>Transaction Log</h2>";
		echo "<table>";
		echo "<thead>";
		echo "<tr><td><td>User</td><td>Institution</td><td>Account Type</td><td>Date</td><td>Amount</td><td>Description</td><td>Category</td><td>Category Type</td><td></tr>";
		echo "</thead>";
		echo "<tbody>";
		while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
			echo "<tr>";
			echo "<td> &nbsp; <a href='deletePM.php?id=".urlencode($row2['TransactionID'])."' onclick=\"return confirm('Are you sure you want to delete?');\"> <img src='red_x_icon.jpg' width='15' height='15'> </a> Delete </td>";
			echo "<td>".$row2['User']."</td>";
			echo "<td>".$row2['Institution']."</td>";
			echo "<td>".$row2['AccountType']."</td>";
			//echo "<td>".$row2['Balance']."</td>";
			echo "<td>".$row2['Date']."</td>";
			echo "<td>".$row2['Amount']."</td>";
			echo "<td>".$row2['Description']."</td>";
			echo "<td>".$row2['CategoryName']."</td>";
			echo "<td>".$row2['CategoryTypeName']."</td>";
			echo "<td> &nbsp; <a href='updatePM.php?id=".urlencode($row2['TransactionID'])."'> <img src='pencil_icon.jpg' width=30px height=30px> </a> Update </td>";
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";
		echo "</center>";
		echo "</div>";
	}
	
	// Footer 
	
	new_footer("Personal Finance Management");   
	Database::dbDisconnect($mysqli);	
 ?>


