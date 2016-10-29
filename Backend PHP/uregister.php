<?php
include 'db.php';

// array for JSON response
$response = array();
if (isset($_GET['u_pass']) && isset($_GET['u_email'])){

	$conn = dbConnect();

	$email=mysqli_real_escape_string($conn,$_GET['u_email']);
	$pass=mysqli_real_escape_string($conn,$_GET['u_pass']);
	$name=mysqli_real_escape_string($conn,$_GET['u_name']);
	$phone=mysqli_real_escape_string($conn,$_GET['u_phone']);
	$home_constituency=mysqli_real_escape_string($conn,$_GET['home_constituency']);
		

	 // insert into the user_master table
	$sql ="INSERT INTO user_master (u_email, u_pass,u_name, u_phone, home_constituency) VALUES('$email','$pass','$name','$phone','$home_constituency')";
	$result = mysqli_query($conn, $sql);
	if ($result) {
			// successfully inserted into database
			//Mail the newly registered user
			$message="Hello There !!			
			Welcome to E-community !!!
			
			Your personal account for Ecommunity
			has been created! 
			
			Your personal login ID and password are as follows:
			Email:'$email'
			Password:'$pass'

			If you have any problems, feel free to contact at <xyz@email.com>.
			
			-Team Ecommunity";
			mail($email,"Password for Your User account",$message, "From:CRY <xyz@email.com>");

			$response["type"] = "uregister";
			$response["success"] = "true";
			$response["message"] = "Thanks for registering";
			$response["u_email"]=$email;
			$response["u_name"]=$name;
			$response["home_constituency"]=$home_constituency;
			// echoing JSON response
			echo json_encode($response);
		} else {
			// failed to insert row
			$response["type"] = "uregister";
			$response["success"] = "false";
			$response["message"] = "Oops! Some Error occurred ..Pls Try again with some other credentials.";
	 
			// echoing JSON response
			echo json_encode($response);
		} 
	} else {
		// required field is missing
		$response["type"] = "uregister";
		$response["success"] = "false";
		$response["message"] = "Required field(s) is missing";
	 
		// echoing JSON response
		echo json_encode($response);
}
?>

