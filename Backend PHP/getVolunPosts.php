<?php			//get all the posts to show to the vounteers based on the location of the volunteer and the radius
	
session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']))
{
	$u_email=$_SESSION['u_email'];
	$conn = dbConnect();
			
	$lat=mysqli_real_escape_string($conn,$_GET['lat']);
	$lon=mysqli_real_escape_string($conn,$_GET['lon']);
	$radius=mysqli_real_escape_string($conn,$_GET['radius']);	//radius taken from client side in kms
	
	//get all the post assigned to the volunteer(v_email) thus dont pass these post to the volunteer on the map in volunteer portal
	$uurl="https://api.mlab.com/api/1/databases/ecommunity/collections/volunTasks?q={\"v_email\":\"$u_email\"}&apiKey=$mongo_api_key";	//update URL
	$result0 = file_get_contents($uurl);		//get the comment user
	$vcom=json_decode($result0,true);
	//var_dump($vcom);
	//echo "<Br>";
	$aposts=array();	//array containing all the posts already assigned to the volunteer
	foreach($vcom as $r)
	{
		array_push($aposts,$r["p_id"]);
	}
	//var_dump($aposts);
	
	$request = 'http://localhost/ecommunity/getConstituency.php?lat='.$lat.'&lon='.$lon; 
	$file_contents = file_get_contents($request);
	$json_decode = json_decode($file_contents,true);
	$state=$json_decode['st_name'];			//get the state for optimixation
	
	 // select into the post_master table
	$sql ="Select * from post_master where state_name='$state' and needVolun=1";
	$result = mysqli_query($conn,$sql);
	if (mysqli_num_rows($result) > 0) {
			// looping through all results
			// products node
			$response["type"] = "getVolunPosts";
			$response["success"] = "true";
			$response["message"] = "getting posts needing volunteering";
			$response["posts"] = array();
		while ($row=mysqli_fetch_array($result)) {
				
				$plat= $row['p_lat'];
				$plon= $row['p_lon'];
				if($radius > getDistance($lat,$lon,$plat,$plon) and !in_array($row["p_id"],$aposts) and $row['u_email']!=$u_email)
				{
					// successfully GET all task
					$response2["p_id"] = $row['p_id'];
					$response2["p_title"] = $row['p_title'];
					$response2["p_desc"] = $row['p_desc'];
					$response2["con_name"] = $row['con_name'];
					$response2["p_lat"] = $row['p_lat'];
					$response2["p_lon"] = $row['p_lon'];
					$response2["p_type"] = $row['p_type'];
					$response2["u_email"] = $row['u_email'];
					
					// adding all to the response object
					array_push($response["posts"], $response2);
				}
			}
			echo json_encode($response);
		
		} else {
			// no task found
			$response["type"] = "getVolunPosts";
			$response["success"] = "false";
			$response["message"] = "Sorry no posts Found needing volunteering";
	 
			// echoing JSON response
			echo json_encode($response);
		} 
		
}
else {
			// no login
			$response["type"] = "getVolunPosts";
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