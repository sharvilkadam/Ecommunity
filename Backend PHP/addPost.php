<?php			//add a post by the user 
	
session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']))
{
	$u_email=$_SESSION['u_email'];
	$u_name=$_SESSION['u_name'];
	$conn = dbConnect();
	//$u_email=$_GET['u_email'];
	$con_name=mysqli_real_escape_string($conn,$_GET['con_name']);
	$state_name=mysqli_real_escape_string($conn,$_GET['state_name']);
	$p_title=mysqli_real_escape_string($conn,$_GET['p_title']);
	$p_desc=mysqli_real_escape_string($conn,$_GET['p_desc']);
	$p_lat=mysqli_real_escape_string($conn,$_GET['p_lat']);
	$p_lon=mysqli_real_escape_string($conn,$_GET['p_lon']);
	///$p_upvotes=mysqli_real_escape_string($conn,$_GET['upvotes']);
	//$p_downvotes=mysqli_real_escape_string($conn,$_GET['downvotes']);
	$p_type=mysqli_real_escape_string($conn,$_GET['p_type']);
	
	 // insert into the post_master table
	$sql ="INSERT INTO post_master (u_email,u_name, con_name,state_name, p_title ,p_desc ,p_lat ,p_lon,p_type,timestamp) VALUES('$u_email','$u_name', '$con_name','$state_name', '$p_title','$p_desc','$p_lat','$p_lon','$p_type',CURRENT_TIMESTAMP)";
	$result = mysqli_query($conn,$sql);
	$id = mysqli_insert_id($conn);
	if ($result) {
			
			// successfully add post
			$response["type"] = "addPost";
			$response["success"] = "true";
			$response["message"] = "Post Added";
			$response["p_title"]=$p_title;
			$response["p_desc"]=$p_desc;
			$response["p_id"]=$id;
			$response["u_email"]=$u_email;
			$response["u_name"]=$u_name;
			//call the addkeywords api for similar posts
			$gurl="http://localhost/ecommunity/addKeywords.php?p_id=$id";
			$result = file_get_contents($gurl);
			$ares=json_decode($result,true);
			//var_dump($ares);
			$response["addKeywords"]=$ares["success"];
			// echoing JSON response
			echo json_encode($response);
		} else {
			// failed to add task
			$response["type"] = "addPost";
			$response["success"] = "false";
			$response["message"] = "Oops!Falied to add Post";
	 
			// echoing JSON response
			echo json_encode($response);
		} 
}
else {
			// failed to add task
			$response["type"] = "addPost";
			$response["success"] = "false";
			$response["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response);
} 
?>
