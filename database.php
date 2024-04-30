<?php
class Database {
  public function __construct() {
    die('Init function error');
  }

  public static function dbConnect() {
  require_once("/home/tgiang/DBgiang.php");
	
  $mysqli = null;
	//try connecting to your database
  try {
    $mysqli = new PDO('mysql:host='.DBHOST.';dbname='.DBNAME, USERNAME, PASSWORD);
    echo "Succesful Connection";
  }

  //catch a potential error, if unable to connect
  catch (PDOException $e)  {
    echo "Could not connect";
    die($e->getMessage());
  }   
    return $mysqli;
  }

  public static function dbDisconnect() {
    $mysqli = null;
  }
}
?>