<?php 
require_once("session.php");
require_once("included_functions.php");
require_once("database.php");

new_header("Delete from PFM Accounts"); 
$mysqli = Database::dbConnect();
$mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

ini_set("display_errors", 1);
error_reporting(E_ALL);

if (($output = message()) !== null) {
    echo $output;
}

    $id = $_GET['id'];
    $delete = $mysqli -> prepare("DELETE FROM Accounts WHERE AccountID =?");
    $stmt = $delete -> execute([$id]);

    if($stmt){
        $_SESSION['message'] = "Successfully deleted account";
    } else{
        $_SESSION['message'] = "Unable to delete account";
    }

    redirect("addAccount.php");	
	
new_footer("Personal Finance Management");
Database::dbDisconnect($mysqli);
?>