 <?php			//get the number of upvotes and no of downvotes on a post(p_id) and the vote of the user (u_email + p_id)
	
session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']) && isset($_GET['p_id']))
{
	$u_email=$_SESSION['u_email'];
	$conn = dbConnect();
	
	$pid=mysqli_real_escape_string($conn,$_GET['p_id']);
	
	$no_of_upvotes="0";		//intialize in case the mongoDB fails
	$no_of_downvotes="0";
	$pvote="-1";
	
	/*
	$uurl="https://api.mlab.com/api/1/databases/ecommunity/collections/votePost?q={\"p_vote\":\"1\",\"p_id\":\"$pid\"}&c=true&apiKey=$mongo_api_key";	//update URL
	$result0 = file_get_contents($uurl);		//get no fo upvotes
	$no_of_upvotes=$result0;
	//echo $no_of_upvotes."<br>";
	
	$uurl="https://api.mlab.com/api/1/databases/ecommunity/collections/votePost?q={\"p_vote\":\"0\",\"p_id\":\"$pid\"}&c=true&apiKey=$mongo_api_key";	//update URL
	$result1 = file_get_contents($uurl);		//get no fo downvotes
	$no_of_downvotes=$result1;
	//echo $no_of_downvotes."<br>";
	
	$uurl="https://api.mlab.com/api/1/databases/ecommunity/collections/votePost?q={\"u_email\":\"$u_email\",\"p_id\":\"$pid\"}&apiKey=$mongo_api_key";	//update URL
	$result2 = file_get_contents($uurl);		//get the vote of the user
	$ares0=json_decode($result2,true);
	//var_dump($ares0);
	//echo $p_vote;
	
	if(isset($ares0[0]))		//email and the post combination exists ie the has voted
	{
		$pvote=$ares0[0]["p_vote"];
		$response["type"] = "getPostVotes";
		$response["success"] = "true";
		$response["message"] = "Getting Post Votes";
		$response["p_id"]=$pid;
		$response["no_of_upvotes"]=$no_of_upvotes;
		$response["no_of_downvotes"]=$no_of_downvotes;
		$response["u_email"]=$u_email;
		$response["p_vote"]=$pvote;
		// echoing JSON response
		echo json_encode($response);
	}
	else			//the combination doesn't exists ie the user has not voted... send -1
	{
		$response["type"] = "getPostVotes";
		$response["success"] = "true";
		$response["message"] = "Getting Post Votes.. User not Voted yet/New post";
		$response["p_id"]=$pid;
		$response["no_of_upvotes"]=$no_of_upvotes;
		$response["no_of_downvotes"]=$no_of_downvotes;
		$response["u_email"]=$u_email;
		$response["p_vote"]="-1";
		// echoing JSON response
		echo json_encode($response);
		
	}
	*/
	
		$fetch=mysqli_query($conn,"Select * from post_vote where u_email='$u_email' and p_id='$pid'");		//retreive thevote details from the comment_vote
		if($row = mysqli_fetch_array($fetch))
		{
			$pvote=$row["p_vote"];
		}
		
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
		$response["p_id"] = $pid;
		$response["no_of_upvotes"] = $no_of_upvotes;
		$response["no_of_downvotes"] = $no_of_downvotes;
		$response["u_email"] = $u_email;
		$response["p_vote"] = $pvote;
		//$response2["timestamp"] = $com["timestamp"];
		echo json_encode($response);
}
else {
			// failed to add task
			$response["type"] = "getPostVotes";
			$response["success"] = "false";
			$response["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response);
} 
?>