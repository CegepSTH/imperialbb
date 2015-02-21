<?php
define("IBB_WEB_SERVICE", 1);
require_once("imperial_service.php");
header("Content-type: application/json");

ValidateAccess();

if($_GET['act'] == "profile") {
	$lstErrors = array();
	$keys = array(
		"user_id" => "", "user_signature" => "", "user_signature" => "",
		"user_email" => "", "user_date_joined" => "", "user_rank" => "",
		"user_posts" => "", "user_location" => "", "user_website" => "",
		"user_avatar_location" => "", "user_birthday" => "", "user_password" => "");
	
	foreach($keys as $key => $value) {
		if(!isset($_POST[$key])) {
			$lstErrors[] = "Property `".$key."` was not received.";
		} else {
			$keys[$key] = $_POST[$key];
		}
	}
	
	if(!empty($keys)) {
		$error = array(
			"error" => "USER_NOT_FOUND",
			"error_level" => "FATAL",
			"errors_arr" => $lstErrors);
		$json = json_encode($error);
		echo $json;
		exit();
	}
	
	$ok = ImperialService::setUserInfo($keys);
	
	$error = array(
		"error" => "SUCCESS");
		
	if($ok) {
		
	} else {
		$error = array(
			"error" => "USER_COULDNT_UPDATE",
			"error_level" => "FATAL",
			"error_msg" => "Database is either busy, down, or Jim has died.");
	}
	
	$json = json_encode($error);
	echo $json;
	exit();	
}

?>
