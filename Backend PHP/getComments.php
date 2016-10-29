<?php			//get all the comments for the post using the post id
	
session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']))
{
	$conn = dbConnect();
	$u_email=$_SESSION['u_email'];
	$pid=mysqli_real_escape_string($conn,$_GET['p_id']);	

	$gurl="https://api.mlab.com/api/1/databases/ecommunity/collections/comments?q={\"p_id\":\"$pid\"}&apiKey=$mongo_api_key";
	//echo $gurl;
	$result = file_get_contents($gurl);
	
	$ares=json_decode($result,true);
	//var_dump($ares);
	
	
	if(isset($ares))
	{
		$response["type"] = "getComments";
		$response["success"] = "true";
		$response["message"] = "Comment Getting";
		$response["p_id"] = $pid;
		$result2 = file_get_contents('https://api.mlab.com/api/1/databases/ecommunity/collections/comments?q={"p_id":"'.$pid.'"}&c=true&apiKey='.$mongo_api_key);
		$response["no_of_comments"] = $result2;
		
		$response["comments"] = array();
		foreach($ares as $com)
		{
			$response2["c_id"] = $com["_id"]["\$oid"];
			$response2["u_email"] = $com["u_email"];
			$response2["comment"] = $com["comment"];
			$response2["timestamp"] = $com["timestamp"];
			
			array_push($response["comments"],$response2);
		}
		
		echo json_encode($response);
		
	}
	else
	{
		// error in the mongo labs
			$response["type"] = "getComments";
			$response["success"] = "false";
			$response["message"] = "Sorry no comments present for this post";
	 
			// echoing JSON response
			echo json_encode($response);
	}
		
}
else {
			// no login
			$response["type"] = "getComments";
			$response["success"] = "false";
			$response["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response);
} 
?>