<?php
session_start();
include('db.php');

$conn=dbconnect();
$response = array();
if(isset($_SESSION['u_email']))
{
	$email=$_SESSION['u_email'];
	$session=mysql_query("SELECT contact FROM user WHERE email='$email' ");
	$row=mysql_fetch_array($session);
	$login_session=$row['u_email'];
	if(!isset($login_session))			//if logout
	{
		$response["type"] = "session";
		$response["success"] = false;
		$response["message"] = "Please LOGIN again..";
		
		// echoing JSON response
		echo json_encode($response);
	}
	else{				//if logged in
		$response["type"] = "session";
		$response["success"] = true;
		//$response["message"] = "Please LOGIN again..";
		
		// echoing JSON response
		echo json_encode($response);
	}
}
else			//if not logged in i.e. no session variable exists
{
	$response["type"] = "session";
	$response["success"] = false;
	$response["message"] = "PLease login to continue";
	
	// echoing JSON response
	echo json_encode($response);
}
?>