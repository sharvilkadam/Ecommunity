<?php			//update a comment to the mongoDB using the post_id, User_email and comment_id authorized to only the comment user and not the admin
	
session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']))
{
	$conn = dbConnect();
	$u_email=$_SESSION['u_email'];
	$pid=mysqli_real_escape_string($conn,$_GET['p_id']);
	$cid=mysqli_real_escape_string($conn,$_GET['c_id']);
	$comment=mysqli_real_escape_string($conn,$_GET['comment']);
	
	$uurl="https://api.mlab.com/api/1/databases/ecommunity/collections/comments/$cid?apiKey=$mongo_api_key";	//update URL
	
	$result0 = file_get_contents($uurl);
	$com=json_decode($result0,true);
	//var_dump($com);
	if($com["u_email"] == $u_email)		//then only authorized to edit the comment
	{
		$x=array();
		//$x["p_id"] = $pid;		//these two will be the same so no need to pass
		//$x["u_email"] = $u_email;
		$x["\$set"]["comment"] = $comment;			//{"$set":{"comment":"this is an updated comment"}}
		//$x["timestamp"] = date("Y-m-d H:i:s");	//not changing the timestamp after edit comment, so that the order remains the same
		$data=json_encode($x);
		
		$opts = array('http' =>
			array(
				'method'  => 'PUT',
				'header'  => 'Content-type: application/json',
				'content' => $data
			)
		);
		$context  = stream_context_create($opts);
		$result = file_get_contents($uurl, false, $context);

		$ares=json_decode($result,true);
		//var_dump($ares);
		if(isset($ares["_id"]))
		{
			$response["type"] = "updateComment";
			$response["success"] = "true";
			$response["message"] = "Comment Updated";
			$response["p_id"] = $pid;
			$response["c_id"] = $ares["_id"]["\$oid"];		//passing this so that the user or the admin can delete it on the client side using the _id
			
			echo json_encode($response);
			
		}
		else
		{
			// error in the mongo labs
				$response["type"] = "updateComment";
				$response["success"] = "false";
				$response["message"] = "update Failed";
		 
				// echoing JSON response
				echo json_encode($response);
		}
	}
	else
	{
			$response["type"] = "updateComment";
			$response["success"] = "false";
			$response["message"] = "Negative!! NOT UR comment!!";
	 
			// echoing JSON response
			echo json_encode($response);
	}
	
}
else {
			// no login
			$response["type"] = "updateComment";
			$response["success"] = "false";
			$response["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response);
} 
?>