<?php 
require_once("session.php");
require_once("included_functions.php");
require_once("database.php");

new_header("Delete from PFM Category"); 
$mysqli = Database::dbConnect();
$mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

ini_set("display_errors", 1);
error_reporting(E_ALL);

if (($output = message()) !== null) {
    echo $output;
}

    $id = $_GET['id'];
    $delete = $mysqli -> prepare("DELETE FROM Categories WHERE CategoryID =?");
    $stmt = $delete -> execute([$id]);

    if($stmt){
        $_SESSION['message'] = "Successfully deleted category";
    } else{
        $_SESSION['message'] = "Unable to delete category";
    }

    redirect("addCategory.php");	
	
new_footer("Personal Finance Management");
Database::dbDisconnect($mysqli);
?>