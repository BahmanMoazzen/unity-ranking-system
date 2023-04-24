<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


//server information
$servername = "localhost";
$username = "spssland_Unity";
$password = "Unity110";
$dbname = "spssland_Unity";


//read json data and convert to result
$get_json_file = file_get_contents("php://input");
$json_results = json_decode($get_json_file);


// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);

$stmt = $conn->prepare("SELECT player_rank FROM unity_ranks_view_alltime where pk_id=? AND user_name =? LIMIT 1");
$stmt->bind_param("ss", $pkg_id, $user_name);


$pkg_id=$json_results->pkg_id;
$user_name=$json_results->user_name;
$stmt->execute();


$result = $stmt->get_result();
if($result->num_rows>0){
	$row = $result->fetch_assoc();
	echo $row["player_rank"];
}else{
	echo 0;
}
/*
if ($result->num_rows > 0) 
{
  // output data of each row
  //$row = $result->fetch_assoc()
  //echo 1;
  //echo $row["player_rank"];// json_encode($row["player_rank"]);
  
} 
else 
{
  //echo 0;
}
*/
$stmt->close();
$conn->close();

?>