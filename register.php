<?php
define("IN_IBB", 1);

$root_path = "./";
require_once($root_path . "includes/common.php");
require_once($root_path . "classes/password.php");
require_once($root_path . "models/user.php");

$language->add_file("register");
$language->add_file("profile");
Template::addNamespace("L", $lang);

// Redirects to login page after registering.
if(isset($_GET['act']) && $_GET['act'] == "login") {
	header("Location: login.php");
} 

if(isset($_POST['Submit'])) {
	CSRF::validate();

	$error = "";
	
	if(strlen($_POST['UserName']) < 2) {
		$error .= $lang['Username_Too_Short'] . "<br />";
	} else if(User::findUser($_POST['UserName']) != null) {
		$error .= $lang['Username_Already_Taken'] . "<br />";
	}

	if(strlen($_POST['Email']) < 2) {
		$error .= $lang['Email_Too_Short'] . "<br />";
	} else if(emailexists($_POST['Email']) == 1) {
		$error .= $lang['Email_Already_Taken'] . "<br />";
	}

	if(strlen($_POST['Password']) < 4) {
		$error .= $lang['Password_Too_Short'] . "<br />";
	} else if($_POST['Password'] != $_POST['Pass2']) {
		$error .= $lang['Passwords_Dont_Match'] . "<br />";
	}
	
	if(!preg_match("#(.*?)@(.*?)\.(.*?)#", $_POST['Email'])) {
		$error .= $lang['Invalid_Email_Address'] . "<br />";
	}
	
	if(strlen($error) > 0) {
		$page_master = new Template("register.tpl");
		
		$page_master->setVars(array(
			"USERNAME" => $_POST['UserName'],
			"EMAIL" => $_POST['Email'],
			"CSRF_TOKEN" => CSRF::getHTML()
		));
		
		$page_master->addToBlock("error", array(
			"ERRORS" => $error
		));

		outputPage($page_master);	
		exit();
	} else {
		if($config['register_auth_type'] == 0) {
			// No activation key.
			$oUser = new User(-1, $_POST['UserName'], $_POST['Email']);
			$oUser->setPassword($_POST['Password']);
			$oUser->setLanguageId($config['default_template']);
			$oUser->setTemplateId($config['default_language']);
			$oUser->setRankId(1);
			$oUser->update();
			
			showMessage(ERR_CODE_ACCOUNT_CREATED, "login.php");
		} else {
			$oUser = new User(-1, $_POST['UserName'], $_POST['Email']);
			$oUser->setPassword($_POST['Password']);
			$oUser->setLanguageId($config['default_template']);
			$oUser->setTemplateId($config['default_language']);
			$activation_key = generate_activate_key();
			$oUser->setActivationKey($activation_key);
			$oUser->setRankId(1);
			$oUser->update(true);
			
			email($lang['Email_New_Account_Subject'], "new_account", 
				array("USER_ID" => $db2->lastInsertId(), 
					"USERNAME" => $_POST['UserName'], 
					"PASSWORD" => $_POST['Password'], 
					"KEY" => $activation_key, 
					"DOMAIN" => $config['url'], 
					"SITE_NAME" => $config['site_name']), $_POST['Email']);
			showMessage(ERR_CODE_ACTIVATE_ACCOUNT);
		}
	}
} else if(isset($_GET['id']) && isset($_GET['key'])) {
	$sql = $db2->query("SELECT * FROM `_PREFIX_users`
		WHERE `user_id` = :user_id",
		array(
			":user_id" => $_GET['id']
		)
	);
	if($result = $sql->fetch()) {
		if($result['user_level'] != "2") {
			showMessage(ERR_CODE_ACCOUNT_ALREADY_ACTIVATED, "login.php");
		} else if($result['activation_key'] != $_GET['key']) {
			showMessage(ERR_CODE_INVALID_ACTIVATION_KEY, "index.php");
		} else {
			$db2->query("UPDATE `_PREFIX_users`
				SET `user_level` = '3'
				WHERE `user_id` = :user_id",
				array(":user_id" => $_GET['id']));
			showMessage(ERR_CODE_ACTIVATION_SUCCESS, "login.php");
		}
	} else {
		showMessage(ERR_CODE_INVALID_USER_ID);
	}
} else {
	$page_master = new Template("register.tpl");
	$page_master->setVars(array(
		"USERNAME" => "",
		"EMAIL" => "",
		"CSRF_TOKEN" => CSRF::getHTML()
	));

	$page_title = $config['site_name'] . " &raquo; " . $lang['Register'];
	outputPage($page_master, $page_title);
	exit();
}
?>
