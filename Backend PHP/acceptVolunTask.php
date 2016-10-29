<?php			//accpte the task assigned to the volunteer an dset status of the volun task to 1
				//call this directly if the volunteer chooses by himself on the volunteer portal
	
session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']))
{
	$conn = dbConnect();
	$v_email=$_SESSION['u_email'];
	$pid=mysqli_real_escape_string($conn,$_GET['p_id']);

	
	$uurl="https://api.mlab.com/api/1/databases/ecommunity/collections/volunTasks?q={\"v_email\":\"$v_email\",\"p_id\":\"$pid\"}&apiKey=$mongo_api_key";	//update URL
	$result0 = file_get_contents($uurl);		//get the comment user
	$vtask=json_decode($result0,true);
	//var_dump($vtask);
	//echo $vcom[0]["_id"]["\$oid"].'<br>';
	
	if(isset($vtask[0]["_id"]))		//email nad the post combination exit therefore accept n update the doc in mongo
	{
		$vtid=$vtask[0]["_id"]["\$oid"];
		$x=array();
		//$x["p_id"] = $pid;
		//$x["u_email"] = $u_email;
		//$x["v_email"] = $v_email;
		$x["\$set"]["status"] ="1";		//accept reject status 0=>rejected 1=>accepted -1=>not a/r
		//$x["timestamp"] = date("Y-m-d H:i:s");
		$data=json_encode($x);
		//echo $data;
		
		$opts = array('http' =>
			array(
				'method'  => 'PUT',
				'header'  => 'Content-type: application/json',
				'content' => $data
			)
		);
		$context  = stream_context_create($opts);
		$uurl1="https://api.mlab.com/api/1/databases/ecommunity/collections/volunTasks/$vtid?apiKey=$mongo_api_key";
		$result1 = file_get_contents($uurl1, false, $context);

		$ares=json_decode($result1,true);
		//var_dump($ares);
		
		if(isset($ares["_id"]))
		{
			$response["type"] = "acceptVolunTask";
			$response["success"] = "true";
			$response["message"] = "Volun task accepted ";
			
			echo json_encode($response);
			
		}
		else
		{
			// error in the mongo labs
				$response["type"] = "acceptVolunTask";
				$response["success"] = "false";
				$response["message"] = "Sorry not accepted.. Please try later";
		 
				// echoing JSON response
				echo json_encode($response);
		}
	}
	else{
		$response["type"] = "acceptVolunTask";
		$response["success"] = "false";
		$response["message"] = "NO such task assigned";
		
		// echoing JSON response
		echo json_encode($response);
	}

}
else {
			// no login
			$response["type"] = "acceptVolunTask";
			$response["success"] = "false";
			$response["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response);
} 
?>