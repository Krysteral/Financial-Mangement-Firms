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

    $query = "SELECT CONCAT(Users.FName, ' ', Users.LName) AS User, Accounts.InstitutionName AS Institution, Accounts.Balance AS Balance, Accounts.Currency AS Currency
    FROM Users NATURAL JOIN Accounts WHERE AccountTypeID = (SELECT AccountTypeID FROM AccountTypes WHERE TypeName='Checking')";

    $stmt = $mysqli->prepare($query);
    $check = $stmt->execute();
    if ($check){
        echo "<div class='row'>";
		echo "<center>";
		echo "<h2>User that has a Checking Account(Nesting)</h2>";
		echo "<table>";
		echo "<thead>";
		echo "<tr><td>User</td><td>Institution</td><td>Balance</td><td>Currency</td></tr>";
		echo "</thead>";
		echo "<tbody>";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo "<tr>";
            echo "<td>".$row['User']."</td>";
            echo "<td>".$row['Institution']."</td>";
            echo "<td>".$row['Balance']."</td>";
            echo "<td>".$row['Currency']."</td>";
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