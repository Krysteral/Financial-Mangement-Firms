<?php 
require_once("session.php"); 
require_once("included_functions.php");
require_once("database.php");

ini_set("display_errors", 1);
error_reporting(E_ALL);

new_header("Personal Finance Management"); 
$mysqli = Database::dbConnect();
$mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (($output = message()) !== null) {
    echo $output;
}

if (isset($_POST["submit"])) {
    if((isset($_POST["account"]) && $_POST["account"] !== "") && (isset($_POST["date"]) && $_POST["date"] !== "") &&(isset($_POST["amount"]) && $_POST["amount"] !== "") &&(isset($_POST["category"]) && $_POST["category"] !== "") &&(isset($_POST["description"]) && $_POST["description"] !== "")) {
        // Check if the account is valid and get the account ID if it is
        $stmt3 = $mysqli->prepare("SELECT MAX(TransactionID) AS maxTranID FROM Transactions");
        $stmt3 -> execute();
        $result = $stmt3 -> fetch(PDO::FETCH_ASSOC);
        $nextTranID = $result['maxTranID'] + 1;
            // Prepare transaction insertion
            $stmt2 = $mysqli->prepare("INSERT INTO Transactions (TransactionID, AccountID, Date, Amount, CategoryID, Description) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt2->execute([$nextTranID, $_POST['account'], $_POST['date'], $_POST['amount'], $_POST['category'], $_POST['description']])) {
                $_SESSION['message'] = "Transaction added successfully";
                header("Location: readPM.php");
                exit;
            } else {
                $_SESSION['message'] = "Error! Could not add transaction";
            }
    
        header("Location: createPM.php");
        exit;
    } else {
        $_SESSION["message"] = "Please fill in all the fields.";
        header("Location: createPM.php");
        exit;
    }
}


echo "<div class='row'>";
echo "<label for='left-label' class='left inline'>";
echo "<h3>Add a Transaction</h3>";
echo "<form method='POST' action=''>";

echo "<label for='account'>Account: </label>";
echo "<select id='account' name='account' required>";
echo "<option value=''></option>";
$stmt = $mysqli->prepare("SELECT DISTINCT CONCAT(FName, ' ', LName) AS User, AccountID, InstitutionName, TypeName FROM Users NATURAL JOIN Accounts NATURAL JOIN AccountTypes ORDER BY UserID ASC;");
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<option value='" . $row['AccountID'] . "'>" . htmlspecialchars($row['User']) . ", " . htmlspecialchars($row['InstitutionName']) . ", " . htmlspecialchars($row['TypeName']) . "</option>";
}
echo "</select><br><br>";

echo "<label for='date'>Date:</label>";
echo "<input type='datetime-local' id='date' name='date' required><br><br>";

echo "<label for='amount'>Amount:</label>";
echo "<input type='number' step='0.01' id='amount' name='amount' required><br><br>";

echo "<label for='description'>Description:</label>";
echo "<input type='text' id='description' name='description' required><br><br>";

echo "<label for='category'>Category:</label>";
echo "<select id='category' name='category' required>";
echo "<option value=''></option>";
$cat = $mysqli->prepare("SELECT CategoryID, Name FROM Categories ORDER BY Name ASC");
$cat->execute();
while ($rowC = $cat->fetch(PDO::FETCH_ASSOC)){
    echo "<option value='".$rowC['CategoryID']."'>".$rowC['Name']."</option>";
}
echo "</select><br><br>";
echo "<input type='submit' name='submit' class='tiny round button' value='Add Transaction' />";
echo "</form>";
echo "<br><p>&laquo;<a href='readPM.php'>Back to Main Page</a>";
echo "</label>";
echo "</div>";
new_footer("Personal Finance Management");
Database::dbDisconnect($mysqli);
?>
