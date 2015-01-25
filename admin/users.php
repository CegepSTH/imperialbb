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

if(!isset($_GET['func'])) $_GET['func'] = "";

if($_GET['func'] == "edit")
{
	if(isset($_POST['username']))
	{
		$db2->query("SELECT * FROM _PREFIX_users WHERE `username`=:uname LIMIT 1", array(":uname" => $_POST['username']));

		if($result = $db2->fetch())
		{
			$theme->new_file("edit_user", "edit_user.tpl");
			$theme->replace_tags("edit_user", array(
				"USER_ID" => $result['user_id'],
				"USERNAME" => $result['username'],
				"EMAIL" => $result['user_email'],
				"SIGNATURE" => $result['user_signature'],
				"AIM" => $result['user_aim'],
				"ICQ" => $result['user_icq'],
				"MSN" => $result['user_msn'],
				"YAHOO" => $result['user_yahoo']
			));

			$db2->query("SELECT * FROM _PREFIX_usergroups");

			while($ug_result = $db2->fetch())
			{
				$theme->insert_nest("edit_user", "usergroup_option", array(
					"UG_ID" => $ug_result['id'],
					"UG_NAME" => $ug_result['name'],
					"UG_SELECTED" => ($ug_result['id'] == $result['user_usergroup']) ? "selected=\"SELECTED\"" : ""
				));
				$theme->add_nest("edit_user", "usergroup_option");
			}
			
			$db2->query("SELECT * FROM `_PREFIX_ranks`");
	
			while($rank_result = $db2->fetch())
			{
				$theme->insert_nest("edit_user", "rank_option", array(
					"RANK_ID" => $rank_result['rank_id'],
					"RANK_NAME" => $rank_result['rank_name'],
					"RANK_SELECTED" => ($rank_result['rank_id'] == $result['user_rank']) ? "selected=\"SELECTED\"" : ""
				));
				$theme->add_nest("edit_user", "rank_option");
			}
			
			$user_levels = array($lang['Administrator'] => "5", $lang['Moderator'] => "4", $lang['Registered'] => "3", $lang['Validating'] => "2", $lang['Guest'] => "1", $lang['Banned'] => "0");
			foreach($user_levels as $ul_name => $ul_id)
			{
				$theme->insert_nest("edit_user", "user_level_option", array(
					"UL_ID" => $ul_id,
					"UL_NAME" => $ul_name,
					"UL_SELECTED" => ($ul_id == $result['user_level']) ? "SELECTED" : ""
				));
				$theme->add_nest("edit_user", "user_level_option");
			}

			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("edit_user");

			//
			// Output the page footer
			//
			include($root_path . "includes/page_footer.php");

		}
		else
		{
			error_msg($lang['Error'], $lang['Invalid_User_Id']);
		}
	}
	else if(isset($_GET['user_id']))
	{
		$error = "";
		if(empty($_POST['Username']))
		{
			$error .= sprintf($lang['No_x_content'], strtolower($lang['Username'])) . "<br />";
		}
		if(strlen($_POST['PassWord']) > 4 && strlen($_POST['PassWord']) < 0)
		{
			$error .= $lang['Password_Too_Short'] . "<br />";
		}
		else if($_POST['PassWord'] != $_POST['Pass2'])
		{
			$error .= $lang['Passwords_Dont_Match'] . "<br />";
		}
		if(!preg_match("#(.*?)@(.*?).(.*?)#", $_POST['Email']))
		{
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
			
			$db2->query("SELECT * FROM `_PREFIX_usergroups`");
			
			while($ug_result = $db2->fetch())
			{
				$theme->insert_nest("edit_user", "usergroup_option", array(
					"UG_ID" => $ug_result['id'],
					"UG_NAME" => $ug_result['name'],
					"UG_SELECTED" => ($ug_result['id'] == $_POST['usergroup']) ? "selected=\"SELECTED\"" : ""
				));
				$theme->add_nest("edit_user", "usergroup_option");
			}
			
			$db2->query("SELECT * FROM `_PREFIX_ranks`");
			
			while($rank_result = $db2->fetch())
			{
				$theme->insert_nest("edit_user", "rank_option", array(
					"RANK_ID" => $rank_result['rank_id'],
					"RANK_NAME" => $rank_result['rank_name'],
					"RANK_SELECTED" => ($rank_result['rank_id'] == $_POST['rank']) ? "selected=\"SELECTED\"" : ""
				));
				$theme->add_nest("edit_user", "rank_option");
			}

			$user_levels = array($lang['Administrator'] => "5", $lang['Moderator'] => "4", $lang['Registered'] => "3", $lang['Validating'] => "2", $lang['Guest'] => "1", $lang['Banned'] => "0");
			foreach($user_levels as $ul_name => $ul_id)
			{
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

			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("edit_user");

			//
			// Output the page footer
			//
			include($root_path . "includes/page_footer.php");

		}
		else
		{
			$values = array(":uname" => $_POST['Username'], ":uemail" => $_POST['Email'], ":usign" => $_POST['signature'], 
			":uaim" => $_POST['aim'], ":uicq" => $_POST['icq'], ":umsn" => $_POST['msn'], ":uyahoo" => $_POST['yahoo'],
			":ugroup" => $_POST['usergroup'], ":urank" => $_POST['rank'], ":ulevel" => $_POST['user_level'], ":uid" => $_GET['user_id']);
			
			$sql = "UPDATE `_PREFIX_users` SET `username`=:uname, `user_email`=:uemail, `user_signature`=:usign";

			if(strlen($_POST['PassWord']) > 0) {
				$values[":upasswd"] = md5(md5($_POST['PassWord']));
				$sql .= ", `user_password`=:upasswd";
			}
			
			$sql .= ", `user_aim`=:uaim, `user_icq`=:uicq, `user_msn`=:umsn, `user_yahoo`=:uyahoo, `user_usergroup`=:ugroup, `user_rank`=:urank, `user_level`=:ulevel
			WHERE `user_id`=:uid";
			
			$db2->query($sql, $values);
			
			if($db2->getError() == "" || $db2->getError() == null) {
				info_box($lang['Edit_User'], $lang['User_Updated_Msg'], "main.php");
			}
		}
	}
	else
	{
		$theme->new_file("edit_user", "edit_user_start.tpl");
		$db2->query("SELECT `username` FROM `_PREFIX_users` WHERE `user_id` > 0 ORDER BY `user_id` DESC LIMIT 50");
		while($result = $db2->fetch())
		{
			$theme->insert_nest("edit_user", "user_option", array(
				"USERNAME" => $result['username']
			));
			$theme->add_nest("edit_user", "user_option");
		}
		
		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("edit_user");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");
	}

}
else if($_GET['func'] == "delete")
{
	if(!isset($_POST['username']) && !isset($_GET['username']))
	{
		$theme->new_file("delete_user", "delete_user.tpl");
		$db2->query("SELECT `username` FROM `_PREFIX_users` WHERE `user_id` > 0 ORDER BY `user_id` DESC LIMIT 25");
		while($result = $db2->fetch())
		{
			$theme->insert_nest("delete_user", "user_option", array(
				"USERNAME" => $result['username']
			));
			$theme->add_nest("delete_user", "user_option");
		}
		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("delete_user");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");
	}
	else if(!isset($_POST['confirm']) || $_POST['confirm'] != "Yes")
	{
		confirm_msg($lang['Delete_User'], sprintf($lang['User_Confirm_Delete'], $_POST['username']), "users.php?func=delete&username=".$_POST['username']."", "users.php?func=delete");
	}
	else
	{
		$db2->query("DELETE FROM `_PREFIX_users` WHERE `username`=:uname LIMIT 1", array(":uname" => $_GET['username']));
		info_box($lang['Delete_User'], $lang['User_Deleted_Msg'], "main.php");
	}
}

?>
