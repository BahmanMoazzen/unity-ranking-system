<?php

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//server information
$servername = "localhost";
$username = "spssland_Unity";
$password = "Unity110";
$dbname = "spssland_Unity";
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

//checking user availablity
$sql = "SELECT user_name FROM unity_ranks where unity_ranks.pk_id = '".$results->pkg_id."' AND unity_ranks.user_name ='".$results->user_name."'";
$result = $conn->query($sql);
if ($result->num_rows > 0)
{
	//if available deleting current information
	$stmt = $conn->prepare("DELETE FROM unity_ranks WHERE unity_ranks.pk_id = ? AND unity_ranks.user_name = ?");
	$stmt->bind_param("ss",$pkg_id,$user_name);
	$pkg_id=$results->pkg_id;
	$user_name=$results->user_name;
	$stmt->execute();
	//setting result value to existing user
	$result_mess_int = 1;
}

// prepare and bind
$stmt = $conn->prepare("INSERT INTO unity_ranks (pk_id, user_name, display_name, player_score, user_ip) VALUES (?,?,?,?,?)");
$stmt->bind_param("sssis",$pkg_id,$user_name,$display_name,$player_score,$user_ip);

//setting parameters
$pkg_id=$results->pkg_id;
$user_name=$results->user_name;
$display_name =$results->display_name;
$player_score = $results->score;
$user_ip =$_SERVER['REMOTE_ADDR'];
//executing
$stmt->execute();

//closing connections
$stmt->close();
$conn->close();

//returning result
echo $result_mess_int;

/*
echo $results->pkg_id ;
echo $results->user_name ;
echo $results->display_name ;
echo $results->score ;
echo $_SERVER['REMOTE_ADDR'] ;
*/
?>