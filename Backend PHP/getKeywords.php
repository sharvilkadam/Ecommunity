<?php			//get all the keyword from the p_title and p_desc from alchemy
//session_start();		//noneed of session here
include('db.php');

$response = array();
if(isset($_GET['p_title']) && isset($_GET['p_desc']))
{
	$conn = dbConnect();
	
	$p_title=$_GET['p_title'];
	$p_desc=$_GET['p_desc'];
	
	$input_str=$p_title." ".$p_desc;
	$text=str_replace(" ","%20",$input_str);
	
	$url="http://gateway-a.watsonplatform.net/calls/text/TextGetRankedKeywords?apikey=$alchemy_api_key&text=$text&outputMode=json&showSourceText=1";

	$alchemy = file_get_contents($url);
	
	//echo $alchemy;
	
	$ares = array();
	$ares = json_decode($alchemy,true);
	//print_r($ares);
	//echo "<br>";
	if($ares["status"] === "OK")
	{
		$response["type"] = "getKeywords";
		$response["success"] = "true";
		$response["message"] = "Got Keywords";
		$response["keywords"] = array();
		$arr_length = count($ares["keywords"]);
		for($i=0;$i<$arr_length;$i++)
		{
			// calculations
			//print_r($ares["keywords"][$i]["relevance"]);
			//print_r($ares["keywords"][$i]["text"]);
			$response2["text"] = $ares["keywords"][$i]["text"];
			$response2["relevance"] = $ares["keywords"][$i]["relevance"];
			array_push($response["keywords"], $response2);
		}
		echo json_encode($response);
		
	}else
	{
		$response["type"] = "getKeywords";
		$response["success"] = "false";
		$response["message"] = "Error from Alchemy API";
		echo json_encode($response);
	}

}
?>