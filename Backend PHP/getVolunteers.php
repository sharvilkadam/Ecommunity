<?php 		//get all the volunteers dynamically nearest to the user's u_lat and u_lon
		//also check of the task is already assigned to the volunteer in the mongoDB.. if it is dont send the volunteer again to the clients

session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']))
{
	$u_email=$_SESSION['u_email'];
	$conn = dbConnect();
	if (isset($_GET['u_lat']) && isset($_GET['u_lon']))
	{
		
		$ulat=mysqli_real_escape_string($conn,$_GET['u_lat']);
		$ulon=mysqli_real_escape_string($conn,$_GET['u_lon']);
		$radius=mysqli_real_escape_string($conn,$_GET['radius']);
		$pid=mysqli_real_escape_string($conn,$_GET['p_id']);
		
		//get the volunteers if the u_email has assigned a task (p_id) to the v_email for comparison of the volunteers below
		$gurl="https://api.mlab.com/api/1/databases/ecommunity/collections/volunTasks?q={\"p_id\":\"$pid\",\"u_email\":\"$u_email\"}&apiKey=$mongo_api_key";
		//echo $gurl;
		$result0 = file_get_contents($gurl);
		
		$ares0=json_decode($result0,true);
		//var_dump($ares0);
		//echo "<br>";
		$susers=array();
		foreach($ares0 as $ar)
		{
			//echo $ar["v_email"]."<br>";
			array_push($susers,$ar["v_email"]);
		}
		//var_dump($susers);		//susers contain all the volunteers who the user has already send the requests to the volunteers for the p_id and
						//should not be send to the client on the mp view
		
		
		$sql1 ="Select * from user_master where u_email='$u_email'";		//get the state for optimixation
		$result1= mysqli_query($conn,$sql1);		
		$row1=mysqli_fetch_array($result1);
		$ustate=$row1["state_name"];
		
		$sql ="Select * from user_master where state_name='$ustate' and isVolun=1";
		$result = mysqli_query($conn,$sql);
		if (mysqli_num_rows($result) > 0) {
				// looping through all results
				// products node
				$response["type"] = "getVolunteers";
				$response["success"] = "true";
				$response["message"] = "getting volunteers whos isVolun=1";
				$response["volunteers"] = array();
			while ($row=mysqli_fetch_array($result)) {
					
					$vlat= $row['v_lat'];
					$vlon= $row['v_lon'];
					if($radius > getDistance($ulat,$ulon,$vlat,$vlon) && !in_array($row['u_email'],$susers) && $row['u_email']!=$u_email)
					{
						// successfully GET all task
						$response2["u_email"] = $row['u_email'];
						$response2["v_lat"] = $row['v_lat'];
						$response2["v_lon"] = $row['v_lon'];
						$response2["u_name"] = $row['u_name'];
						$response2["isVolun"] = $row['isVolun'];
						
						// adding all to the response object
						array_push($response["volunteers"], $response2);
					}
				}
				echo json_encode($response);
		
		} else {
			// no volunteers found
			$response["type"] = "getVolunteers";
			$response["success"] = "false";
			$response["message"] = "Sorry no active Volunteers.. Try some other time";
	 
			// echoing JSON response
			echo json_encode($response);
		}  
		
		
	}
	else {
			// required field is missing
			$response["type"] = "getVolunteers";
			$response["success"] = "false";
			$response["message"] = "Required field(s) is missing";
		 
			// echoing JSON response
			echo json_encode($response);
	}
}
else {
		// failed to add task
		$response["type"] = "getVolunteers";
		$response["success"] = "false";
		$response["message"] = "Unauthorized !! Please Login to Continue";
 
		// echoing JSON response
		echo json_encode($response);
}


function getDistance($lat1,$lon1,$lat2,$lon2) 		//haversine's formaula to calculate the diastance betwen two lat-lon points in Kms
{				
	$R = 6371; 								// Radius of the earth in km
	$dLat = deg2rad($lat2-$lat1);  			// deg2rad available in php
	$dLon = deg2rad($lon2-$lon1); 
	$a = 	sin($dLat/2) * sin($dLat/2) +
			cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * 
			sin($dLon/2) * sin($dLon/2);

			
	$c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
	$d = $R * $c; 				//distance in kms returned
	return $d;
}
?>