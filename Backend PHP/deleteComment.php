<?php			//delete a comment to the mongoDB using the post_id, User_email and comment_id authorized to the admin of the post & the comment user
	
session_start();
include('db.php');
error_reporting(E_ERROR | E_PARSE);		//for supressing the warnings

$response = array();
if(isset($_SESSION['u_email']))
{
	$conn = dbConnect();
	$u_email=$_SESSION['u_email'];
	$pid=mysqli_real_escape_string($conn,$_GET['p_id']);
	$cid=mysqli_real_escape_string($conn,$_GET['c_id']);
	
	
	$sql ="select u_email from post_master where p_id='$pid'";		//get the admin/author of the post
	$result1 = mysqli_query($conn,$sql);
	if($row1=mysqli_fetch_array($result1))
		$admin=$row1["u_email"];
	else $admin="";
	
	$uurl="https://api.mlab.com/api/1/databases/ecommunity/collections/comments/$cid?apiKey=$mongo_api_key";	//update URL
	$result0 = file_get_contents($uurl);		//get the comment user
	$com=json_decode($result0,true);
	//var_dump($com);
	
	if($com["u_email"] == $u_email or $admin == $u_email)		//then only authorized to edit the comment
	{
		//$x=array();
		//$x["p_id"] = $pid;		//these two will be the same so no need to pass
		//$x["u_email"] = $u_email;
		//$data=json_encode($x);
		
		$opts = array('http' =>
			array(
				'method'  => 'DELETE',
				'header'  => 'Content-type: application/json'
				
			)
		);
		$context  = stream_context_create($opts);
		$result = file_get_contents($uurl, false, $context);

		$ares=json_decode($result,true);
		//var_dump($ares);
		
		if(isset($ares["_id"]))
		{
			$response["type"] = "deleteComment";
			$response["success"] = "true";
			$response["message"] = "Comment Deleted";
			$response["p_id"] = $pid;
			
			$result2 = file_get_contents('https://api.mlab.com/api/1/databases/ecommunity/collections/comments?q={"p_id":"'.$pid.'"}&c=true&apiKey='.$mongo_api_key);
			$response["no_of_comments"] = $result2;
			$sql ="UPDATE post_master SET no_of_comments = '$result2' where p_id='$pid'";
			$result3 = mysqli_query($conn,$sql);
			
			echo json_encode($response);
			
		}
		else
		{
			// error in the mongo labs
				$response["type"] = "deleteComment";
				$response["success"] = "false";
				$response["message"] = "delete Failed or Document doesn't exists";
		 
				// echoing JSON response
				echo json_encode($response);
		}
		
	}
	else
	{
			$response["type"] = "deleteComment";
			$response["success"] = "false";
			$response["message"] = "Negative!! NOT UR comment!!";
	 
			// echoing JSON response
			echo json_encode($response);
	}
	
}
else {
			// no login
			$response["type"] = "deleteComment";
			$response["success"] = "false";
			$response["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response);
} 
?>