<?php

/**********************************************************
*
*			admin/language.php
*
*		ImperialBB 2.X.X - By Nate and James
*
*		     (C) The IBB Group
*
***********************************************************/

define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
include($root_path . "includes/common.php");

$language->add_file("admin/language");

if(!isset($_GET['func'])) $_GET['func'] = "";

if($_GET['func'] == "add")
{
	if(isset($_POST['Submit']))
	{
		$error = "";

		if(!isset($_POST['name']) || empty($_POST['name']))
		{
			$error .= $lang['No_Name_Entered_Error'] . "<br />";
		}
		if(!isset($_POST['folder']) || empty($_POST['folder']))
		{
			$error .= $lang['No_Folder_Entered_Error'];
		}
		else
		{
			if(!is_dir($root_path . "/language/".$_POST['folder']."/"))
			{
				$error .= $lang['Invalid_Folder_Entered_Error'];
			}
		}

		if(!empty($error))
		{
			$theme->new_file("add_language", "add_edit_language.tpl");

			$theme->replace_tags("add_language", array(
				"ACTION" => $lang['Add_Language'],
				"NAME" => $_POST['name'],
				"FOLDER" => $_POST['folder'],
				"USABLE" => (isset($_POST['usable'])) ? "checked=\"checked\"" : ""
			));

			$theme->insert_nest("add_language", "error", array(
				"ERROR" => $error
			));
			$theme->add_nest("add_language", "error");

			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("add_language");

			//
			// Output the page footer
			//
			include($root_path . "includes/page_footer.php");
		}
		else
		{
			if(isset($_POST['usable']))
			{
				$usable = "1";
			}
			else
			{
				$usable = "0";
			}
			$db->query("INSERT INTO `".$db_prefix."languages` (`language_name`, `language_folder`, `language_usable`) VALUES ('".$_POST['name']."', '".$_POST['folder']."', '".$usable."')");

			info_box($lang['Add_Language'], $lang['Language_Added_Msg'], "language.php");
		}
	}
	else
	{
		$theme->new_file("add_language", "add_edit_language.tpl");

		$theme->replace_tags("add_language", array(
			"ACTION" => $lang['Add_Language'],
			"NAME" => "",
			"FOLDER" => "",
			"USABLE" => ""
		));

		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("add_language");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");
	}


}
else if($_GET['func'] == "download")
{
	// TODO: DOWNLOAD INSERT SECTION
}
else if($_GET['func'] == "edit")
{
	if(!(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0))
	{
		error_msg($lang['Error'], $lang['Invalid_language_pack_id']);
	}

	if(isset($_POST['Submit']))
	{
		$error = "";

		if(!isset($_POST['name']) || empty($_POST['name']))
		{
			$error .= $lang['No_Name_Entered_Error'] . "<br />";
		}
		if(!isset($_POST['folder']) || empty($_POST['folder']))
		{
			$error .= $lang['No_Folder_Entered_Error'];
		}
		else
		{
			if(!is_dir($root_path . "/language/".$_POST['folder']."/"))
			{
				$error .= $lang['Invalid_Folder_Entered_Error'];
			}
		}

		if(!empty($error))
		{
			$theme->new_file("edit_language", "edit_language.tpl");

			$theme->replace_tags("add_edit_language", array(
				"ACTION" => $lang['Edit_Language'],
				"ID" => $_GET['id'],
				"NAME" => $_POST['name'],
				"FOLDER" => $_POST['folder'],
				"USABLE" => (isset($_POST['usable'])) ? "checked=\"checked\"" : ""
			));

			$theme->insert_nest("edit_language", "error", array(
				"ERROR" => $error
			));
			$theme->add_nest("edit_language", "error");

			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("edit_language");

			//
			// Output the page footer
			//
			include($root_path . "includes/page_footer.php");
		}
		else
		{
			if(isset($_POST['usable']))
			{
				$usable = "1";
			}
			else
			{
				$usable = "0";
			}
			$db->query("UPDATE `".$db_prefix."languages` SET `language_name` = '".$_POST['name']."', `language_folder` = '".$_POST['folder']."', `language_usable` = '".$usable."' WHERE `language_id` = '".$_GET['id']."'");

			info_box($lang['Edit_Language'], $lang['Language_Edited_Msg'], "language.php");
		}


	}
	else
	{
		$theme->new_file("edit_language", "add_edit_language.tpl");

		$query = $db->query("SELECT `language_id`, `language_name`, `language_folder`, `language_usable` FROM `".$db_prefix."languages` WHERE `language_id` = '".$_GET['id']."'");

		if($result = $db->fetch_array($query))
		{
			$theme->replace_tags("edit_language", array(
				"ACTION" => $lang['Edit_Language'],
				"ID" => $result['language_id'],
				"NAME" => $result['language_name'],
				"FOLDER" => $result['language_folder'],
				"USABLE" => ($result['language_usable'] == 1) ? "checked=\"checked\"" : ""
			));
		}
		else
		{
			error_msg($lang['Error'], $lang['Invalid_language_pack_id']);
		}

		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("edit_language");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");
	}

}
else if($_GET['func'] == "delete")
{
	if(!(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0))
	{
		error_msg($lang['Error'], $lang['Invalid_language_pack_id']);
	}
	$query = $db->query("SELECT count(`language_id`) AS 'count' FROM `".$db_prefix."languages`");
	$result = $db->fetch_array($query);
	if($result['count'] <= 1) {
		error_msg($lang['Error'], $lang['Cannot_Delete_Last_Language_Msg']);
	}

	if(isset($_GET['confirm']) && $_GET['confirm'] == 1)
	{
		$db->query("UPDATE `".$db_prefix."users` SET `user_language` = '".$config['default_language']."' WHERE `user_language` = '".$_GET['id']."'");
		$db->query("DELETE FROM `".$db_prefix."languages` WHERE `language_id` = '".$_GET['id']."'");

		info_box($lang['Delete_Language'], $lang['Language_Deleted_Msg'], "language.php");
	}
	else
	{
		confirm_msg($lang['Delete_Language'], $lang['Delete_Language_Confirm_Msg'], "language.php?func=delete&id=".$_GET['id']."&confirm=1", "language.php");
	}

}
else
{
	//
	// View installed language packs
	//
	$theme->new_file("manage_languages", "manage_languages.tpl");

	$query = $db->query("SELECT `language_id`, `language_name`, `language_folder`, `language_usable` FROM `".$db_prefix."languages`");

	while($result = $db->fetch_array($query))
	{
		$theme->insert_nest("manage_languages", "language_row", array(
			"ID" => $result['language_id'],
			"NAME" => $result['language_name'],
			"FOLDER" => $result['language_folder'],
			"USABLE" => ($result['language_usable'] == 1) ? "checked=\"checked\"" : ""
		));
		$theme->add_nest("manage_languages", "language_row");
	}

	//
	// Output the page header
	//
	include($root_path . "includes/page_header.php");

	//
	// Output the main page
	//
	$theme->output("manage_languages");

	//
	// Output the page footer
	//
	include($root_path . "includes/page_footer.php");
}

?>