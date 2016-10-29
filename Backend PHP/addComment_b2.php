<?php			//add a comment to the mongoDB using the post_id, User_email
	
session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']))
{
	$conn = dbConnect();
	$u_email=$_SESSION['u_email'];
	$pid=mysqli_real_escape_string($conn,$_GET['p_id']);
	$comment=mysqli_real_escape_string($conn,$_GET['comment']);
	

	$x=array();
	$x["p_id"] = $pid;
	$x["u_email"] = $u_email;
	$x["comment"] = $comment;
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
	$result = file_get_contents('https://api.mlab.com/api/1/databases/ecommunity/collections/comments?apiKey='.$mongo_api_key, false, $context);

	$ares=json_decode($result,true);
	//var_dump($ares);
	
	if(isset($ares["_id"]))
	{
		$response["type"] = "addComment";
		$response["success"] = "true";
		$response["message"] = "Comment Added";
		$response["p_id"] = $pid;
		$cid= $ares["_id"]["\$oid"];
		$response["c_id"] = $cid;		//passing this so that the user or the admin can delete it on the client side using the _id
		
		$result2 = file_get_contents('https://api.mlab.com/api/1/databases/ecommunity/collections/comments?q={"p_id":"'.$pid.'"}&c=true&apiKey='.$mongo_api_key);
		$response["no_of_comments"] = $result2;
		$sql ="UPDATE post_master SET no_of_comments = '$result2' where p_id='$pid'";
		$result3 = mysqli_query($conn,$sql);
		
	
		
		echo json_encode($response);
		
	}
	else
	{
		// error in the mongo labs
			$response["type"] = "addComment";
			$response["success"] = "false";
			$response["message"] = "Sorry no comment added";
	 
			// echoing JSON response
			echo json_encode($response);
	}

}
else {
			// no login
			$response["type"] = "addComment";
			$response["success"] = "false";
			$response["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response);
} 
?>