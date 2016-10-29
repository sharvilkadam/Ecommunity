 <?php			//voate a comment by the user 
	
error_reporting(E_ERROR | E_PARSE);

session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']) && isset($_GET['p_id']) && isset($_GET['c_id']) && isset($_GET['c_vote']))	//vote=1 =>upvote  vote=0 =>downvote
{
	$u_email=$_SESSION['u_email'];
	$conn = dbConnect();
	
	$cid=mysqli_real_escape_string($conn,$_GET['c_id']);
	$pid=mysqli_real_escape_string($conn,$_GET['p_id']);
	$cvote=mysqli_real_escape_string($conn,$_GET['c_vote']);
	
	$arese=0;
	$uurle="https://api.mlab.com/api/1/databases/ecommunity/collections/comments/$cid?apiKey=$mongo_api_key";	//update URL
	$resulte = file_get_contents($uurle);		//get the vote of the user
	$arese=json_decode($resulte,true);
	if($arese!=0)		//comment exists thus user can vote
	{
	
		$uurl="https://api.mlab.com/api/1/databases/ecommunity/collections/voteComment?q={\"u_email\":\"$u_email\",\"c_id\":\"$cid\",\"p_id\":\"$pid\"}&apiKey=$mongo_api_key";	//update URL
		$result0 = file_get_contents($uurl);		//get the vote of the user
		$ares0=json_decode($result0,true);
		//var_dump($ares0);
		
		
		if(!isset($ares0[0]))		//email and the post combination doesnt exit ie not votes yet... therefore add 
		{
			$x=array();
			$x["u_email"] = $u_email;
			$x["p_id"] = $pid;
			$x["c_id"] = $cid;
			$x["c_vote"] = $cvote;
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
			$result = file_get_contents('https://api.mlab.com/api/1/databases/ecommunity/collections/voteComment?apiKey='.$mongo_api_key, false, $context);

			$ares=json_decode($result,true);
			//var_dump($ares);
				
				//using SQL
			$sql0 ="INSERT INTO comment_vote (u_email,p_id,c_id,c_vote,timestamp) VALUES('$u_email','$pid','$cid','$cvote',CURRENT_TIMESTAMP)";
			$result0 = mysqli_query($conn,$sql0);
			
			/*
			$fetch=mysqli_query($conn,"Select * from comment_master where c_id='$cid'");		//retreive the post details from the post_master
			if($row = mysqli_fetch_array($fetch))
			{
				$no_of_upvotes=$row['no_of_upvotes'];
				$no_of_downvotes=$row['no_of_downvotes'];
				//echo $no_of_upvotes."+".$no_of_downvotes;
			}
			if($cvote==1)		//upvote
			{
				$no_of_upvotes+=1;
				$sql ="UPDATE comment_master SET no_of_upvotes = '$no_of_upvotes' where c_id='$cid'";	
			}
			else if($cvote==0)		//downvotes
			{
				$no_of_downvotes+=1;
				$sql ="UPDATE comment_master SET no_of_downvotes = '$no_of_downvotes' where c_id='$cid'";
			}
			$result3 = mysqli_query($conn,$sql);
			*/
			
			$response["type"] = "voteComment";
			$response["success"] = "true";
			$response["message"] = "Comment Voted";
			$response["u_email"]=$u_email;
			$response["p_id"]=$pid;
			$response["c_id"]=$cid;
			$response["c_vote"]=$cvote;
			// echoing JSON response
			echo json_encode($response);
		}
		else			//the combination exists ie the user already voted.. wants to change
		{
			$avote=$ares0[0]["c_vote"];
			$vcid=$ares0[0]["_id"]["\$oid"];
			if($avote === $cvote)				//same vote again
			{
				$response["type"] = "voteComment";
				$response["success"] = "false";
				$response["message"] = "Comment Already voted ";
				$response["c_vote"]=$cvote;
				$response["c_id"]=$cid;
				// echoing JSON response
				echo json_encode($response);
			}
			else				//change of vote
			{
				$x=array();
				//$x["p_id"] = $pid;		//these two will be the same so no need to pass
				//$x["u_email"] = $u_email;
				$x["\$set"]["c_vote"] = $cvote;			//{"$set":{"comment":"this is an updated comment"}}
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
				$uurl="https://api.mlab.com/api/1/databases/ecommunity/collections/voteComment/$vcid?apiKey=$mongo_api_key";
				$result1 = file_get_contents($uurl, false, $context);

				$ares=json_decode($result1,true);
				//var_dump($ares);
				
				
				//using SQL for faster processing
				$sql ="UPDATE comment_vote SET c_vote = '$cvote', timestamp = CURRENT_TIMESTAMP where p_id='$pid' and c_id='$cid' and u_email='$u_email'";
				$result3 = mysqli_query($conn,$sql);
				
				/*
				$fetch=mysqli_query($conn,"Select * from comment_master where c_id='$cid'");		//retreive the post details from the post_master
				if($row = mysqli_fetch_array($fetch))
				{
					$no_of_upvotes=$row['no_of_upvotes'];
					$no_of_downvotes=$row['no_of_downvotes'];
					//echo $no_of_upvotes."+".$no_of_downvotes;
				}
				if($cvote==1)		//downvote changed to upvote
				{
					$no_of_upvotes+=1;
					$no_of_downvotes-=1;
					$sql ="UPDATE comment_master SET no_of_upvotes = '$no_of_upvotes',no_of_downvotes='$no_of_downvotes' where c_id='$cid'";	
				}
				else if($cvote==0)		//upvote changed to downvotes
				{
					$no_of_upvotes-=1;
					$no_of_downvotes+=1;
					$sql ="UPDATE comment_master SET no_of_upvotes = '$no_of_upvotes',no_of_downvotes='$no_of_downvotes' where c_id='$cid'";
				}
				$result3 = mysqli_query($conn,$sql);
				*/

				$response["type"] = "voteComment";
				$response["success"] = "true";
				$response["message"] = "Changed comment vote";
				$response["u_email"]=$u_email;
				$response["p_id"]=$pid;
				$response["c_id"]=$cid;
				$response["c_vote"]=$cvote;
				
				
				// echoing JSON response
				echo json_encode($response);
			}
			
		}
	}
	else	//comment doesnt exists
	{
		// error in the mongo labs
			$response["type"] = "voteComment";
			$response["success"] = "false";
			$response["message"] = "Comment doest exist";
	 
			// echoing JSON response
			echo json_encode($response);
	}
}
else {
		// failed to add task
		$response["type"] = "voteComment";
		$response["success"] = "false";
		$response["message"] = "Unauthorized !! Please Login to Continue";
 
		// echoing JSON response
		echo json_encode($response);
} 
?>