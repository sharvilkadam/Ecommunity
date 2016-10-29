<?php			//get all the posts associated with the constituency
				//here the posts' feed algorithm is applied and accordingdly the posts are sent to the client
	
session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']))
{
	$u_email=$_SESSION['u_email'];
	$conn = dbConnect();
	
	$con_name=mysqli_real_escape_string($conn,$_GET['con_name']);
	$lat=mysqli_real_escape_string($conn,$_GET['lat']);
	$lon=mysqli_real_escape_string($conn,$_GET['lon']);
	if(isset($_GET['sort_by']))
		$sort_by=mysqli_real_escape_string($conn,$_GET['sort_by']);
	else
		$sort_by="none";
	
	 // order by timestamp
	$sql ="Select * from post_master where con_name='$con_name'";
	$result = mysqli_query($conn,$sql);
	$postsT=array();
	if (mysqli_num_rows($result) > 0) 
	{
		while ($row=mysqli_fetch_array($result)) {
					
					// successfully GET all task
					$posts1["p_id"] = $row['p_id'];
					$posts1["p_title"] = $row['p_title'];
					$posts1["p_desc"] = $row['p_desc'];
					$posts1["u_email"] = $row['u_email'];
					$posts1["u_name"] = $row['u_name'];
					$posts1["con_name"] = $row['con_name'];
					$plat=$row['p_lat'];
					$plon=$row['p_lon'];
					$posts1["p_lat"] = $plat;
					$posts1["p_lon"] = $plon;
					$posts1["upvotes"] = $row['upvotes'];
					$posts1["downvotes"] = $row['downvotes'];
					$posts1["category"] = $row['category'];
					$posts1["timestamp"] = $row['timestamp'];
					$posts1["no_of_comments"] = $row['no_of_comments'];
					$posts1["distance"]= getDistance($lat,$lon,$plat,$plon);
					// adding all to the response object
					array_push($postsT, $posts1);
					
		}
		//var_dump($postsT);
		
		//postsDIsts in the array sorted wrt to the nearest distance
		$postsDist=$postsT;
		//var_dump($postsDist);
		function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
			$sort_col = array();
			foreach ($arr as $key=> $row) {
				$sort_col[$key] = $row[$col];
			}

			array_multisort($sort_col, $dir, $arr);
		}


		array_sort_by_column($postsDist, 'distance');
		
		//var_dump($postsDist);
		
		
		
		 // order by upvotes
		$sql ="Select * from post_master where con_name='$con_name' order by upvotes desc";
		$result = mysqli_query($conn,$sql);
		$postsU=array();
		while ($row=mysqli_fetch_array($result)) {
					
					// successfully GET all task
					$posts2["p_id"] = $row['p_id'];
					$posts2["p_title"] = $row['p_title'];
					$posts2["p_desc"] = $row['p_desc'];
					$posts2["u_email"] = $row['u_email'];
					$posts2["u_name"] = $row['u_name'];
					$posts2["con_name"] = $row['con_name'];
					$posts2["p_lat"] = $row['p_lat'];
					$posts2["p_lon"] = $row['p_lon'];
					$posts2["upvotes"] = $row['upvotes'];
					$posts2["downvotes"] = $row['downvotes'];
					$posts2["category"] = $row['category'];
					$posts2["timestamp"] = $row['timestamp'];
					$posts2["no_of_comments"] = $row['no_of_comments'];
					$posts2["distance"]= getDistance($lat,$lon,$plat,$plon);
					// adding all to the response object
					array_push($postsU, $posts2);
					
		}
		//var_dump($postsU);
		
		 // order by downvotes
		$sql ="Select * from post_master where con_name='$con_name' order by downvotes desc";
		$result = mysqli_query($conn,$sql);
		$postsD=array();
		while ($row=mysqli_fetch_array($result)) {
					
					// successfully GET all task
					$posts3["p_id"] = $row['p_id'];
					$posts3["p_title"] = $row['p_title'];
					$posts3["p_desc"] = $row['p_desc'];
					$posts3["u_email"] = $row['u_email'];
					$posts3["u_name"] = $row['u_name'];
					$posts3["con_name"] = $row['con_name'];
					$posts3["p_lat"] = $row['p_lat'];
					$posts3["p_lon"] = $row['p_lon'];
					$posts3["upvotes"] = $row['upvotes'];
					$posts3["downvotes"] = $row['downvotes'];
					$posts3["category"] = $row['category'];
					$posts3["timestamp"] = $row['timestamp'];
					$posts3["no_of_comments"] = $row['no_of_comments'];
					$posts3["distance"]= getDistance($lat,$lon,$plat,$plon);
					// adding all to the response object
					array_push($postsD, $posts3);
					
		}
		//var_dump($postsD);
		
		 // order by noo of comments
		$sql ="Select * from post_master where con_name='$con_name' order by no_of_comments desc";
		$result = mysqli_query($conn,$sql);
		$postsC=array();
		while ($row=mysqli_fetch_array($result)) {
					
					// successfully GET all task
					$posts4["p_id"] = $row['p_id'];
					$posts4["p_title"] = $row['p_title'];
					$posts4["p_desc"] = $row['p_desc'];
					$posts4["u_email"] = $row['u_email'];
					$posts4["u_name"] = $row['u_name'];
					$posts4["con_name"] = $row['con_name'];
					$posts4["p_lat"] = $row['p_lat'];
					$posts4["p_lon"] = $row['p_lon'];
					$posts4["upvotes"] = $row['upvotes'];
					$posts4["downvotes"] = $row['downvotes'];
					$posts4["category"] = $row['category'];
					$posts4["timestamp"] = $row['timestamp'];
					$posts4["no_of_comments"] = $row['no_of_comments'];
					$posts4["distance"]= getDistance($lat,$lon,$plat,$plon);
					// adding all to the response object
					array_push($postsC, $posts4);
					
		}
		//var_dump($postsC);
		//echo "<br><br>";
		
		$response2["type"] = "getPosts";
		$response2["success"] = "true";
		$response2["message"] = "Posts getting accordin to the algo";
		$response2["posts"] = array();
		
		if($sort_by=="timestamp")
		{
			array_push($response2["posts"],$postsT);
		}
		else if($sort_by=="distance")
		{
			array_push($response2["posts"],$postsDist);
		}
		else if($sort_by=="upvotes")
		{
			array_push($response2["posts"],$postsU);
		}
		else if($sort_by=="downvotes")
		{
			array_push($response2["posts"],$postsD);
		}
		else if($sort_by=="no_of_comments")
		{
			array_push($response2["posts"],$postsC);
		}
		else
		//if($sort_by=="none")			//return for the posts feed
		{
			$response=array();
			$t=0;
			$d=0;
			$u=0;
			$c=0;
			$parray=array();
			
			for($i=0; $i<sizeof($postsC);$i++)
			{
				$rno=rand(1,4);
				if($rno==1)		//take from timestamp array 
				{
					for($j=$t;$j<sizeof($postsT);$j++)
					{
						$pid=$postsT[$j]['p_id'];
						if(!in_array($pid,$parray))
						{
							array_push($parray,$pid);
							array_push($response,$postsT[$j]);
							$t++;
							break;
						}
					}
					
					
				}else if($rno==2)		//take from distandce array 
				{
					for($j=$d;$j<sizeof($postsDist);$j++)
					{
						$pid=$postsDist[$j]['p_id'];
						if(!in_array($pid,$parray))
						{
							array_push($parray,$pid);
							array_push($response,$postsDist[$j]);
							$d++;
							break;
						}
					}
					
				}else if($rno==3)		//take from upvotes array 
				{
					for($j=$u;$j<sizeof($postsU);$j++)
					{
						$pid=$postsU[$j]['p_id'];
						if(!in_array($pid,$parray))
						{
							array_push($parray,$pid);
							array_push($response,$postsU[$j]);
							$u++;
							break;
						}
					}
					
					
				}else{			//take from no gf comments array 
					for($j=$c;$j<sizeof($postsC);$j++)
					{
						$pid=$postsC[$j]['p_id'];
						if(!in_array($pid,$parray))
						{
							array_push($parray,$pid);
							array_push($response,$postsC[$j]);
							$c++;
							break;
						}
					}
					
				}
				//echo "rno=".$rno." res=".sizeof($response)."<br>";
				//var_dump($response);
				//echo "<br><br>";
			}
			
			array_push($response2["posts"],$response);
		}
		
			
		//var_dump($response);
		
		
		echo json_encode($response2);
		
	}
	else
	{
		// no task found
			$response2["type"] = "getPosts";
			$response2["success"] = "false";
			$response2["message"] = "Sorry no Task Found";
	 
			// echoing JSON response
			echo json_encode($response2);
	}
	
	
}
else {
			// no login
			$response2["type"] = "getPosts";
			$response2["success"] = "false";
			$response2["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response2);
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