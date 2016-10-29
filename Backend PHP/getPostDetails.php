<?php 		//To get the details of a single post using pid

session_start();
include('db.php');

$response = array();

if (isset($_SESSION['u_email']) && isset($_GET['p_id'])){

	$conn = dbConnect();
	
	$p_id=mysqli_real_escape_string($conn,$_GET['p_id']);
	
	 // insert into the user_master table
	$fetch=mysqli_query($conn,"Select * from post_master where p_id='$p_id'");

	if($row = mysqli_fetch_array($fetch))
		{
			$response["type"] = "getPostDetails";
			$response["success"] = "true";
			$response["message"] = "Post Details getting.";
			$response["p_id"]=$row['p_id'];
			$response["u_email"]=$row['u_email'];
			$response["u_name"]=$row['u_name'];
			$response["con_name"]=$row['con_name'];
			$response["p_title"]=$row['p_title'];
			$response["p_desc"]=$row['p_desc'];
			$response["p_lat"]=$row['p_lat'];
			$response["p_lon"]=$row['p_lon'];
			$response["upvotes"]=$row['upvotes'];
			$response["downvotes"]=$row['downvotes'];
			$response["category"]=$row['category'];
			$response["p_type"]=$row['p_type'];
			

			// echoing JSON response
			echo json_encode($response);
		} else {
			// failed to login
			$response["type"] = "getPostDetails";
			$response["success"] = "false";
			$response["message"] = "Oops! Couldn't retreive details of the post";
	 
			// echoing JSON response
			echo json_encode($response);
		} 
	}	
	else {
			// required field is missing
			$response["type"] = "getPostDetails";
			$response["success"] = "false";
			$response["message"] = "Please login to access";
		 
			// echoing JSON response
			echo json_encode($response);
	}

?>