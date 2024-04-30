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
	if((isset($_POST['name']) && $_POST['name'] !== "") && (isset($_POST['type']) && $_POST['type'] !== "") ){

		//Query database for this "new" username. Be sure to limit 1 in your SQL as there should only be one.
		$verify = $mysqli->prepare("SELECT CategoryID, Name FROM Categories NATURAL JOIN CategoryType WHERE (Name = ? AND TypeID = ?)");
		$verify->execute([$_POST['name'], $_POST['type']]);
		$count = $verify -> rowCount();
		$name = $_POST['name'];
		//If the username DOES exist in table, create a session message - "The username already exists"
		//Reidrect back to addLogin.php
		if($count != 0){
			$_SESSION['message'] = "$name already exists.";
			redirect("addCategory.php");
		} 
		//If the username DOES NOT exist in table, insert into table
		//Verify stmt successfully executes by using an if-statement
		// If successful, create a session message - "User successfully added"
		// If NOT successful, create a session message - "Could not add user"
		//In both cases, redirect back to addLogin.php
		else {
            $query = $mysqli->prepare("SELECT MAX(CategoryID) AS maxID FROM Categories");
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $catID = $result['maxID'] + 1;
			$insert = $mysqli->prepare("INSERT INTO Categories(CategoryID, Name, TypeID) VALUES (?, ?, ?)");
			$stmt1 = $insert->execute([$catID, $_POST['name'],$_POST['type']]);
			if($stmt1){
				$_SESSION['message'] = "Category successfully added";
			} else{
				$_SESSION['message'] = "Could not add category";
			}
			redirect("addCategory.php");
		}
	} else {
		$_SESSION['message'] = "Must enter all information";
	}
}
?>

    <div class='row'>
		<label for='left-label' class='left inline'>
		<h3>Add Category</h3>
	<form action="addCategory.php" method="post">
		<p>Category Name:<input type="text" name="name" value="" required/></p>
		<?php 
            $category = $mysqli->prepare("SELECT DISTINCT TypeID, TypeName FROM CategoryType");
            $check = $category->execute();
            echo "<p>Category Type:<select name='type'>";
            echo "<option></option>";
            while($cat = $category->fetch(PDO::FETCH_ASSOC)){
               echo "<option value='".$cat['TypeID']."'>".$cat['TypeName']."</option>";
            }
            echo "</select></p>";
        ?>
		<input type="submit" name="submit" class='tiny round button' value="Add Category" />
	</form>

        <p><br /><br /><hr />
		<h3>Current Category</h3>
        <?php
         
			$user = $mysqli->prepare("SELECT CategoryID, Name, TypeName FROM Categories NATURAL JOIN CategoryType ORDER BY CategoryID ASC");
			$stmt = $user->execute();
			if($stmt){
				echo "<div class='row'>";
				echo "<center>";
				echo "<table>";
				echo "<thead>";
				echo "<tr><td></td><td>Category Name</td><td>Type</td>";
				echo "</thead>";
				echo "<tbody>";
				while($row = $user->fetch(PDO::FETCH_ASSOC)){
				echo "<tr>";
				echo "<td> &nbsp; <a href='deleteCategory.php?id=".urlencode($row['CategoryID'])."' onclick='return confirm(\"Are you sure you want to delete?');\"> <img src='red_x_icon.jpg' width='15' height='15'> </a></td>";
				echo "<td>".$row['Name']."</td>";
                echo "<td>".$row['TypeName']."</td>";
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