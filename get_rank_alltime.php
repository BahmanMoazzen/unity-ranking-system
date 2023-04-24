<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
header("Content-Type: application/json; charset=UTF-8");


//server information
$servername = "localhost";
$username = "spssland_Unity";
$password = "Unity110";
$dbname = "spssland_Unity";
$max_record =15;
//setting result to user dosn't exists
$result_mess_int = 0;

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
  $result_mess_int = 2;
}
//read json data and convert to result
$get_json_file = file_get_contents("php://input");
$results = json_decode($get_json_file);
if($results->limit === 0){
	$results->limit =$max_record;
}
if($results->limit> $max_record)
{
	$results->limit =$max_record;
}
$stmt = $conn->prepare("SELECT display_name,player_score,user_ip FROM unity_ranks_view_alltime where pk_id =? order by player_score desc LIMIT ?");
$stmt->bind_param("ss",$results->pkg_id, $results->limit);
$stmt->execute();
$result = $stmt->get_result();
$outp = $result->fetch_all(MYSQLI_ASSOC);

echo "{\"RankingItem\": ".json_encode($outp)."}";


?>