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

    // Kien's query 3: for each User's 'FName LName' (use concat to list together),
    // list all transaction descriptions on one line. List users' 'FName LName' once 
    // with many transaction.description - use group concat.

    $query = "SELECT CONCAT(Users.FName, ' ', Users.LName) AS User,
    SUM(Transactions.Amount) AS Total, 
    GROUP_CONCAT(Transactions.Description SEPARATOR ', ') AS Description
    FROM Users NATURAL JOIN Accounts NATURAL JOIN Transactions 
    GROUP BY Users.UserID ORDER BY User";

    $stmt = $mysqli->prepare($query);
    $check = $stmt->execute();
    if ($check){
        echo "<div class='row'>";
		echo "<center>";
		echo "<h2>User and their transactions' descriptions (GROUP_CONCAT)</h2>";
		echo "<table>";
		echo "<thead>";
		echo "<tr><td>User</td><td>Total Amount</td><td>Description</td></tr>";
		echo "</thead>";
		echo "<tbody>";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo "<tr>";
            echo "<td>".$row['User']."</td>";
            echo "<td>".$row['Total']."</td>";
            echo "<td>".$row['Description']."</td>";
            echo "</tr>";
        }
        echo "</tbody>";
		echo "</table>";
    }
    echo "<br /><p>&laquo:<a href='readPM.php'>Back to Main Page</a>";
    echo "</center>";
    echo "</div>";
    echo "</label>";
	new_footer("Personal Finance Management");   
	Database::dbDisconnect($mysqli);
?>