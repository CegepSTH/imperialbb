<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: register.php                                               # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright � 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

define("IN_IBB", 1);

$root_path = "./";
require_once($root_path . "includes/common.php");
require_once($root_path . "classes/password.php");
require_once($root_path . "models/user.php");

$language->add_file("register");

// Redirects to login page after registering.
if($_GET['act'] == "login") {
	header("Location: login.php");
} 

if(isset($_POST['Submit'])) {
	CSRF::validate();

	$error = "";
	
	if(strlen($_POST['UserName']) < 2) {
		$error .= $lang['Username_Too_Short'] . "<br />";
	} else if(userexists($_POST['UserName']) == 1) {
		$error .= $lang['Username_Already_Taken'] . "<br />";
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
		$theme->new_file("register", "register.tpl", "");
		
		$theme->replace_tags("register", array(
			"USERNAME" => $_POST['UserName'],
			"EMAIL" => $_POST['Email']
		));
		
		$theme->insert_nest("register", "error", array(
			"ERRORS" => $error
		));
		
		$theme->add_nest("register", "error");

		include_once($root_path . "includes/page_header.php");
		$theme->output("register");
		include_once($root_path . "includes/page_footer.php");
	} else {
		if($config['register_auth_type'] == 0) {
			// No activation key.
			$oUser = new User(-1, $_POST['UserName'], $_POST['Email']);
			$oUser->setPassword($_POST['Password']);
			$oUser->setLanguageId($config['default_template']);
			$oUser->setTemplateId($config['default_language']);
			$oUser->setRankId(1);
			$oUser->update();
			
			info_box($lang['Registration'], $lang['Registration_Successfull_Msg'], "?act=login");
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
					
			info_box($lang['Registration'], $lang['Activate_Your_Acct_Msg'], "?act=login");
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
			error_msg($lang['Account_Activation'], $lang['Account_Already_Activated']);
		} else if($result['activation_key'] != $_GET['key']) {
			error_msg($lang['Account_Activation'], $lang['Invalid_Activation_Key']);
		} else {
			$db2->query("UPDATE `_PREFIX_users`
				SET `user_level` = '3'
				WHERE `user_id` = :user_id",
				array(
					":user_id" => $_GET['id']
				)
			);
			info_box($lang['Account_Activation'], $lang['Account_Activated'], "?act=login");
		}
	} else {
		error_msg($lang['Error'], $lang['Invalid_User_Id']);
	}
} else {
	$theme->new_file("register", "register.tpl", "");
	$theme->replace_tags("register", array(
		"USERNAME" => "",
		"EMAIL" => "",
		"CSRF_TOKEN" => CSRF::getHTML()
	));

	$page_title = $config['site_name'] . " &raquo; " . $lang['Register'];

	include_once($root_path . "includes/page_header.php");
	$theme->output("register");
	include_once($root_path . "includes/page_footer.php");
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright � 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
