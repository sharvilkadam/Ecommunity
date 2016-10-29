<?php			//call after adding a post to db in addPost.php to add the keywods and metaphones assocaited with it in the db
	
include('db.php');

$response = array();
if(isset($_GET['p_id']) )//&& isset($_GET['p_title']) && isset($_GET['p_desc']))
{
	$conn = dbConnect();
	$p_id=$_GET['p_id'];
	//$p_title=$_GET['p_title'];
	//$p_desc=$_GET['p_desc'];
	$fetch=mysqli_query($conn,"Select p_title,p_desc,category,con_name from post_master where p_id='$p_id'");		//retreive the post details from the post_master
	if($row = mysqli_fetch_array($fetch))
		{
			$p_title=$row['p_title'];
			$p_desc=$row['p_desc'];
			$con_name=$row['con_name'];
			$category=$row['category'];
		}
		
	$turl= "http://localhost/nlp/getKeywords.php?p_title=$p_title&p_desc=$p_desc";		//get keywords from the alchemy api
	$url=str_replace(" ","%20",$turl);
	
	$result = file_get_contents($url);
	//echo $result;
	$ares = array();
	$ares = json_decode($result,true);
	if($ares["success"] === "true")
	{
		$response["type"] = "addKeywords";
		$response["success"] = "true";
		$response["message"] = "Keywords Added";
		
		$arr_length = count($ares["keywords"]);
		for($i=0;$i<$arr_length;$i++)
		{
			//explode the individual keywords and insert each of the exploded key word with the relevance and its metaphone
			$exploded=explode(" ",$ares["keywords"][$i]["text"]);
			$relevance=$ares["keywords"][$i]["relevance"];
			foreach ($exploded as $ex) {
				$metaphone=metaphone($ex);
				$sql1 ="INSERT INTO post_keywords (p_id,keyword,metaphone,relevance,con_name,category) VALUES('$p_id','$ex','$metaphone','$relevance','$con_name','$category')";
				$result1 = mysqli_query($conn,$sql1);
				
			}
			// calculations
			//print_r($ares["keywords"][$i]["relevance"]);
			//print_r($ares["keywords"][$i]["text"]);
			
			
		}
		echo json_encode($response);
		
	}else
	{
		$response["type"] = "addKeywords";
		$response["success"] = "false";
		$response["message"] = "Error Couldn't add keywords. Please try again later";
		echo json_encode($response);
	}
	
}
?>
