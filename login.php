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
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

define("IN_IBB", 1);

$root_path = "./";
$ignore_offline = true;
include($root_path . "includes/common.php");

$language->add_file("login");

if(!isset($_GET['func'])) $_GET['func'] = "";


if($_GET['func'] == "activate")
{
	if(!isset($_GET['user_id'])) error_msg($lang['Error'], $lang['Activation_Error_Msg']);
	if(!isset($_GET['key'])) error_msg($lang['Error'], $lang['Activation_Error_Msg']);
	$sql = $db->query("SELECT * FROM `".$db_prefix."users` WHERE `user_id` = '".$_GET['user_id']."' && `user_activation_key` = '".$_GET['key']."' LIMIT 1");
	if($result = $db->fetch_array($sql))
	{
		if($result['user_level'] != 2)
		{
			error_msg($lang['Activation_Error'], $lang['Already_Activated_Msg']);
		}
		$db->query("UPDATE `".$db_prefix."users` SET `user_level` = '3', `user_activation_key` = '' WHERE `user_id` = '".$_GET['user_id']."'");
		info_box($lang['Activation_Successful'], sprintf($lang['Activation_Successful_Msg'], $result['username']), "login.php");
	}
	else
	{
		error_msg($lang['Activation_Error'], $lang['Activation_Error_Msg']);
	}
}
else if($_GET['func'] == "logout")
{
	setcookie("UserName");
	setcookie("Password");
	$_SESSION['user_id'] = -1;
	session_regenerate_id();
	$db->query("DELETE FROM `".$db_prefix."sessions` WHERE `ip` = '".$_SERVER['REMOTE_ADDR']."'");
	info_box($lang['Logout'], $lang['Logged_Out_Msg'], "index.php");
}
else if($_GET['func'] == "forgotten_pass")
{
	if(isset($_POST['Submit']))
	{
		if(!isset($_POST['username']) || !isset($_POST['email'])) error_msg($lang['Error'], $lang['Invalid_username_or_email']);
		$query = $db->query("SELECT `user_id`, `username`, `user_email` FROM `".$db_prefix."users` WHERE `username` = '".$_POST['username']."' AND `user_email` = '".$_POST['email']."'");

		if($result = $db->fetch_array($query))
		{
			$key = generate_activate_key();
			$password = generate_activate_key(7);
			$db->query("UPDATE `".$db_prefix."users` SET `user_activation_key` = '".$key."', `user_new_password` = '".md5(md5($password))."', `user_password_reset_request` = '".time()."' WHERE `user_id` = '".$result['id']."'");
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

		$page_title = $config['site_name'] . " &raquo; " . $lang['Forgotten_Password'];

		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("forgotten_password");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");
	}

}
else if($_GET['func'] == "activate_new_pass")
{
	if(!isset($_GET['user_id'])) error_msg($lang['Error'], $lang['Activate_New_Pass_Error']);
	if(!isset($_GET['key'])) error_msg($lang['Error'], $lang['Activate_New_Pass_Error']);
	$sql = $db->query("SELECT `user_id` FROM `".$db_prefix."users` WHERE `user_id` = '".$_GET['user_id']."' && `user_activation_key` = '".$_GET['key']."' LIMIT 1");
	if($result = $db->fetch_array($sql))
	{
		$db->query("UPDATE `".$db_prefix."users` SET `user_password` = `new_password`, `user_activation_key` = '', `user_password_reset_request` = '', `user_new_password` = '' WHERE `user_id` = '".$_GET['user_id']."'");
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
		$user_sql = $db->query("SELECT * FROM `".$db_prefix."users` WHERE `username` = '"  . $_POST['UserName'] .  "' && `user_password` = '"  . md5(md5($_POST['PassWord'])) .  "' LIMIT 1");
		if($user_result = $db->fetch_array($user_sql))
		{
			setcookie("UserName", $user_result['username'], time()+604800);
			setcookie("Password", $user_result['user_password'], time()+604800);
			$_SESSION['user_id'] = $user_result['user_id'];
			$db->query("UPDATE `".$db_prefix."sessions` SET `user_id` = '".$user_result['user_id']."' WHERE `ip` = '".$_SERVER['REMOTE_ADDR']."' && `session_id` = '".session_id()."'");
			info_box($lang['Login'], sprintf($lang['Successful_Login_Msg'], $user_result['username']), "index.php");

		}
		else
		{
			info_box($lang['Error'], $lang['Invalid_Login_Msg'], "login.php");
		}
	}
	else
	{
		$theme->new_file("login", "login.tpl", "");
		$page_title = $config['site_name'] . " &raquo; " . $lang['Login'];

		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("login");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");
	}
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>