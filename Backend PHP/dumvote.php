<?php
//session_id($_GET['session_id']);

@ini_set("output_buffering", "Off");
@ini_set('implicit_flush', 1);
@ini_set('zlib.output_compression', 0);
@ini_set('max_execution_time',0);
include('db.php');
$conn = dbConnect();
for($i=0;$i<50;$i++)
{
		$x=array();
		$x["u_email"] = "qwe";
		$x["p_id"] = "2";
		$x["p_vote"] = "1";
		$x["timestamp"] = date("Y-m-d H:i:s");
		$data=json_encode($x);
		//echo $data;
		$opts = array('http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/json',
				'content' => $data
			)
		);
		$context  = stream_context_create($opts);
		$result = file_get_contents('https://api.mlab.com/api/1/databases/ecommunity/collections/votePost?apiKey='.$mongo_api_key, false, $context);

		$ares=json_decode($result,true);
		//var_dump($ares);
		$sql0 ="INSERT INTO post_vote (u_email,p_id,p_vote,timestamp) VALUES('qwe','2','1',CURRENT_TIMESTAMP)";
		$result0 = mysqli_query($conn,$sql0);
		echo $i."<br>";
		flush();
		ob_flush();
		sleep(2);
}	
?>