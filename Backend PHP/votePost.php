 <?php			//voate a post by the user 
	
session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']) && isset($_GET['p_id']) && isset($_GET['p_vote']))	//vote=1 =>upvote  vote=0 =>downvote
{
	$u_email=$_SESSION['u_email'];
	$conn = dbConnect();
	
	$pid=mysqli_real_escape_string($conn,$_GET['p_id']);
	$pvote=mysqli_real_escape_string($conn,$_GET['p_vote']);
	
	$uurl="https://api.mlab.com/api/1/databases/ecommunity/collections/votePost?q={\"u_email\":\"$u_email\",\"p_id\":\"$pid\"}&apiKey=$mongo_api_key";	//update URL
	$result0 = file_get_contents($uurl);		//get the vote of the user
	$ares0=json_decode($result0,true);
	//var_dump($ares0);
	
	
	if(!isset($ares0[0]))		//email and the post combination doesnt exit ie not votes yet... therefore add 
	{
		$x=array();
		$x["u_email"] = $u_email;
		$x["p_id"] = $pid;
		$x["p_vote"] = $pvote;
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

		$sql0 ="INSERT INTO post_vote (u_email,p_id,p_vote,timestamp) VALUES('$u_email','$pid','$pvote',CURRENT_TIMESTAMP)";
		$result0 = mysqli_query($conn,$sql0);
		
		$ares=json_decode($result,true);
		
		$sqlg="select  count(*) total,
					sum(case when p_vote = '0' then 1 else 0 end) down,
					sum(case when p_vote = '1' then 1 else 0 end) up
				from post_vote
				where p_id='$pid'";
		$fetchg=mysqli_query($conn,$sqlg);		//retreive the not of upvotes and no of downvotes of the comments 
		if($row = mysqli_fetch_array($fetchg))
		{
			$no_of_upvotes=$row['up'];
			$no_of_downvotes=$row['down'];
			$total=$row['total'];
		}
		if($no_of_upvotes==null)
			$no_of_upvotes=0;
		if($no_of_downvotes==null)
			$no_of_downvotes=0;
		//var_dump($ares);
		
		$sqlu ="UPDATE post_master SET upvotes = '$no_of_upvotes', downvotes = '$no_of_downvotes' where p_id='$pid'";
		$result3u = mysqli_query($conn,$sqlu);
		
		$response["type"] = "votePost";
		$response["success"] = "true";
		$response["message"] = "Post Voted";
		$response["u_email"]=$u_email;
		$response["p_id"]=$pid;
		$response["p_vote"]=$pvote;
		$response["no_of_upvotes"] = $no_of_upvotes;
		$response["no_of_downvotes"] = $no_of_downvotes;
		// echoing JSON response
		echo json_encode($response);
	}
	else			//the combination exists ie the user already voted.. wants to change
	{
		$avote=$ares0[0]["p_vote"];
		$vpid=$ares0[0]["_id"]["\$oid"];
		if($avote === $pvote)				//same vote again
		{
			$response["type"] = "votePost";
			$response["success"] = "false";
			$response["message"] = "Already voted ";
			$response["p_vote"]=$pvote;
			// echoing JSON response
			echo json_encode($response);
		}
		else				//change of vote
		{
			$x=array();
			//$x["p_id"] = $pid;		//these two will be the same so no need to pass
			//$x["u_email"] = $u_email;
			$x["\$set"]["p_vote"] = $pvote;			//{"$set":{"comment":"this is an updated comment"}}
			$x["\$set"]["timestamp"] = date("Y-m-d H:i:s");	
			$data=json_encode($x);
			
			$opts = array('http' =>
				array(
					'method'  => 'PUT',
					'header'  => 'Content-type: application/json',
					'content' => $data
				)
			);
			$context  = stream_context_create($opts);
			$uurl="https://api.mlab.com/api/1/databases/ecommunity/collections/votePost/$vpid?apiKey=$mongo_api_key";
			$result1 = file_get_contents($uurl, false, $context);

			$ares=json_decode($result1,true);
			//var_dump($ares);
			
			$sql ="UPDATE post_vote SET p_vote = '$pvote', timestamp = CURRENT_TIMESTAMP where p_id='$pid' and u_email='$u_email'";
			$result3 = mysqli_query($conn,$sql);
			
			$sqlg="select  count(*) total,
					sum(case when p_vote = '0' then 1 else 0 end) down,
					sum(case when p_vote = '1' then 1 else 0 end) up
				from post_vote
				where p_id='$pid'";
			$fetchg=mysqli_query($conn,$sqlg);		//retreive the not of upvotes and no of downvotes of the comments 
			if($row = mysqli_fetch_array($fetchg))
			{
				$no_of_upvotes=$row['up'];
				$no_of_downvotes=$row['down'];
				$total=$row['total'];
			}
			if($no_of_upvotes==null)
				$no_of_upvotes=0;
			if($no_of_downvotes==null)
				$no_of_downvotes=0;
			
			echo $no_of_upvotes;
			$sqlu ="UPDATE post_master SET upvotes = '$no_of_upvotes', downvotes = '$no_of_downvotes' where p_id='$pid'";
			$result3u = mysqli_query($conn,$sqlu);
			
			$response["type"] = "votePost";
			$response["success"] = "true";
			$response["message"] = "Changed vote";
			$response["p_vote"]=$pvote;
			$response["u_email"]=$u_email;
			$response["p_id"]=$pid;
			$response["no_of_upvotes"] = $no_of_upvotes;
			$response["no_of_downvotes"] = $no_of_downvotes;
			// echoing JSON response
			echo json_encode($response);
		}
		
	}

}
else {
			// failed to add task
			$response["type"] = "votePost";
			$response["success"] = "false";
			$response["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response);
} 
?>