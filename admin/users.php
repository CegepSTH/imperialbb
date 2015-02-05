<?php

/**********************************************************
*
*			admin/users.php
*
*		ImperialBB 2.X.X - By Nate and James
*
*		     (C) The IBB Group
*
***********************************************************/

define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
require_once($root_path."includes/common.php");
require_once($root_path."models/user.php");

if(!isset($_GET['func'])) $_GET['func'] = "";

if($_GET['func'] == "edit")
{
	if(isset($_POST['username']))
	{
		// Fetch user.
		$oUser = User::findUser(trim($_POST['username']));
		
		if($oUser == null || is_null($oUser)) {
			error_msg($lang['Error'], $lang['Invalid_User_Id']);
			exit();
		}


		$ims = $oUser->getMessengers();
		$theme->new_file("edit_user", "edit_user.tpl");
		$theme->replace_tags("edit_user", array(
			"USER_ID" => $oUser->getId(),
			"USERNAME" => $oUser->getUsername(),
			"EMAIL" => $oUser->getEmail(),
			"SIGNATURE" => $oUser->getSignature(),
			"AIM" => $ims["aim"],
			"ICQ" => $ims["icq"],
			"MSN" => $ims["msn"],
			"YAHOO" => $ims["yahoo"]
		));
		
		// Fetch usergroups.
		$db2->query("SELECT * FROM _PREFIX_usergroups");
		while($ug_result = $db2->fetch()) {
			$theme->insert_nest("edit_user", "usergroup_option", array(
				"UG_ID" => $ug_result['id'],
				"UG_NAME" => $ug_result['name'],
				"UG_SELECTED" => ($ug_result['id'] == $oUser->getUsergroupId()) ? "selected=\"SELECTED\"" : ""
			));
			$theme->add_nest("edit_user", "usergroup_option");
		}

		// Fetch ranks.
		$db2->query("SELECT * FROM `_PREFIX_ranks`");
		while($rank_result = $db2->fetch()) {
			$theme->insert_nest("edit_user", "rank_option", array(
				"RANK_ID" => $rank_result['rank_id'],
				"RANK_NAME" => $rank_result['rank_name'],
				"RANK_SELECTED" => ($rank_result['rank_id'] == $oUser->getRankId()) ? "selected=\"SELECTED\"" : ""
			));
			$theme->add_nest("edit_user", "rank_option");
		}
		
		// Fetch user levels.
		$user_levels = array($lang['Administrator'] => "5", $lang['Moderator'] => "4", $lang['Registered'] => "3", $lang['Validating'] => "2", $lang['Guest'] => "1", $lang['Banned'] => "0");
		foreach($user_levels as $ul_name => $ul_id)	{
			$theme->insert_nest("edit_user", "user_level_option", array(
				"UL_ID" => $ul_id,
				"UL_NAME" => $ul_name,
				"UL_SELECTED" => ($ul_id == $oUser->getLevel()) ? "SELECTED" : ""
			));

			$theme->add_nest("edit_user", "user_level_option");
		}

		include_once($root_path . "includes/page_header.php");
		$theme->output("edit_user");
		include_once($root_path . "includes/page_footer.php");
	}
	else if(isset($_GET['user_id']))
	{
		// Edit user.
		$error = "";
		if(empty($_POST['Username'])) {
			$error .= sprintf($lang['No_x_content'], strtolower($lang['Username'])) . "<br />";
		}
		
		if(strlen($_POST['PassWord']) > 4 && strlen($_POST['PassWord']) < 0) {
			$error .= $lang['Password_Too_Short'] . "<br />";
		} else if($_POST['PassWord'] != $_POST['Pass2']) {
			$error .= $lang['Passwords_Dont_Match'] . "<br />";
		}
		
		if(!preg_match("#(.*?)@(.*?).(.*?)#", $_POST['Email'])) {
			$error .= $lang['Invalid_Email_Address'] . "<br />";
		}

		if(strlen($error) > 0)
		{
			$theme->new_file("edit_user", "edit_user.tpl", "");
			$theme->replace_tags("edit_user", array(
				"USER_ID" => $_GET['user_id'],
				"USERNAME" => $_POST['Username'],
				"EMAIL" => $_POST['Email'],
				"SIGNATURE" => $_POST['signature'],
				"AIM" => $_POST['aim'],
				"ICQ" => $_POST['icq'],
				"MSN" => $_POST['msn'],
				"YAHOO" => $_POST['yahoo']
			));
			
			// Fetch usergroups.
			$db2->query("SELECT * FROM `_PREFIX_usergroups`");
			while($ug_result = $db2->fetch()) {
				$theme->insert_nest("edit_user", "usergroup_option", array(
					"UG_ID" => $ug_result['id'],
					"UG_NAME" => $ug_result['name'],
					"UG_SELECTED" => ($ug_result['id'] == $_POST['usergroup']) ? "selected=\"SELECTED\"" : ""
				));
				$theme->add_nest("edit_user", "usergroup_option");
			}
			
			// Fetch
			$db2->query("SELECT * FROM `_PREFIX_ranks`");
			while($rank_result = $db2->fetch()) {
				$theme->insert_nest("edit_user", "rank_option", array(
					"RANK_ID" => $rank_result['rank_id'],
					"RANK_NAME" => $rank_result['rank_name'],
					"RANK_SELECTED" => ($rank_result['rank_id'] == $_POST['rank']) ? "selected=\"SELECTED\"" : ""
				));
				$theme->add_nest("edit_user", "rank_option");
			}

			// Fetch level.
			$user_levels = array($lang['Administrator'] => "5", $lang['Moderator'] => "4", $lang['Registered'] => "3", $lang['Validating'] => "2", $lang['Guest'] => "1", $lang['Banned'] => "0");
			foreach($user_levels as $ul_name => $ul_id) {
				$theme->insert_nest("edit_user", "user_level_option", array(
					"UL_ID" => $ul_id,
					"UL_NAME" => $ul_name,
					"UL_SELECTED" => ($ul_id == $_POST['user_level']) ? "SELECTED" : ""
				));
				$theme->add_nest("edit_user", "user_level_option");
			}
			$theme->insert_nest("edit_user", "error", array(
				"ERRORS" => $error
			));
			$theme->add_nest("edit_user", "error");

			include_once($root_path . "includes/page_header.php");
			$theme->output("edit_user");
			include_once($root_path . "includes/page_footer.php");
		}
		else
		{
			$oUser = User::findUser($_GET['user_id']);
			
			$oUser->setUsername($_POST['Username']); 
			$oUser->setMail($_POST['Email']);
			$oUser->setSignature($_POST['signature']);
			$oUser->setMessengers(array("aim" => $_POST['aim'], "icq" => $_POST['icq'], "msn" => $_POST['msn'], "yahoo" => $_POST['yahoo']));
			$oUser->setUsergroupId($_POST['usergroup']);
			$oUser->setRankId($_POST['rank']);
			$oUser->setLevel($_POST['user_level']); 
			
			$pass_ok = true;
			if(strlen($_POST['PassWord']) > 0) {
				$oUser->setPassword($_POST['PassWord']); 
				$pass_ok = $oUser->updatePassword();
			}
			
			$ok = $oUser->update();
			if($ok && $pass_ok) {
				info_box($lang['Edit_User'], $lang['User_Updated_Msg'], "main.php");
			}
		}
	}
	else
	{
		$theme->new_file("edit_user", "edit_user_start.tpl");
		$db2->query("SELECT `username` FROM `_PREFIX_users` WHERE `user_id` > 0 ORDER BY `user_id` DESC LIMIT 50");
		
		while($result = $db2->fetch()) {
			$theme->insert_nest("edit_user", "user_option", array(
				"USERNAME" => $result['username']
			));
			$theme->add_nest("edit_user", "user_option");
		}
		
		include_once($root_path . "includes/page_header.php");
		$theme->output("edit_user");
		include_once($root_path . "includes/page_footer.php");
	}

}
else if($_GET['func'] == "delete")
{
	if(!isset($_POST['username']) && !isset($_GET['username']))
	{
		$theme->new_file("delete_user", "delete_user.tpl");
		$db2->query("SELECT `username` FROM `_PREFIX_users` WHERE `user_id` > 0 ORDER BY `user_id` DESC LIMIT 25");
		
		while($result = $db2->fetch()) {
			$theme->insert_nest("delete_user", "user_option", array(
				"USERNAME" => $result['username']
			));
			$theme->add_nest("delete_user", "user_option");
		}

		include_once($root_path . "includes/page_header.php");
		$theme->output("delete_user");
		include_once($root_path . "includes/page_footer.php");
	}
	else if(!isset($_POST['confirm']) || $_POST['confirm'] != "Yes")
	{
		confirm_msg($lang['Delete_User'], sprintf($lang['User_Confirm_Delete'], $_POST['username']), "users.php?func=delete&username=".$_POST['username']."", "users.php?func=delete");
	}
	else
	{
		User::delete($_GET['username']);
		info_box($lang['Delete_User'], $lang['User_Deleted_Msg'], "main.php");
	}
}

?>
