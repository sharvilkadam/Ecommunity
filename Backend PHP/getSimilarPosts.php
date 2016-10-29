<?php			//call after creating the post on the client and passing the p_title p_desc and the category and the con_name for comparing
	
include('db.php');

$response = array();
if(isset($_GET['p_title']) && isset($_GET['p_desc']) && isset($_GET['con_name']) && isset($_GET['category']))
{
	$conn = dbConnect();
	$p_title=$_GET['p_title'];
	$p_desc=$_GET['p_desc'];
	$con_name=$_GET['con_name'];
	$category=$_GET['category'];
	
	//get all the posts and the keywords from post_keywords with category=$category and con_name=$con_name
	$sql ="Select p_id,keyword,metaphone,relevance from post_keywords where con_name='$con_name' and category='$category'";
	$result = mysqli_query($conn,$sql);
	$rkeywords=array();
	$temp=0;
	/*
	while ($row=mysqli_fetch_array($result)) {
		if($temp == $row['p_id'])
		{
			$response3["keyword"] = $row['keyword'];
			$response3["metaphone"] = $row['metaphone'];
			$response3["relevance"] = $row['relevance'];
			array_push($response2["keywords"], $response3);
			//array_push($rkeywords, $response2);
		}
		else
		{
			if($temp !==0)
				array_push($rkeywords, $response2);
			$temp=$row['p_id'];
			$response2["p_id"] = $row['p_id'];
			$response2["keywords"] = array();
			$response3["keyword"] = $row['keyword'];
			$response3["metaphone"] = $row['metaphone'];
			$response3["relevance"] = $row['relevance'];
			array_push($response2["keywords"], $response3);
		}
		
	}	
	array_push($rkeywords, $response2);
	//print_r($rkeywords);
	*/
	while ($row=mysqli_fetch_array($result)) {
		if($temp == $row['p_id'])
		{
			
			
			//$response2["keywords"] = array();
			//$response3["keyword"] = $row['keyword'];
			$response3 = $row['metaphone'];
			//$response3["relevance"] = $row['relevance'];
			//array_push($response2["keywords"], $response3);
			//array_push($rkeywords, $response2);
		}
		else
		{
			if($temp !==0)
				array_push($rkeywords, $response2);
			$temp=$row['p_id'];
			$response2["p_id"] = $row['p_id'];
			$response2["keywords"] = array();
			//$response3["keyword"] = $row['keyword'];
			$response3 = $row['metaphone'];
			//$response3["relevance"] = $row['relevance'];
			//array_push($response2["keywords"], $response3);
		}
		array_push($response2["keywords"], $response3);
		
	}	
	array_push($rkeywords, $response2);
	//print_r($rkeywords);
	
	
	$turl= "http://localhost/nlp/getKeywords.php?p_title=$p_title&p_desc=$p_desc";		//get keywords from the alchemy api
	$url=str_replace(" ","%20",$turl);
	
	$result = file_get_contents($url);		//$result contains the keywords JSON
	//echo $result;
	$ares = array();
	$ares = json_decode($result,true);
	if($ares["success"] === "true")
	{
		$response["type"] = "getSimilarPosts";
		$response["success"] = "true";
		$response["message"] = "Similar Posts getting";
		$response["sposts"] = array();
		
		$arr_length = count($ares["keywords"]);
		$parray=array();
		for($i=0;$i<$arr_length;$i++)
		{
			//explode the individual keywords and compare each of the exploded key word,relevance and its metaphone with all the in post_keywords
			$exploded=explode(" ",$ares["keywords"][$i]["text"]);
			$relevance=$ares["keywords"][$i]["relevance"];
			foreach ($exploded as $ex) {
				$metaphone=metaphone($ex);		//calculate metaphone
				//campare the keyword with all the keywords in the $rkeywords array
				foreach ($rkeywords as $rk) {
					$p_id=$rk["p_id"];
					
					if (in_array($metaphone, $rk["keywords"]))
					{
						if (array_key_exists($p_id,$parray))
							$parray["$p_id"]+=1;
						else
							$parray["$p_id"]=1;
					}
					
				
				}
				
					
				
			}
			// calculations
			//print_r($ares["keywords"][$i]["relevance"]);
			//print_r($ares["keywords"][$i]["text"]);	
		}
		//print_r($parray);
		foreach ($parray as $key => $value) 		//key=p_id and value=the no of metaphones in common
		{
			$sql1 ="Select * from post_master where p_id='$key'";
			$result1 = mysqli_query($conn,$sql1);
			while ($row=mysqli_fetch_array($result1)) {
				$response4["p_id"] = $row['p_id'];
				$response4["p_title"] = $row['p_title'];
				$response4["p_desc"] = $row['p_desc'];
				$response4["u_email"] = $row['u_email'];
				$response4["u_name"] = $row['u_name'];
				$response4["con_name"] = $row['con_name'];
				$response4["p_lat"] = $row['p_lat'];
				$response4["p_lon"] = $row['p_lon'];
				$response4["upvotes"] = $row['upvotes'];
				$response4["downvotes"] = $row['downvotes'];
				//$response4["category"] = $row['category'];
				array_push($response["sposts"], $response4);
			}
		}
		
		echo json_encode($response);
		
	}else
	{
		$response["type"] = "getSimilarPosts";
		$response["success"] = "false";
		$response["message"] = "Couldn't retreive similar posts.. Sorry ;-(";
		echo json_encode($response);
	}


}
?>
