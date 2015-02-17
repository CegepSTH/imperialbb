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
	
	$result = ImperialService::getTopicsList($_GET['fid'], 0, 10);
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
	echo $json;
	exit();
}

?>
