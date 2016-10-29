<?php			//get all the tasks assigned for the volunteer using the vemail
	
session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']))
{
	$conn = dbConnect();
	$u_email=$_SESSION['u_email'];
	
	$gurl="https://api.mlab.com/api/1/databases/ecommunity/collections/volunTasks?q={\"v_email\":\"$u_email\"}&apiKey=$mongo_api_key";
	//echo $gurl;
	$result = file_get_contents($gurl);
	
	$ares=json_decode($result,true);
	//var_dump($ares);
	
	
	if(isset($ares))
	{
		$response["type"] = "getVolunTasks";
		$response["success"] = "true";
		$response["message"] = "Volunteer Tasks Getting";
		$result2 = file_get_contents('https://api.mlab.com/api/1/databases/ecommunity/collections/volunTasks?q={"v_email":"'.$u_email.'"}&c=true&apiKey='.$mongo_api_key);
		$response["no_of_tasks"] = $result2;
		
		$response["tasks"] = array();
		foreach($ares as $com)
		{
			$response2["vt_id"] = $com["_id"]["\$oid"];
			$response2["p_id"] = $com["p_id"];
			$response2["status"] = $com["status"];
			$vpid=$com["p_id"];
			$sql3 ="Select * from post_master where p_id=$vpid";
			$result3 = mysqli_query($conn,$sql3);
			$row3=mysqli_fetch_array($result3);
			$response2["p_title"] = $row3["p_title"];
			$response2["p_desc"] = $row3["p_desc"];
			$response2["p_lat"] = $row3["p_lat"];
			$response2["p_lon"] = $row3["p_lon"];
			$response2["u_email"] = $com["u_email"];
			$response2["timestamp"] = $com["timestamp"];
			
			array_push($response["tasks"],$response2);
		}
		
		echo json_encode($response);
		
	}
	else
	{
		// error in the mongo labs
			$response["type"] = "getVolunTasks";
			$response["success"] = "false";
			$response["message"] = "Sorry no Tasks present for this Volunteer";
	 
			// echoing JSON response
			echo json_encode($response);
	}

}
else {
			// no login
			$response["type"] = "getVolunTasks";
			$response["success"] = "false";
			$response["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response);
} 
?>