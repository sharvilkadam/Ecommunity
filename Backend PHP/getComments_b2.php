<?php			//get all the comments for the post using the post id from 'comments' and also the votes of the comments from 'voteComments'
				//also get the no of upvates and downvotes for each comments
				
						//for flushing the bufer output and printing the output one at a time for each iteration
@ini_set("output_buffering", "Off");
@ini_set('implicit_flush', 1);
@ini_set('zlib.output_compression', 0);
@ini_set('max_execution_time',0);

session_start();
include('db.php');


$response = array();
if(isset($_SESSION['u_email']))
{
	$conn = dbConnect();
	$u_email=$_SESSION['u_email'];
	$pid=mysqli_real_escape_string($conn,$_GET['p_id']);	

	//$ares=0;
	$gurl="https://api.mlab.com/api/1/databases/ecommunity/collections/comments?q={\"p_id\":\"$pid\"}&s={\"timestamp\":1}&apiKey=$mongo_api_key";
	$result = file_get_contents($gurl);
	$ares=json_decode($result,true);
	//var_dump($ares);
	
	
	if(!empty($ares))
	{
		$response["type"] = "getComments";
		$response["success"] = "true";
		$response["message"] = "Comment Getting";
		$response["p_id"] = $pid;
		$result2 = file_get_contents('https://api.mlab.com/api/1/databases/ecommunity/collections/comments?q={"p_id":"'.$pid.'"}&c=true&apiKey='.$mongo_api_key);
		$response["no_of_comments"] = $result2;
		
		$response["comments"] = array();
		foreach($ares as $com)
		{
			$cid = $com["_id"]["\$oid"];
			
			$no_of_upvotes="0";		//intialize in case the mongoDB fails
			$no_of_downvotes="0";
			$cvote="-1";
			/*
			$uurl="https://api.mlab.com/api/1/databases/ecommunity/collections/voteComment?q={\"c_id\":\"$cid\",\"p_id\":\"$pid\",\"c_vote\":\"1\"}&c=true&apiKey=$mongo_api_key";	//update URL
			$result0 = file_get_contents($uurl);		//get the no of upvotes
			$no_of_upvotes=$result0;
			
			$uurl1="https://api.mlab.com/api/1/databases/ecommunity/collections/voteComment?q={\"c_id\":\"$cid\",\"p_id\":\"$pid\",\"c_vote\":\"0\"}&c=true&apiKey=$mongo_api_key";	//update URL
			$result1 = file_get_contents($uurl1);		//get the no of upvotes
			$no_of_downvotes=$result1;
			
			$uurl2="https://api.mlab.com/api/1/databases/ecommunity/collections/voteComment?q={\"u_email\":\"$u_email\",\"p_id\":\"$pid\",\"c_id\":\"$cid\"}&apiKey=$mongo_api_key";	//update URL
			$result2 = file_get_contents($uurl2);		//get the vote of the user
			$ares0=json_decode($result2,true);
			if(isset($ares0[0]))		//email and the post combination exists ie the has voted
			{
				$cvote=$ares0[0]["c_vote"];
			}
			*/
			$fetch=mysqli_query($conn,"Select * from comment_vote where c_id='$cid' and u_email='$u_email' and p_id='$pid'");		//retreive thevote details from the comment_vote
			//echo $fetch1;
			if($row = mysqli_fetch_array($fetch))
			{
				$cvote=$row["c_vote"];
			}
			
			$sqlg="select  count(*) total,
						sum(case when c_vote = '0' then 1 else 0 end) down,
						sum(case when c_vote = '1' then 1 else 0 end) up
					from comment_vote
					where p_id='$pid' and c_id='$cid'";
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
			$response2["c_id"] = $cid;
			$response2["u_email"] = $com["u_email"];
			$response2["comment"] = $com["comment"];
			$response2["no_of_upvotes"] = $no_of_upvotes;
			$response2["no_of_downvotes"] = $no_of_downvotes;
			$response2["c_vote"] = $cvote;
			$response2["timestamp"] = $com["timestamp"];
			
			array_push($response["comments"],$response2);
			//echo json_encode($response);
			//reset($response);
			
			//flush();
			//ob_flush();
		}
		
		echo json_encode($response);
		
	}
	else
	{
		// error in the mongo labs
			$response["type"] = "getComments";
			$response["success"] = "false";
			$response["message"] = "Sorry no comments present for this post";
	 
			// echoing JSON response
			echo json_encode($response);
	}
		
}
else {
			// no login
			$response["type"] = "getComments";
			$response["success"] = "false";
			$response["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response);
} 
?>