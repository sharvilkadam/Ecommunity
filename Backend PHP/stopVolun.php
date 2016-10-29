<?php 		//STOP the volun.. call this when the user toggle the volunteer button in the app to stop/user closes the app/logs off
			//here isVOlun = 0

session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']))
{
	$u_email=$_SESSION['u_email'];
	$conn = dbConnect();
	
		//get all the posts and the keywords from post_keywords with category=$category and con_name=$con_name
		$sql ="UPDATE user_master SET isVolun=0 WHERE u_email='$u_email'";
		$result = mysqli_query($conn,$sql);
		//echo $result;
		if($result)
			{
				
				$response["type"] = "stopVolun";
				$response["success"] = "true";
				$response["message"] = "VOlunteering stoped..isVolun=0";
				
				//$response["utype"]=$row["utype"];
				// echoing JSON response
				echo json_encode($response);
			} else {
				// failed to login
				$response["type"] = "stopVolun";
				$response["success"] = "false";
				$response["message"] = "Oops!Not stoped";
		 
				// echoing JSON response
				echo json_encode($response);
			}	
	}	
}
else {
			// failed to add task
			$response["type"] = "stopVolun";
			$response["success"] = "false";
			$response["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response);
}
?>