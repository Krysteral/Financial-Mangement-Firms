<?php 
require_once("session.php");
require_once("included_functions.php");
require_once("database.php");

new_header("Delete from PFM Users"); 
$mysqli = Database::dbConnect();
$mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

ini_set("display_errors", 1);
error_reporting(E_ALL);

if (($output = message()) !== null) {
    echo $output;
}

    $id = $_GET['id'];
    $delete = $mysqli -> prepare("DELETE FROM Users WHERE UserID =?");
    $stmt = $delete -> execute([$id]);

    if($stmt){
        $_SESSION['message'] = "Successfully deleted user";
    } else{
        $_SESSION['message'] = "Unable to delete user";
    }

    redirect("addUser.php");	
	
new_footer("Personal Finance Management");
Database::dbDisconnect($mysqli);
?>