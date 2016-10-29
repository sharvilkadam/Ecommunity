<?php 						//get constituencies using the JSON data and JK, Arunachal and lashdweep in the default.JSON coz GAPIs not returnig for the 3
	$latitude=$_GET['lat'];
	$longitude=$_GET['lon'];
	$geolocation = $latitude.','.$longitude;
	$request = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&sensor=false'; 
	$file_contents = file_get_contents($request);
	$json_decode = json_decode($file_contents,true);
	if($json_decode['status'] === "OK"){			
		$a=$json_decode['results'][0]['formatted_address'];
		$ex=explode(",",$a);
		$c=sizeof($ex);
		$st=explode(" ",$ex[$c-2]);
		$state=$st[1];
	}
	else{							//if google doesnt return anything for the co-ordinates eg arunachal and JK
			$state="default";
	}
	//echo $state;
	switch ($state) {
		case "Andaman":
			//echo "Andaman";
			$state1="AN";
			break;
		case "Andhra":
			//echo "Andhra";
			$state1="AP";
			break;
		case "****":	//arunachal pradesh
			//echo "****";
			$state1="AR";
			break;
		case "Assam":
			//echo "Assam";
			$state1="AS";
			break;
		case "Bihar":
			//echo "Bihar";
			$state1="BR";
			break;
		case "Chhattisgarh":
			//echo "Chhattisgarh";
			$state1="CG";
			break;
		case "Chandigarh":
			//echo "Chandigarh";
			$state1="CH";
			break;
		case "Daman":
			//echo "Daman";
			$state1="DD";
			break;
		case "Delhi":
			//echo "Delhi";
			$state1="DL";
			break;
		case "Dadra":
			//echo "Dadra";
			$state1="DN";
			break;
		case "Goa":
			//echo "Goa";
			$state1="GA";
			break;
		case "Gujarat":
			//echo "Gujarat";
			$state1="GJ";
			break;
		case "Himachal":
			//echo "Himachal";
			$state1="HP";
			break;
		case "Haryana":
			//echo "Haryana";
			$state1="HR";
			break;
		case "Jharkhand":
			//echo "Jharkhand";
			$state1="JH";
			break;
		case "*****":	//Jammu kasmir
			//echo "****";
			$state1="JK";
			break;
		case "Karnataka":
			//echo "Karnataka";
			$state1="KA";
			break;
		case "Kerala":
			//echo "Kerala";
			$state1="KL";
			break;
		case "Lakshadweep":
			//echo "Lakshadweep";
			$state1="LD";
			break;
		case "Maharashtra":
			//echo "Maharashtra";
			$state1="MH";
			break;
		case "Meghalaya":
			//echo "Meghalaya";
			$state1="ML";
			break;
		case "Manipur":
			//echo "Manipur";
			$state1="MN";
			break;
		case "Madhya":
			//echo "Madhya";
			$state1="MP";
			break;
		case "Mizoram":
			//echo "Mizoram";
			$state1="MZ";
			break;
		case "Nagaland":
			//echo "Nagaland";
			$state1="NL";
			break;
		case "Odisha":
			//echo "Odisha";
			$state1="OD";
			break;
		case "Punjab":
			//echo "Punjab";
			$state1="PB";
			break;
		case "Puducherry":
			//echo "Puducherry";
			$state1="PY";
			break;
		case "Rajasthan":
			//echo "Rajasthan";
			$state1="RJ";
			break;
		case "Sikkim":
			//echo "Sikkim";
			$state1="SK";
			break;
		case "Telangana":
			//echo "Telangana";
			$state1="TL";
			break;
		case "Tamil":
			//echo "Tamil";
			$state1="TN";
			break;
		case "Tripura":
			//echo "Tripura";
			$state1="TR";
			break;
		case "Uttarakhand":
			//echo "Uttarakhand";
			$state1="UK";
			break;
		case "Uttar":
			//echo "Uttar";
			$state1="UP";
			break;
		case "West":
			//echo "West";
			$state1="WB";
			break;
		default:			//for JK , AR and LK
			//echo "Default";
			$state1="default";
	}
	//echo "<br/>";
	$file_url="http://localhost/nlp/consti/$state1.json";
	$state_polys = file_get_contents($file_url);
	$polys = json_decode($state_polys,true);
	//var_dump($polys["State"]);
	//$i=1;
	$final_PC="";
	foreach($polys["State"] as $con)
	{
		//var_dump($con);
		$pc_name=$con["properties"]["PC_NAME"];
		$pc_code=$con["properties"]["PC_CODE"];
		$geo_type=$con["geometry"]["type"];
		$coordinates=$con["geometry"]["coordinates"];
			
		if($geo_type==="Polygon")
		{
			//echo "in ploygon".$i."<br>";
			//var_dump($coordinates);
			//echo "<br><br>";
			$vertices_x = array();    // x-coordinates of the vertices of the polygon
			$vertices_y = array(); // y-coordinates of the vertices of the polygon
		
			foreach($coordinates[0] as $lonlat)			//foreach($coordinates as $lonlat)
			{
				//var_dump($lonlat);
				//echo "<br>";
				$lon=$lonlat[0];
				$lat=$lonlat[1];
				array_push($vertices_x,$lon);
				array_push($vertices_y,$lat);
			}
			//echo "<br><br>";
			
			$points_polygon = count($vertices_x) - 1;  // number vertices - zero-based array
			$longitude_x = $longitude;  // x-coordinate of the point to test
			$latitude_y = $latitude;    // y-coordinate of the point to test

			if (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){
				//echo "Is in polygon! : $pc_name ";
				$final_PC=$pc_name;
				break;
			}
			else 
			{
				//echo "Is not in polygon";
			}
		}
		else			//if multi polygon
		{
			//var_dump($coordinates);
			//echo "<br><br>";
			//echo "in multi-ploygon ".$i."<br>";
			foreach($coordinates as $multi)
			{
				//var_dump($multi);
				//echo "<br>";
				
				$vertices_x = array();    // x-coordinates of the vertices of the polygon
				$vertices_y = array(); // y-coordinates of the vertices of the polygon
				
				foreach($multi[0] as $lonlat)
				{
					
					//var_dump($lonlat);
					//echo "<br>";
					
					$lon=$lonlat[0];
					$lat=$lonlat[1];
					array_push($vertices_x,$lon);
					array_push($vertices_y,$lat);
					//echo "<br><br>";
				}
				//echo "<br><br>";
				
				$points_polygon = count($vertices_x) - 1;  // number vertices - zero-based array
				$longitude_x = $longitude;  // x-coordinate of the point to test
				$latitude_y = $latitude;    // y-coordinate of the point to test

				
				if (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){
					//echo "Is in polygon! : $pc_name ";
					$final_PC=$pc_name;
					break 2;		//break both the loop
				}
				else 
				{
					//echo "Is not in polygon";
				}
				unset($vertices_x);
				unset($vertices_y);
				
			}
		}
		//$i++;
		unset($vertices_x);
		unset($vertices_y);
	}
	
	//echo "<br>";
	//echo "out of foreach loop";
	if($final_PC != ""){
		$response["type"] = "getConstituency";
		$response["success"] = "true";
		$response["message"] = "Got Constituency";
		$response["pc_name"] = $final_PC;
		$response["st_name"] = $state1;
		echo json_encode($response);
	}
	else {
		$response["type"] = "getConstituency";
		$response["success"] = "false";
		$response["message"] = "No Constituency Mapped. Try later";
		echo json_encode($response);
	}
	
	function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
	{
	  $i = $j = $c = 0;
	  for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
		if ( (($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
		 ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) )
		   $c = !$c;
	  }
	  return $c;
	}
	
?>