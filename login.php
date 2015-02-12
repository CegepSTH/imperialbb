<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: login.php                                       # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright � 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

define("IN_IBB", 1);

$root_path = "./";
$ignore_offline = true;
require_once($root_path . "includes/common.php");
require_once($root_path . "classes/password.php");
include_once($root_path . "models/user.php");

$language->add_file("login");
Template::addNamespace("L", $lang);

if(!isset($_GET['func'])) $_GET['func'] = "";


if($_GET['func'] == "activate")
{
	if(!isset($_GET['user_id'])) error_msg($lang['Error'], $lang['Activation_Error_Msg']);
	if(!isset($_GET['key'])) error_msg($lang['Error'], $lang['Activation_Error_Msg']);
	
	$activationStatus = User::activate(intval($_GET['user_id']), $_GET['key']);
	
	// If done properly, show message.
	if($activationStatus == 0) {
		info_box($lang['Activation_Successful'], 
			sprintf($lang['Activation_Successful_Msg'], 
			$result['username']), "login.php");
	} elseif ($activationStatus == 1) {
		error_msg($lang['Activation_Error'], $lang['Already_Activated_Msg']);
	} else {
		error_msg($lang['Activation_Error'], $lang['Activation_Error_Msg']);
	}
}
else if($_GET['func'] == "logout")
{
	setcookie("UserName");
	setcookie("Password");
	$_SESSION['user_id'] = -1;
	session_regenerate_id();
	
	$db2->query("DELETE FROM `_PREFIX_sessions`
		WHERE `ip` = :remote_ip",
		array(":remote_ip" => $_SERVER['REMOTE_ADDR']) );
	
	info_box($lang['Logout'], $lang['Logged_Out_Msg'], "index.php");
}
else if($_GET['func'] == "forgotten_pass")
{
	if(isset($_POST['Submit']))
	{
		CSRF::validate();

		if(!isset($_POST['username']) || !isset($_POST['email'])) error_msg($lang['Error'], $lang['Invalid_username_or_email']);
		$query = $db2->query("SELECT `user_id`, `username`, `user_email`
			FROM `_PREFIX_users`
			WHERE `username` = :username AND `user_email` = :email",
			array(
				":username" => $_POST['username'],
				":email" => $_POST['email']
			)
		);

		if($result = $query->fetch())
		{
			$key = generate_activate_key();
			$password = generate_activate_key(7);
			$db2->query("UPDATE `_PREFIX_users`
				SET `user_activation_key` = :key,
				`user_new_password` = :password,
				`user_password_reset_request` = :current_time
				WHERE `user_id` = :user_id",
				array(
					":key" => $key,
					":Password" => password_hash($password, PASSWORD_BCRYPT),
					":current_time" => time(),
					":user_id" => $result['id']
				)
			);
			email($lang['Forgotten_Password_Email_Subject'], "forgotten_password", array(
				"DOMAIN" => $config['url'],
				"USER_ID" => $result['user_id'],
				"USERNAME" => $result['username'],
				"PASSWORD" => $password,
				"KEY" => $key
			), $result['user_email']);
		}
		else
		{
			error_msg($lang['Error'], $lang['Invalid_username_or_email']);
		}
	}
	else
	{
		$theme->new_file("forgotten_password", "forgotten_password.tpl");
		$theme->replace_tags("forgotten_password",
			array(
				"CSRF_TOKEN" => CSRF::getHtml()
			)
		);

		$page_title = $config['site_name'] . " &raquo; " . $lang['Forgotten_Password'];

		include_once($root_path . "includes/page_header.php");
		$theme->output("forgotten_password");
		include_once($root_path . "includes/page_footer.php");
	}

}
else if($_GET['func'] == "activate_new_pass")
{
	if(!isset($_GET['user_id'])) error_msg($lang['Error'], $lang['Activate_New_Pass_Error']);
	if(!isset($_GET['key'])) error_msg($lang['Error'], $lang['Activate_New_Pass_Error']);
	$sql = $db2->query("SELECT `user_id` FROM `_PREFIX_users`
		WHERE `user_id` = :user_id && `user_activation_key` = :key
		LIMIT 1",
		array(
			":user_id" => $_GET['user_id'],
			":key" => $_GET['key'],
		)
	);
	if($result = $sql->fetch())
	{
		$db2->query("UPDATE `_PREFIX_users`
			SET `user_password` = `new_password`,
			`user_activation_key` = '',
			`user_password_reset_request` = '',
			`user_new_password` = ''
			 WHERE `user_id` = :user_id",
			array(
				":user_id" => $_GET['user_id']
			)
		);
		info_box($lang['Activation_Successful'], $lang['New_Pass_Activation_Successful_Msg'], "login.php");
	}
	else
	{
		error_msg($lang['Error'], $lang['Activate_New_Pass_Error']);
	}


}
else
{
	if($user['user_id'] > 0)
	{
		error_msg($lang['Error'], sprintf($lang['Already_Logged_in'], $user['username'], "<a href=\"login.php?func=logout\">", "</a>"), "index.php");
	}
	if(isset($_POST['Submit']))
	{
		CSRF::validate();

		$user_id = User::check($_POST['UserName'], $_POST['PassWord']);
		$oUser = User::findUser($user_id);

		if($user_id > -1)
		{
			setcookie("UserName", $oUser->getUsername(), time()+604800);
			setcookie("Password", "What happens after midnight stays secret :^)", time()+604800);
			$_SESSION['user_id'] = $oUser->getId();
			
			$db2->query("UPDATE `_PREFIX_sessions`
				SET `user_id` = :user_id
				WHERE `ip` = :remote_ip && `session_id` = :session_id",
				array(
					":user_id" => $oUser->getId(),
					":remote_ip" => $_SERVER['REMOTE_ADDR'],
					":session_id" => session_id()
				)
			);
			info_box($lang['Login'], sprintf($lang['Successful_Login_Msg'], $oUser->getUsername()), "index.php");

		}
		else
		{
			info_box($lang['Error'], $lang['Invalid_Login_Msg'], "login.php");
		}
	}
	else
	{
		$page_master = new Template("login.tpl");
		$page_master->setVars(array(
			"CSRF_TOKEN" => CSRF::getHTML()
		));

		$page_title = $config['site_name'] . " &raquo; " . $lang['Login'];
		outputPage($page_master);
	}
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright � 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
