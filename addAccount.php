<?php
require_once("session.php");
require_once("included_functions.php");
require_once("database.php");

ini_set("display_errors", 1);
error_reporting(E_ALL);

new_header("Personal Finance Managment"); 
$mysqli = Database::dbConnect();
$mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if (($output = message()) !== null) {
    echo $output;
}

if(isset($_POST['submit'])){
	if((isset($_POST['user']) && $_POST['user'] !== "") && (isset($_POST['account']) && $_POST['account'] !== "") && (isset($_POST['institution']) && $_POST['institution'] !== "") && (isset($_POST['balance']) && $_POST['balance'] !== "") && (isset($_POST['currency']) && $_POST['currency'] !== "")){
	    //Grab posted values for username and password, encrypting the password
		//so that it is set up to compare with the encrypted password in the database
		//Use password_encrypt
		
		//Query database for this "new" username. Be sure to limit 1 in your SQL as there should only be one.
		$verify = $mysqli->prepare("SELECT AccountID FROM Accounts WHERE UserID = ? AND AccountTypeID=?");
		$verify->execute([$_POST['user'], $_POST['account']]);
		$count = $verify -> rowCount();
		
		
		//If the username DOES exist in table, create a session message - "The username already exists"
		//Reidrect back to addLogin.php
		if($count != 0){
			$_SESSION['message'] = "The account already exists.";
			redirect("addAccount.php");
		} 
	
		else {
            $query = $mysqli->prepare("SELECT MAX(AccountID) AS maxID FROM Accounts");
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $accID = $result['maxID'] + 1;
			$insert = $mysqli->prepare("INSERT INTO Accounts (AccountID, UserID, AccountTypeID, InstitutionName, Balance, Currency) VALUES (?, ?, ?, ?, ?, ?)");
			$stmt1 = $insert->execute([$accID, $_POST['user'], $_POST['account'], $_POST['institution'], $_POST['balance'], $_POST['currency']]);
			if($stmt1){
				$_SESSION['message'] = "Account successfully added";
			} else{
				$_SESSION['message'] = "Could not add account";
			}
			redirect("addAccount.php");
		}
	} else {
		$_SESSION['message'] = "Must enter all information";
	}
}


    echo "<div class='row'>";
	echo "<label for='left-label' class='left inline'>";
	echo "<h3>Add Account</h3>";
    //form
	echo "<form action=addAccount.php method='post'>";
    //choose user
    echo "<p>User:<select name='user'>";
    echo "<option></option>";
    $userQuery = $mysqli->prepare("SELECT DISTINCT UserID, CONCAT(FName, ' ', LName) as UserName FROM Users ORDER BY UserID");
    $userQuery->execute();
    while ($row = $userQuery->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='".$row['UserID']."'>".$row['UserName']."</option>";
    }
    echo "</select></p>";
    //choose account type
    echo "<p>Account Type:<select name='account'>";
    echo "<option></option>";
    $typeQuery = $mysqli->prepare("SELECT AccountTypeID, TypeName FROM AccountTypes ORDER BY AccountTypeID");
    $typeQuery->execute();
    while ($typeRow = $typeQuery->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='".$typeRow['AccountTypeID']."'>".$typeRow['TypeName']."</option>";
    }
    echo "</select></p>";
    //input institution
	echo "<p>Institution Name:<input type='text' name='institution' value='' /></p>";
    //input balance
	echo "<p>Balance:<input type='number' step='0.01' name='balance' value='' /></p>";
    //input 
    echo "<p>Currency:<input type='text' name='currency' value='' /></p>";
	echo "<input type='submit' name='submit' class='tiny round button' value='Add Account'/>";
	echo "</form>";


    echo "<p><br /><br /><hr />";
	echo "<h3>Current Accounts</h3>";
        $account = $mysqli->prepare("SELECT AccountID, CONCAT(Fname,' ', LName) AS User, TypeName, InstitutionName, Balance, Currency FROM Users NATURAL JOIN Accounts NATURAL JOIN AccountTypes ORDER BY AccountID ASC");
        $stmt = $account->execute();
        if($stmt){
            echo "<div class='row'>";
            echo "<center>";
            echo "<table>";
            echo "<thead>";
            echo "<tr><td></td><td>User</td><td>Account</td><td>Institution</td><td>Balance</td><td>Currency</td>";
            echo "</thead>";
            echo "<tbody>";
            while($row = $account->fetch(PDO::FETCH_ASSOC)){
            echo "<tr>";
            echo "<td> &nbsp; <a href='deleteAccount.php?id=".urlencode($row['AccountID'])."' onclick='return confirm(\"Are you sure you want to delete?');\"> <img src='red_x_icon.jpg' width='15' height='15'> </a></td>";
            echo "<td>".$row['User']."</td>";
            echo "<td>".$row['TypeName']."</td>";
            echo "<td>".$row['InstitutionName']."</td>";
            echo "<td>".$row['Balance']."</td>";
            echo "<td>".$row['Currency']."</td>";
            echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</center>";
            echo "</div>";

        }

        echo "<br /><p>&laquo:<a href='readPM.php'>Back to Main Page</a>";
        echo "</div>";
        echo "</label>";
        new_footer("Personal Finance Management");
        Database::dbDisconnect($mysqli);

?>