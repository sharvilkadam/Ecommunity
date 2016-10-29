<?php			//get all the taxo from the p_title and p_desc from alchemy
//session_start();		//noneed of session here
include('db.php');

error_reporting(0);

$response = array();
if(isset($_GET['p_title']) && isset($_GET['p_desc']))
{
	$conn = dbConnect();
	
	$p_title=$_GET['p_title'];
	$p_desc=$_GET['p_desc'];
	
	$input_str=$p_title." ".$p_desc;
	$text=str_replace(" ","%20",$input_str);
	
	$url="http://gateway-a.watsonplatform.net/calls/text/TextGetRankedTaxonomy?apikey=$alchemy_api_key&text=$text&outputMode=json&showSourceText=1";

	$alchemy = file_get_contents($url);
	
	//echo $alchemy;
	
	$ares = array();
	$ares = json_decode($alchemy,true);
	//var_dump($ares);
	//echo "<br>";
	
	if($ares["status"] === "OK")
	{
		$response["type"] = "getTaxo";
		$response["success"] = "true";
		$response["message"] = "Got Taxonomy";
		$response["taxonomy"] = array();
		$arr_length = count($ares["taxonomy"]);
		for($i=0;$i<$arr_length;$i++)
		{
			// calculations
			//print_r($ares["keywords"][$i]["relevance"]);
			//print_r($ares["keywords"][$i]["text"]);
			$response2["label"] = $ares["taxonomy"][$i]["label"];
			$response2["score"] = $ares["taxonomy"][$i]["score"];
			if($ares["taxonomy"][$i]["confident"])
				$response2["confident"]="no";
			else
				$response2["confident"]="yes";
			array_push($response["taxonomy"], $response2);
		}
		echo json_encode($response);
		
	}else
	{
		$response["type"] = "getTaxo";
		$response["success"] = "false";
		$response["message"] = "Error from Alchemy API";
		echo json_encode($response);
	}
	
}
?>