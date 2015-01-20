<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: users.php                                                  # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
include($root_path . "includes/common.php");

if(!isset($_GET['func'])) $_GET['func'] = "";

if($_GET['func'] == "edit")
{
	if(isset($_POST['username']))
	{

		$sql = $db->query("SELECT * FROM `".$db_prefix."users` WHERE `username` = '".$_POST['username']."' LIMIT 1");
		if($result = $db->fetch_array($sql))
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

			$ug_sql = $db->query("SELECT * FROM `".$db_prefix."usergroups`");
			while($ug_result = $db->fetch_array($ug_sql))
			{
				$theme->insert_nest("edit_user", "usergroup_option", array(
					"UG_ID" => $ug_result['id'],
					"UG_NAME" => $ug_result['name'],
					"UG_SELECTED" => ($ug_result['id'] == $result['user_usergroup']) ? "selected=\"SELECTED\"" : ""
				));
				$theme->add_nest("edit_user", "usergroup_option");
			}

			$rank_sql = $db->query("SELECT * FROM `".$db_prefix."ranks`");
			while($rank_result = $db->fetch_array($rank_sql))
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
			$ug_sql = $db->query("SELECT * FROM `".$db_prefix."usergroups`");
			while($ug_result = $db->fetch_array($ug_sql))
			{
				$theme->insert_nest("edit_user", "usergroup_option", array(
					"UG_ID" => $ug_result['id'],
					"UG_NAME" => $ug_result['name'],
					"UG_SELECTED" => ($ug_result['id'] == $_POST['usergroup']) ? "selected=\"SELECTED\"" : ""
				));
				$theme->add_nest("edit_user", "usergroup_option");
			}

			$rank_sql = $db->query("SELECT * FROM `".$db_prefix."ranks`");
			while($rank_result = $db->fetch_array($rank_sql))
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
			$sql = "UPDATE `".$db_prefix."users` SET `username` = '".$_POST['Username']."', `user_email` = '".$_POST['Email']."', `user_signature` = '".$_POST['signature']."'";

			if(strlen($_POST['PassWord']) > 0) $sql .= ", `user_password`='".md5(md5($_POST['PassWord']))."'";
			$sql .= ", `user_aim` = '".$_POST['aim']."', `user_icq` = '".$_POST['icq']."', `user_msn` ='".$_POST['msn']."', `user_yahoo` ='".$_POST['yahoo']."', `user_usergroup` ='".$_POST['usergroup']."', `user_rank` = '".$_POST['rank']."', `user_level` = '".$_POST['user_level']."'
					WHERE `user_id` = '".$_GET['user_id']."'";

			if($db->query($sql)) {
				info_box($lang['Edit_User'], $lang['User_Updated_Msg'], "main.php");
			}
		}
	}
	else
	{
		$theme->new_file("edit_user", "edit_user_start.tpl");
		$sql = $db->query("SELECT `username` FROM `".$db_prefix."users` WHERE `user_id` > 0 ORDER BY `user_id` DESC LIMIT 50");
		while($result = $db->fetch_array($sql))
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
		$sql = $db->query("SELECT `username` FROM `".$db_prefix."users` WHERE `user_id` > 0 ORDER BY `user_id` DESC LIMIT 25");
		while($result = $db->fetch_array($sql))
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
		$db->query("DELETE FROM `".$db_prefix."users` WHERE `username` = '".$_GET['username']."' LIMIT 1");
		info_box($lang['Delete_User'], $lang['User_Deleted_Msg'], "main.php");
	}
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>