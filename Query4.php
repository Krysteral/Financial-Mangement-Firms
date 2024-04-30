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

    $query = "SELECT c.Name AS CategoryName,
    GROUP_CONCAT(DISTINCT t.Description ORDER BY t.Description SEPARATOR ', ') AS Descriptions,
    GROUP_CONCAT(DISTINCT CONCAT(u.FName, ' ', u.LName) ORDER BY u.FName, u.LName SEPARATOR ', ') AS Users
    FROM Categories c
    NATURAL JOIN Transactions t 
    NATURAL JOIN Accounts a 
    NATURAL JOIN Users u
    GROUP BY c.CategoryID
    ORDER BY c.Name";

    $stmt = $mysqli->prepare($query);
    $check = $stmt->execute();
    if ($check){
        echo "<div class='row'>";
		echo "<center>";
		echo "<h2>Category with descriptions and users (GROUP_CONCAT)</h2>";
		echo "<table>";
		echo "<thead>";
		echo "<tr><td>Category</td><td>Descriptions</td><td>Users</td></tr>";
		echo "</thead>";
		echo "<tbody>";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo "<tr>";
            echo "<td>".$row['CategoryName']."</td>";
            echo "<td>".$row['Descriptions']."</td>";
            echo "<td>".$row['Users']."</td>";
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