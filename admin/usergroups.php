<?php

/**********************************************************
*
*			admin/mods.php
*
*		ImperialBB 2.X.X - By Nate and James
*
*		     (C) The IBB Group
*
***********************************************************/

define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
require_once($root_path . "includes/common.php");
$language->add_file("admin/usergroups");

if(!isset($_GET['func'])) $_GET['func'] = "";

if($_GET['func'] == "permissions")
{
	if(!isset($_GET['id']) && !isset($_POST['id']))
	{
		$theme ->new_file("ug_select", "ug_select.tpl");
		$db2->query("SELECT * FROM `_PREFIX_usergroups`");

		while($result = $db2->fetch())
		{
			$theme->insert_nest("ug_select", "ug_select", array(
				"GROUP_ID" => $result['id'],
				"GROUP_NAME" => $result['name']
			));
			$theme->add_nest("ug_select", "ug_select");
		}
		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("ug_select");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");
	}
	else
	{
		if(isset($_POST['Submit']))
		{
			$db2->query("SELECT * FROM `_PREFIX_forums`");
			
			while($result = $db2->fetch())
			{
				$forum_id = $result['forum_id'];

				if($_POST[$forum_id]['Read'] == "2" && $_POST[$forum_id]['Post'] == "2" && $_POST[$forum_id]['Reply'] == "2" && $_POST[$forum_id]['Poll'] == "2" && $_POST[$forum_id]['Create_Poll'] == "2" && $_POST[$forum_id]['Mod'] == "2")
				{
					$values = array(":id" => $_GET['id'], ":fid" => $forum_id);
					$db2->query("DELETE FROM `_PREFIX_ug_auth` WHERE `usergroup`=:id && `ug_forum_id`=:fid", $values);
				}
				else
				{
					$values = array(":id" => $_GET['id'], ":fid" => $forum_id, ":fread" => $_POST[$forum_id]['Read'], 
						":fpost" => $_POST[$forum_id]['Post'], ":freply" => $_POST[$forum_id]['Reply'], ":cpoll" => $_POST[$forum_id]['Create_Poll'],
						":poll" => $_POST[$forum_id]['Poll'], ":mod" => $_POST[$forum_id]['Mod'], ":idd" => $_GET['id'], ":fidd" => $forum_id);
						
					$ug_sql = $db2->query("SELECT * FROM `_PREFIX_ug_auth` WHERE `usergroup`=:id && `ug_forum_id`=:fid", $values);
					
					if(!$db2->fetch()) {
						$db2->query("INSERT INTO `_PREFIX_ug_auth`
									VALUES(:id, :fid, :fread, :fpost, :freply, :cpoll, :poll, :mod)", $values);
					} else {
						$db2->query("UPDATE `_PREFIX_ug_auth`
						SET `usergroup`=:id, `ug_forum_id`=:fid, `ug_read`=:fread, `ug_post`=:fpost, `ug_reply`=:freply, `ug_create_poll`=:fcpoll, `ug_poll`=:poll, `ug_mod`=:mod
						WHERE `usergroup`=:idd && `ug_forum_id`=:fidd", $values);
					}
				}
			}
			info_box($lang['Usergroup_Permissions'], $lang['Usergroup_Perm_Msg'], "usergroups.php?func=permissions");

			}
			else
			{
				$theme->new_file("ug_auth", "ug_auth.tpl");
				$db2->query("SELECT * FROM `_PREFIX_usergroups` WHERE `id`=:uid", array(":uid" => $_POST['id']));
				while($result = $db2->fetch())
				{
					$theme->replace_tags("ug_auth", array(
					"GROUP_ID" => $result['id'],
					"GROUP_NAME" => $result['name']
					));
				}

				$pdo_forums = $db2->query("SELECT * FROM `_PREFIX_forums` ORDER BY `forum_id` DESC");
					
				while($result = $pdo_forums->fetch()) {
					$values = array(":uid" => $_POST['id'], ":fid" => $result['forum_id']);
					$db2->query("SELECT * FROM `_PREFIX_ug_auth` WHERE `usergroup`=:uid && `ug_forum_id`=:fid", $values);
					
					if($result_auth = $db2->fetch())
					{
						$theme->insert_nest("ug_auth", "forum_permissions", array(
							"FORUM_ID" => $result['forum_id'],
							"FORUM_NAME" => $result['forum_name'],
							"READ_TRUE" => ($result_auth['ug_read'] == 1) ? "SELECTED" : "",
							"READ_FALSE" => ($result_auth['ug_read'] == 0) ? "SELECTED" : "",
							"READ_DEFAULT" => ($result_auth['ug_read'] == 2) ? "SELECTED" : "",
							"POST_TRUE" => ($result_auth['ug_post'] == 1) ? "SELECTED" : "",
							"POST_FALSE" => ($result_auth['ug_post'] == 0) ? "SELECTED" : "",
							"POST_DEFAULT" => ($result_auth['ug_post'] == 2) ? "SELECTED" : "",
							"REPLY_TRUE" => ($result_auth['ug_reply'] == 1) ? "SELECTED" : "",
							"REPLY_FALSE" => ($result_auth['ug_reply'] == 0) ? "SELECTED" : "",
							"REPLY_DEFAULT" => ($result_auth['ug_reply'] == 2) ? "SELECTED" : "",
							"POLL_TRUE" => ($result_auth['ug_poll'] == 1) ? "SELECTED" : "",
							"POLL_FALSE" => ($result_auth['ug_poll'] == 0) ? "SELECTED" : "",
							"POLL_DEFAULT" => ($result_auth['ug_poll'] == 2) ? "SELECTED" : "",
							"CREATE_POLL_TRUE" => ($result_auth['ug_create_poll'] == 1) ? "SELECTED" : "",
							"CREATE_POLL_FALSE" => ($result_auth['ug_create_poll'] == 0) ? "SELECTED" : "",
							"CREATE_POLL_DEFAULT" => ($result_auth['ug_create_poll'] == 2) ? "SELECTED" : "",
							"MOD_TRUE" => ($result_auth['ug_mod'] == 1) ? "SELECTED" : "",
							"MOD_FALSE" => ($result_auth['ug_mod'] == 0) ? "SELECTED" : "",
							"MOD_DEFAULT" => ($result_auth['ug_mod'] == 2) ? "SELECTED" : ""
						));
						$theme->add_nest("ug_auth", "forum_permissions");
					}
					else
					{
						$theme->insert_nest("ug_auth", "forum_permissions", array(
						"FORUM_ID" => $result['forum_id'],
						"FORUM_NAME" => $result['forum_name'],
						"READ_TRUE" => "",
						"READ_FALSE" => "",
						"READ_DEFAULT" => "SELECTED",
						"POST_TRUE" => "",
						"POST_FALSE" => "",
						"POST_DEFAULT" => "SELECTED",
						"REPLY_TRUE" => "",
						"REPLY_FALSE" => "",
						"REPLY_DEFAULT" => "SELECTED",
						"POLL_TRUE" => "",
						"POLL_FALSE" =>  "",
						"POLL_DEFAULT" => "SELECTED",
						"CREATE_POLL_TRUE" => "",
						"CREATE_POLL_FALSE" => "",
						"CREATE_POLL_DEFAULT" => "SELECTED",
						"MOD_TRUE" => "",
						"MOD_FALSE" => "",
						"MOD_DEFAULT" =>"SELECTED"
						));
						$theme->add_nest("ug_auth", "forum_permissions");
					}
				}
				//
				// Output the page header
				//
				include($root_path . "includes/page_header.php");

				//
				// Output the main page
				//
				$theme->output("ug_auth");

				//
				// Output the page footer
				//
				include($root_path . "includes/page_footer.php");
			}
        }
}
else if($_GET['func'] == "add")
{
	if(isset($_POST['submit']))
	{
		$error = "";

		if(!isset($_POST['name']) || empty($_POST['name']))
		{
			$error .= sprintf($lang['No_x_content'], $lang['usergroup_name']);
		}

		if(!empty($error))
		{
			$theme->new_file("add_ug", "add_ug.tpl");
			$theme->replace_tags("add_ug", array(
				"ACTION" => "Create",
				"NAME" => $_POST['name'],
				"DESC" => $_POST['desc']
			));

			$theme->insert_nest("add_ug", "error", array(
				"ERROR" => $error
			));
			$theme->add_nest("add_ug", "error");

			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("add_ug");

			//
			// Output the page footer
			//
			include($root_path . "includes/page_footer.php");

		}
		else
		{

			$values = array(":uname" => $_POST['name'], ":desc" => $_POST['desc']);
			$db2->query("INSERT INTO `_PREFIX_usergroups` (`name`, `desc`) VALUES (:uname, :desc)", $values);

			info_box($lang['Create_Usergroup'], $lang['Usergroup_Created_Msg'], "usergroups.php");
		}
	}
	else
	{
		$theme->new_file("add_ug", "add_ug.tpl");
		$theme->replace_tags("add_ug", array(
			"ACTION" => $lang['Create'],
			"NAME" => "",
			"DESC" => ""
		));
		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("add_ug");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");
	}
} else if($_GET['func'] == "edit")
{
	if(isset($_POST['submit']))
	{
		$error = "";
		if(!isset($_POST['name']) || empty($_POST['name']))
		{
			$error .= sprintf($lang['No_x_content'], $lang['usergroup_name']);
		}
		if(!empty($error))
		{
			$theme->new_file("edit_ug", "add_ug.tpl");
			$theme->replace_tags("edit_ug", array(
				"ACTION" => $lang['Edit'],
				"NAME" => $_POST['name'],
				"DESC" => $_POST['desc']
			));

			$theme->insert_nest("edit_ug", "error", array(
				"ERROR" => $error
			));
			$theme->add_nest("edit_ug", "error");

			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("edit_ug");

			//
			// Output the page footer
			//
			include($root_path . "includes/page_footer.php");

		}
		else
		{
			$values = array(":name" => $_POST['name'], ":desc" => $_POST['desc'], ":id" => $_GET['id']);
			$db2->query("UPDATE `_PREFIX_usergroups` SET`name`=:name, `desc`=:desc WHERE `id`=:id");
			info_box($lang['Edit_Usergroup'], $lang['Usergroup_Updated_Msg'], "usergroups.php");
		}
	}
	else
	{
		$db2->query("SELECT * FROM `_PREFIX_usergroups` WHERE `id`=:id", array(":id" => $_GET['id']));
		if($result = $db2->fetch())
		{
			$theme->new_file("add_ug", "add_ug.tpl");
			$theme->replace_tags("add_ug", array(
				"ACTION" => $lang['Edit'],
				"NAME" => $result['name'],
				"DESC" => $result['desc']
			));
			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("add_ug");

			//
			// Output the page footer
			//
			include($root_path . "includes/page_footer.php");
		}
		else
		{
			error_msg($lang['Error'], $lang['Invalid_Usergroup_Id']);
		}
	}
} else if($_GET['func'] == "delete") {
	if(isset($_POST['submit'])) {
		if($_POST['new_ug'] != "-1") {
			if(!isset($_POST['new_ug'])) error_msg($lang['Error'], $lang['Invalid_Usergroup_Id']);

			$db2->query("SELECT `id` FROM `_PREFIX_usergroups` WHERE `id`=:nug", array(":nug" => $_POST['new_ug']));
			if($result = $db2->fetch()) {
				$values = array(":rid" => $result['id'], ":id" => $_GET['id']);
				$db2->query("UPDATE `_PREFIX_users` SET `user_usergroup`=:rid WHERE `user_usergroup`=:id", $values);
			}
			else
			{
				error_msg($lang['Error'], $lang['Invalid_Usergroup_Id']);
			}
		}
		else
		{
			$db2->query("UPDATE `_PREFIX_users` SET `user_usergroup` = '0' WHERE `user_usergroup`=:id", array(":id" => $_GET['id']));
		}

		$db2->query("DELETE FROM `_PREFIX_usergroups` WHERE `id`=:id", array(":id" => $_GET['id']));
		$db2->query("DELETE FROM `_PREFIX_ug_auth` WHERE `usergroup`=:id", array(":id" => $_GET['id']));
		info_box($lang['Delete_Usergroup'], $lang['Usergroup_Deleted_Msg'], "usergroups.php");
	}
	else
	{
		$theme->new_file("delete_ug", "delete_ug.tpl");
		$db2->query("SELECT `name` FROM `_PREFIX_usergroups` WHERE `id`=:id", array(":id" => $_GET['id']));
		
		if($result = $db->fetch())
		{
			$theme->replace_tags("delete_ug", array(
				"UG_NAME" => $result['name']
			));
		}
		else
		{
			error_msg($lang['Error'], $lang['Invalid_Usergroup_Id']);
		}

		$db2->query("SELECT `id`, `name` FROM `_PREFIX_usergroups` WHERE `id`!=:id", array(":id" => $_GET['id']));
		while($result = $db2->fetch())
		{
			$theme->insert_nest("delete_ug", "ug_row", array(
				"ID" => $result['id'],
				"NAME" => $result['name']
			));
			$theme->add_nest("delete_ug", "ug_row");
		}
		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("delete_ug");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");
	}
}
else
{
	$theme->new_file("manage", "manage_ug.tpl");
	$db2->query("SELECT * FROM `_PREFIX_usergroups`");
	while($result = $db2->fetch()) {
		$theme->insert_nest("manage", "ug_row", array(
			"ID" => $result['id'],
			"NAME" => $result['name'],
			"DESC" => $result['desc']
		));
		$theme->add_nest("manage", "ug_row");
	}
	//
	// Output the page header
	//
	include($root_path . "includes/page_header.php");

	//
	// Output the main page
	//
	$theme->output("manage");

	//
	// Output the page footer
	//
	include($root_path . "includes/page_footer.php");
}
?>
