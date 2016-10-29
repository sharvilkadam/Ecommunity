<?php 		//this is to dynamically update the location of the vouneteer 
		//this api will continously get call from the client side to update the location with the lat and lon
		//this is the for the user(volun) .. if isVolun is 1 the the l;ocation will be dynamically updated
				//that means that the volunteer in available for the same

session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']))
{
	$u_email=$_SESSION['u_email'];
	$conn = dbConnect();
	if (isset($_GET['v_lat']) && isset($_GET['v_lon'])){
		
		$vlat=mysqli_real_escape_string($conn,$_GET['v_lat']);
		$vlon=mysqli_real_escape_string($conn,$_GET['v_lon']);
		
		//get all the posts and the keywords from post_keywords with category=$category and con_name=$con_name
		$sql ="UPDATE user_master SET v_lat='$vlat',v_lon='$vlon' WHERE u_email='$u_email'";
		$result = mysqli_query($conn,$sql);
		//echo $result;
		if($result)
			{
				
				$response["type"] = "updateVolunLocation";
				$response["success"] = "true";
				$response["message"] = "Location Updated";
				
				//$response["utype"]=$row["utype"];
				// echoing JSON response
				echo json_encode($response);
			} else {
				// failed to login
				$response["type"] = "updateVolunLocation";
				$response["success"] = "false";
				$response["message"] = "Oops!Not updated";
		 
				// echoing JSON response
				echo json_encode($response);
			} 
		
		
	}
	else {
			// required field is missing
			$response["type"] = "updateVolunLocation";
			$response["success"] = "false";
			$response["message"] = "Required field(s) is missing";
		 
			// echoing JSON response
			echo json_encode($response);
	}
}
else {
			// failed to add task
			$response["type"] = "updateVolunLocation";
			$response["success"] = "false";
			$response["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response);
}
?>