<?php 		//start the volun.. call this when the user toggle the volunteer button in the app
			//here isVOlun = 1
			//get the location first time and update the vlat and vlon and also the state_name for optimization

session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']))
{
	$u_email=$_SESSION['u_email'];
	$conn = dbConnect();
	$lat=mysqli_real_escape_string($conn,$_GET['lat']);
	$lon=mysqli_real_escape_string($conn,$_GET['lon']);
	
	$request = 'http://localhost/ecommunity/getConstituency.php?lat='.$lat.'&lon='.$lon; 
	$file_contents = file_get_contents($request);
	$json_decode = json_decode($file_contents,true);
	$state=$json_decode['st_name'];			//get the state for optimixation
	
		//get all the posts and the keywords from post_keywords with category=$category and con_name=$con_name
		$sql ="UPDATE user_master SET isVolun=1, v_lat='$lat', v_lon='$lon', state_name='$state' WHERE u_email='$u_email'";
		$result = mysqli_query($conn,$sql);
		//echo $result;
		if($result)
			{
				
				$response["type"] = "startVolun";
				$response["success"] = "true";
				$response["message"] = "VOlunteering started...isVolun=1";
				
				//$response["utype"]=$row["utype"];
				// echoing JSON response
				echo json_encode($response);
			} else {
				// failed to login
				$response["type"] = "startVolun";
				$response["success"] = "false";
				$response["message"] = "Oops!Not started";
		 
				// echoing JSON response
				echo json_encode($response);
			}	
	}	
}
else {
			// failed to add task
			$response["type"] = "startVolun";
			$response["success"] = "false";
			$response["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response);
}
?>