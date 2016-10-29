 <?php			//get all the vote of the user frmo the post_vote
	
session_start();
include('db.php');

$response = array();
if(isset($_SESSION['u_email']) && $_GET['con_name'])
{
	$u_email=$_SESSION['u_email'];
	$conn = dbConnect();
	
	$con_name=mysqli_real_escape_string($conn,$_GET['con_name']);
	
		$pidarray=array();
		$result=mysqli_query($conn,"Select * from post_vote where u_email='$u_email'");		//retreive thevote details from post_vot
		if (mysqli_num_rows($result) > 0) 
		{
			// looping through all results
			// products node
			$response["type"] = "getUserVotes";
			$response["success"] = "true";
			$response["message"] = "getting all the posts votes by the user";
			$response["postVotes"] = array();
			while($row = mysqli_fetch_array($result))
			{
				$pid=$row["p_id"];
				
				$pvote=$row["p_vote"];
			
				$fetch=mysqli_query($conn,"Select * from post_master where p_id='$pid'");		//retreive thevote details from the comment_vote
				if($row = mysqli_fetch_array($fetch))
				{
					$gcon_name=$row["con_name"];
				}
				else
					$gcon_name="0";
				if($gcon_name==$con_name and !in_array($pid,$pidarray))
				{
					array_push($pidarray,$pid);
					$sqlg="select  count(*) total,
								sum(case when p_vote = '0' then 1 else 0 end) down,
								sum(case when p_vote = '1' then 1 else 0 end) up
							from post_vote
							where p_id='$pid'";
					$fetchg=mysqli_query($conn,$sqlg);		//retreive the not of upvotes and no of downvotes of the comments 
					if($rowg = mysqli_fetch_array($fetchg))
					{
						$no_of_upvotes=$rowg['up'];
						$no_of_downvotes=$rowg['down'];
						$total=$rowg['total'];
					}
					if($no_of_upvotes==null)
						$no_of_upvotes=0;
					if($no_of_downvotes==null)
						$no_of_downvotes=0;
					$response2["p_id"] = $pid;
					$response2["no_of_upvotes"] = $no_of_upvotes;
					$response2["no_of_downvotes"] = $no_of_downvotes;
					$response2["u_email"] = $u_email;
					$response2["p_vote"] = $pvote;
					//$response2["timestamp"] = $com["timestamp"];
					array_push($response["postVotes"],$response2);
				}
			}
				
			echo json_encode($response);
		}
		else{			//not voted any post
				$response["type"] = "getUserVotes";
				$response["success"] = "false";
				$response["message"] = "User has not voted any post";
				echo json_encode($response);
		}
}
else {
			// failed to add task
			$response["type"] = "getUserVotes";
			$response["success"] = "false";
			$response["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response);
} 
?>