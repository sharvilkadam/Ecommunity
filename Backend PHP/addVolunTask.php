<?php			//add a task for the volunteer to the mongoDB after the user clicks a volunteer using the post_id, User_email
	
session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']))
{
	$conn = dbConnect();
	$u_email=$_SESSION['u_email'];
	$pid=mysqli_real_escape_string($conn,$_GET['p_id']);
	$v_email=mysqli_real_escape_string($conn,$_GET['v_email']);
	
	$uurl="https://api.mlab.com/api/1/databases/ecommunity/collections/volunTasks?q={\"v_email\":\"$v_email\",\"p_id\":\"$pid\"}&apiKey=$mongo_api_key";	//update URL
	$result0 = file_get_contents($uurl);		//get the comment user
	$vcom=json_decode($result0,true);
	//var_dump($vcom);
	//echo $vcom[0]["_id"]["\$oid"].'<br>';
	
	if(!isset($vcom[0]["_id"]))		//email nad the post combination doesnt exit therefore add
	{

		$x=array();
		$x["p_id"] = $pid;
		$x["u_email"] = $u_email;
		$x["v_email"] = $v_email;
		$x["timestamp"] = date("Y-m-d H:i:s");
		$data=json_encode($x);
		//echo $data;
		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/json',
				'content' => $data
			)
		);
		$context  = stream_context_create($opts);
		$result = file_get_contents('https://api.mlab.com/api/1/databases/ecommunity/collections/volunTasks?apiKey='.$mongo_api_key, false, $context);

		$ares=json_decode($result,true);
		//var_dump($ares);
		
		if(isset($ares["_id"]))
		{
			$response["type"] = "addVolunTask";
			$response["success"] = "true";
			$response["message"] = "Volun task Added and volunteer notified";
			$response["p_id"] = $pid;
			$response["vt_id"] = $ares["_id"]["\$oid"];		//passing this so that the user or the admin can delete it on the client side using the _id
			
			echo json_encode($response);
			
		}
		else
		{
			// error in the mongo labs
				$response["type"] = "addVolunTask";
				$response["success"] = "false";
				$response["message"] = "Sorry no task added";
		 
				// echoing JSON response
				echo json_encode($response);
		}
	}
	else{
		$response["type"] = "addVolunTask";
		$response["success"] = "false";
		$response["message"] = "Already notified the volunteer ";
		
		// echoing JSON response
		echo json_encode($response);
	}

}
else {
			// no login
			$response["type"] = "addVolunTask";
			$response["success"] = "false";
			$response["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response);
} 
?>