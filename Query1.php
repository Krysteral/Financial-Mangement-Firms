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

    $query = "SELECT IT.TypeName AS Type, 
    ROUND(AVG(I.AmountInvested), 1) AS AverageInvested
    FROM Investments AS I
    JOIN InvestmentTypes AS IT ON I.InvestmentTypeID = IT.InvestmentTypeID
    GROUP BY IT.TypeName ORDER BY IT.TypeName";

    $stmt = $mysqli->prepare($query);
    $check = $stmt->execute();
    if ($check){
        echo "<div class='row'>";
		echo "<center>";
		echo "<h2>Average amount for transactions in each category (Aggregate)</h2>";
		echo "<table>";
		echo "<thead>";
		echo "<tr><td>Investment Category</td><td>Average</td></tr>";
		echo "</thead>";
		echo "<tbody>";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo "<tr>";
            echo "<td>".$row['Type']."</td>";
            echo "<td>".$row['AverageInvested']."</td>";
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