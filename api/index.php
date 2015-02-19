<?php
define("IBB_WEB_SERVICE", 1);
require_once("imperial_service.php");

// Check if token was supplied.
if(!isset($_GET['tok']) || trim($_GET['tok']) == "") {
	$error = array(
		"error" => "NO_TOKEN_ERROR", 
		"error_level" => "FATAL",
		"error_msg" => "No token was given.");
	
	$json = json_encode($error);
	
	echo $json;
	exit();
} else {
	// Verify if user is ok to access.
	if(ImperialService::checkAppToken($_GET['tok']) == false) {
		$error = array(
			"error" => "BAD_TOKEN_BAD_ERROR",
			"error_level" => "FATAL", 
			"error_msg" => "Specified token is invalid.");
		$json = json_encode($error);
		echo $json;
		exit();
	}
}

// Is action specified?
if(!isset($_GET['act']) || trim($_GET['act']) == "") {
	$error = array(
		"error" => "NO_ACTION_ERROR", 
		"error_level" => "FATAL",
		"error_msg" => "No action was specified.");
	
	$json = json_encode($error);
	
	echo $json;
	exit();	
}

if($_GET['act'] == "appcheck") {
	$bAuth = ImperialService::checkAppToken($_GET['tok']);
	header("Content-type: plain/text");
	
	if($bAuth) {
		echo "AUTHORIZED";
	} else {
		echo "UNAUTHORIZED";
	}
	
	exit();
}

// Parse actions.
if($_GET['act'] == "forums") {
	$forumsList = ImperialService::getAllForumsList();
	$json = json_encode($forumsList);
	
	echo $json;
	exit();
} else if($_GET['act'] == "topics") {
	// Get topics lists
	if(!isset($_GET['fid']) || !is_numeric($_GET['fid'])) {
		$error = array(
			"error" => "FORUM_ID_INVALID_ERROR",
			"error_level" => "FATAL",
			"error_msg" => "Forum id was either unspecified or is not numeric.");
		$json = json_encode($error);
		echo $json;
		exit();
	}
	
	$result = ImperialService::getTopicsList($_GET['fid'], 0, 20);
	$json = json_encode($result);
	
	echo $json;
	exit();
} else if($_GET['act'] == "posts") {
	// Get all post for given topic
	if(!isset($_GET['tid']) || !is_numeric($_GET['tid'])) {
		$error = array(
			"error" => "TOPIC_ID_INVALID_ERROR",
			"error_level" => "FATAL",
			"error_msg" => "Topic id was either unspecified or is not numeric.");
		$json = json_encode($error);
		echo $json;
		exit();
	}
	
	$result = ImperialService::getTopicPostsList($_GET['tid'], 0, 20);
	$json = json_encode($result);
	
	echo $json;
	exit();
} else if ($_GET['act'] == "profile") {
	if((!isset($_GET['uid']) && !is_numeric($_GET['uid'])) 
		|| (!isset($_GET['u']) && trim($_GET['u']) == "")) 
	{
		$error = array(
			"error" => "INVALID_PROFILE_ID_USERNAME",
			"error_level" => "FATAL",
			"error_msg" => "User id or username was not specified or invalid.");
		$json = json_encode($error);
		
		echo $json;
		exit();		
	}
	
	// if uid is null then take username. 
	$uid = $_GET['uid'] ?: $_GET['u'];
	$result = ImperialService::getUserInfo($uid);
	
	$json = json_encode($error);
	echo $json;
	exit();
}

?>
