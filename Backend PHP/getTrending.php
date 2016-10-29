<?php			//get all the posts associated with the constituency AND CHECK FOR THE TRENDING posts using chi^2 and
	//store it in the mongo db for the first time and check for all the subsiquent requests
	//only wrt to no_of_upvotes
	//wrt to both upvotes n the comments in getTrending_b.php
	
session_start();
include('db.php');

$responsex = array();
if(isset($_SESSION['u_email']))
{
	$u_email=$_SESSION['u_email'];
	$conn = dbConnect();
	
	$con_name=mysqli_real_escape_string($conn,$_GET['con_name']);
	
	$sql ="Select CURRENT_TIMESTAMP";
	$result = mysqli_query($conn,$sql);
	$row=mysqli_fetch_array($result);
	$ct=$row['CURRENT_TIMESTAMP'];	//ct is the timestamp of a day before for observed value
	//echo $ct."<br>";
	$ct2=explode(" ",$ct);
	$ct3=explode("-",$ct2[0]);
	$ct3[2]-=1;
	$pt1=implode("-",$ct3);
	$ct2[0]=$pt1;
	$ct2[1]="00:00:00";
	$pt=implode(" ",$ct2);		//pt is the timestamp of a day before for observed value
	//echo $pt;			
	
	//current timestamp
	$response = array();
	$response["timestamp"] = $ct;
	$response["posts"] = array();
		
	$sqlg="select  p_id,count(*) total,
						sum(case when p_vote = '1' then 1 else 0 end) up
					from post_vote
					group by p_id";
	$fetchg=mysqli_query($conn,$sqlg);		//retreive the not of upvotes and no of downvotes of the comments 
	while($row = mysqli_fetch_array($fetchg))
	{
		$pid=$row['p_id'];
		
		$no_of_upvotes=$row['up'];
		$total=$row['total'];
		$response2["p_id"] = $pid;
		$response2["no_of_upvotes"] = $no_of_upvotes;
		$response2["total"] = $total;
		array_push($response["posts"],$response2);
	}
	//echo json_encode($response);
	//var_dump($response);
	
	//previous timestamp
	$response3 = array();
	$response3["timestamp"] = $pt;
	$response3["posts"] = array();
		
	$sqlg1="select  p_id,count(*) total,
						sum(case when p_vote = '1' then 1 else 0 end) up
					from post_vote
					where timestamp < '$pt'
					group by p_id";
	$fetchg1=mysqli_query($conn,$sqlg1);		//retreive the not of upvotes and no of downvotes of the comments 
	while($row1 = mysqli_fetch_array($fetchg1))
	{
		$pid3=$row1['p_id'];
		$no_of_upvotes3=$row1['up'];
		$total3=$row1['total'];
		$response4["p_id"] = $pid3;
		$response4["no_of_upvotes"] = $no_of_upvotes3;
		$response4["total"] = $total3;
		array_push($response3["posts"],$response4);
	}
	//echo json_encode($response3);
	//var_dump($response3["posts"]);
	//echo "<bR>";
	
	$trending=array();
	$trending["tposts"]=array();
	foreach($response["posts"] as $p)	//for each 
	{
		$fpid=$p["p_id"];
		$sql1="SELECT con_name from post_master where p_id=$fpid";
		$fetch1=mysqli_query($conn,$sql1);		//retreive the con name
		if($row1 = mysqli_fetch_array($fetch1))
			$fcon=$row1["con_name"];
		
		if($fcon == $con_name)
		{
			$fup=$p["no_of_upvotes"];
			//echo $fpid." ".$p["no_of_upvotes"]."<br>";
			$key=array_search($fpid, array_column($response3["posts"], 'p_id'));		//return the key if found 

			if(is_numeric($key))	//the post p is there in the previous posts array
			{
				$ppid=$response3["posts"][$key]["p_id"];
				$pup=$response3["posts"][$key]["no_of_upvotes"];
				//echo "THere :  $ppid: $pup";
			}
			else
			{
				$ppid=$fpid;
				$pup=0;
				
				//echo "Not there:  $ppid: $pup";
			}
				//now compare $fup and $pup for the chi2
			if($pup==0)
				$pup=1;		//the condition of the chi2 //division by zero
		
			//echo "fpid: ".$fpid." $fup	";		//observed
			//echo "ppid: ".$ppid." $pup";			//expected
			$chi2value=chi2($fup,$pup);
		
			//echo "chi1 ".$chi2value."<br>";
			
			if($chi2value>=3.84)		//coresponding to the p value of 0.05 in chisquare 
			{						//thus trensing post wrt to upvotes
				$res=array();
				$res["p_id"]=$fpid;
				$res["chi"]=$chi2value;
				array_push($trending["tposts"],$res);
			}
		}
	}
	//var_dump($trending);
	function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {
		$sort_col = array();
		foreach ($arr as $key=> $row) {
			$sort_col[$key] = $row[$col];
		}

		array_multisort($sort_col, $dir, $arr);
	}


	array_sort_by_column($trending["tposts"], 'chi');
	//echo "<br><Br>";
	//var_dump($trending); 			//sort wrt highest chi squre value n take the top2-3
	
	$responsex["type"] = "getTrending";
	$responsex["success"] = "true";
	$responsex["message"] = "Trending POsts Getting";
	$responsex["tposts"] = array();
	foreach($trending["tposts"] as $tpost)
	{
		$tpid=$tpost["p_id"];
		$response2["p_id"] = $tpid;
		$sql2="SELECT * from post_master where p_id=$tpid";
		$fetch2=mysqli_query($conn,$sql2);		//retreive the con name
		if($row2 = mysqli_fetch_array($fetch2))
		{
			$ptitle1=$row2["p_title"];
			$pdesc1= $row2['p_desc'];
			$uemail1 = $row2['u_email'];
			$plat1 = $row2['p_lat'];
			$plon1= $row2['p_lon'];
			$upvotes1 = $row2['upvotes'];
			$downvotes1 = $row2['downvotes'];
			$timestamp1 = $row2['timestamp'];
			$no_of_comments1= $row2['no_of_comments'];
		}
		$response2["p_title"] = $ptitle1;
		$response2["p_desc"] = $pdesc1;
		$response2["u_email"] = $uemail1;
		$response2["p_lat"] = $plat1;
		$response2["p_lon"] = $plon1;
		$response2["upvotes"] = $upvotes1;
		$response2["downvotes"] = $downvotes1;
		$response2["timestamp"] = $timestamp1;
		$response2["no_of_comments"] = $no_of_comments1;
		
		array_push($responsex["tposts"],$response2);
	}
	
	
		echo json_encode($responsex);
	
	
}
else {
			// no login
			$response2["type"] = "getPosts";
			$response2["success"] = "false";
			$response2["message"] = "Unauthorized !! Please Login to Continue";
	 
			// echoing JSON response
			echo json_encode($response2);
} 


function chi2($ob,$ex) 		//chi square test to determine the spike in upvotes or comments
{				
	
	$ob2 =pow(($ob-$ex),2);  			// pow available in php
	$chi = $ob2/$ex; 
	
	return $chi;
}
?>