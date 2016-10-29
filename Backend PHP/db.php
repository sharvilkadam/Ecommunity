<?php // db.php
 
date_default_timezone_set("Asia/Calcutta");

/*$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$db = "ecommunity";
*/
$dbhost = "mysql.hostinger.in";
$dbuser = "u862135098_user";
$dbpass = "Unlock@123";
$db = "u862135098_db";

$alchemy_api_key="fe793e9da4c6915a9f81a6d8d506dfbe15a6f96b";		//for the alchemy api


$mongo_api_key="kRsSwwr63rBd7FMea0KMOeaovzBawCik";			//for the mLabs Mongo DB Data API

function dbConnect() {
global $dbhost, $dbuser, $dbpass, $db;
 
$dbcnx = mysqli_connect($dbhost, $dbuser, $dbpass, $db)
or die("The site database appears to be down.");
 
//if ($db!="" and !mysql_select_db($db))
//die("The site database is unavailable.");
 
return $dbcnx;
}
?>