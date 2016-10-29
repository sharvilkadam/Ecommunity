<?php			//updates a post by the user 
	
session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']) && isset($_GET['p_id']))
{
	$u_email=$_SESSION['u_email'];
	$conn = dbConnect();
	//$u_email=$_GET['u_email'];
	$p_id=mysqli_real_escape_string($conn,$_GET['p_id']);
	$p_title=mysqli_real_escape_string($conn,$_GET['p_title']);
	$p_desc=mysqli_real_escape_string($conn,$_GET['p_desc']);
	//$p_lat=mysqli_real_escape_string($conn,$_GET['p_lat']);
	//$p_lon=mysqli_real_escape_string($conn,$_GET['p_lon']);
	///$p_upvotes=mysqli_real_escape_string($conn,$_GET['upvotes']);
	//$p_downvotes=mysqli_real_escape_string($conn,$_GET['downvotes']);
	//$p_type=mysqli_real_escape_string($conn,$_GET['p_type']);
	
	 // insert into the post_master table
	$sql ="UPDATE post_master SET p_title='$p_title',p_desc='$p_desc' where p_id='$p_id'";
	$result = mysqli_query($conn,$sql);
	
	if ($result) {
			
			// successfully add post
			$response["type"] = "updatePost";
			$response["success"] = "true";
			$response["message"] = "Post Updated";
			$response["p_title"]=$p_title;
			$response["p_id"]=$p_id;
			// echoing JSON response
			echo json_encode($response);
		} else {
			// failed to add task
			$response["type"] = "updatePost";
			$response["success"] = "false";
			$response["message"] = "Oops!Falied to update Post";
	 
			// echoing JSON response
			echo json_encode($response);
		} 
}
else {
			// failed to add task
			$response["type"] = "updatePost";
			$response["success"] = "false";
			$response["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response);
} 
?>