<?php			//add a task for the volunteer to the mongoDB after the volunteer himself click on a post in the map view of the volun portal
	
session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']))
{
	$conn = dbConnect();
	$v_email=$_SESSION['u_email'];
	$pid=mysqli_real_escape_string($conn,$_GET['p_id']);
	
	$sql ="Select u_email from post_master where p_id='$pid'";
	$result3 = mysqli_query($conn,$sql);
	if($row = mysqli_fetch_array($result3))
		$u_email=$row['u_email'];
	
	
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
		$x["status"] ="-1";		//accept reject status 0=>rejected 1=>accepted -1=>not a/r
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
			$response["message"] = "Volun task Added .. THanks for VOlunteering";
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
		$response["message"] = "Already accepted the task ";
		
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