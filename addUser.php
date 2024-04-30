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
	if((isset($_POST['fname']) && $_POST['fname'] !== "") && (isset($_POST['lname']) && $_POST['lname'] !== "") && (isset($_POST['email']) && $_POST['email'] !== "") && (isset($_POST['password']) && $_POST['password'] !== "")){
	    //Grab posted values for username and password, encrypting the password
		//so that it is set up to compare with the encrypted password in the database
		//Use password_encrypt
		$username = $_POST['email'];
		$password = $_POST['password'];
		$hash = password_encrypt($password);
		
		//Query database for this "new" username. Be sure to limit 1 in your SQL as there should only be one.
		$verify = $mysqli->prepare("SELECT Email FROM Users WHERE Email = ?");
		$verify->execute([$username]);
		$count = $verify -> rowCount();
		
		
		//If the username DOES exist in table, create a session message - "The username already exists"
		//Reidrect back to addLogin.php
		if($count != 0){
			$_SESSION['message'] = "The User's account already exists.";
			redirect("addUser.php");
		} 
		//If the username DOES NOT exist in table, insert into table
		//Verify stmt successfully executes by using an if-statement
		// If successful, create a session message - "User successfully added"
		// If NOT successful, create a session message - "Could not add user"
		//In both cases, redirect back to addLogin.php
		else {
            $query = $mysqli->prepare("SELECT MAX(UserID) AS maxID FROM Users");
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $userID = $result['maxID'] + 1;
			$insert = $mysqli->prepare("INSERT INTO Users(UserID, FName, LName, Email, PasswordHash, RegistrationDate) VALUES (?, ?, ?, ?, ?, NOW())");
			$stmt1 = $insert->execute([$userID, $_POST['fname'], $_POST['lname'], $_POST['email'], $hash]);
			if($stmt1){
				$_SESSION['message'] = "User successfully added";
			} else{
				$_SESSION['message'] = "Could not add user";
			}
			redirect("addUser.php");
		}
	} else {
		$_SESSION['message'] = "Must enter all information";
	}
}
?>

    <div class='row'>
		<label for='left-label' class='left inline'>
		<h3>Add User</h3>
	<form action="addUser.php" method="post">
		<p>User's First Name:<input type="text" name="fname" value="" /></p>
		<p>User's Last Name:<input type="text" name="lname" value="" /></p>
        <p>User's Email:<input type="email" name="email" value="" /></p>
        <p>Desired Password:<input type="password" name="password" value="" /></p>
		<input type="submit" name="submit" class="tiny round button" value="Add User" />
	</form>

        <p><br /><br /><hr />
		<h3>Current Users</h3>
        <?php
         
			$user = $mysqli->prepare("SELECT UserID, CONCAT(Fname,' ', LName) AS User, Email, RegistrationDate FROM Users ORDER BY UserID ASC");
			$stmt = $user->execute();
			if($stmt){
				echo "<div class='row'>";
				echo "<center>";
				echo "<table>";
				echo "<thead>";
				echo "<tr><td></td><td>Name</td><td>Email</td><td>RegistrationDate</td>";
				echo "</thead>";
				echo "<tbody>";
				while($row = $user->fetch(PDO::FETCH_ASSOC)){
				echo "<tr>";
				echo "<td> &nbsp; <a href='deleteUser.php?id=".urlencode($row['UserID'])."' onclick='return confirm(\"Are you sure you want to delete?');\"> <img src='red_x_icon.jpg' width='15' height='15'> </a></td>";
				echo "<td>".$row['User']."</td>";
                echo "<td>".$row['Email']."</td>";
                echo "<td>".$row['RegistrationDate']."</td>";
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