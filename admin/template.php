<?php

/**********************************************************
*
*			admin/template.php
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

$language->add_file("admin/template");

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
			if(!is_dir($root_path . "/templates/".$_POST['folder']."/"))
			{
				$error .= $lang['Invalid_Folder_Entered_Error'];
			}
		}

		if(!empty($error))
		{
			$theme->new_file("add_template", "add_edit_template.tpl");

			$theme->replace_tags("add_template", array(
				"ACTION" => $lang['Add_Template'],
				"NAME" => $_POST['name'],
				"FOLDER" => $_POST['folder'],
				"USABLE" => (isset($_POST['usable'])) ? "checked=\"checked\"" : ""
			));

			$theme->insert_nest("add_template", "error", array(
				"ERROR" => $error
			));
			$theme->add_nest("add_template", "error");

			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("add_template");

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
			$values = array(":name" => $_POST['name'], ":folder" => $_POST['folder'], ":usable" => $usable);
			$db2->query("INSERT INTO `_PREFIX_templates` (`template_name`, `template_folder`, `template_usable`) VALUES (:name, :folder, :usable)", $values);

			info_box($lang['Add_Template'], $lang['Template_Added_Msg'], "template.php");
		}
	}
	else
	{
		$theme->new_file("add_template", "add_edit_template.tpl");

		$theme->replace_tags("add_template", array(
			"ACTION" => $lang['Add_Template'],
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
		$theme->output("add_template");

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
		error_msg($lang['Error'], $lang['Invalid_template_id']);
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
			if(!is_dir($root_path . "/templates/".$_POST['folder']."/"))
			{
				$error .= $lang['Invalid_Folder_Entered_Error'];
			}
		}

		if(!empty($error))
		{
			$theme->new_file("edit_template", "add_edit_template.tpl");

			$theme->replace_tags("edit_template", array(
				"ACTION" => $lang['Edit_Template'],
				"ID" => $_GET['id'],
				"NAME" => $_POST['name'],
				"FOLDER" => $_POST['folder'],
				"USABLE" => (isset($_POST['usable'])) ? "checked=\"checked\"" : ""
			));

			$theme->insert_nest("edit_template", "error", array(
				"ERROR" => $error
			));
			$theme->add_nest("edit_template", "error");

			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("edit_template");

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
			$values = array(":name" => $_POST['name'], ":folder" => $_POST['folder'], ":usable" => $usable, ":id" => $_GET['id']);
			$db2->query("UPDATE `_PREFIX_templates` SET `template_name`=:name, `template_folder`=:older, `template_usable`=:usable WHERE `template_id`=:id", $values);

			info_box($lang['Edit_Template'], $lang['Template_Edited_Msg'], "template.php");
		}


	}
	else
	{
		$theme->new_file("edit_template", "add_edit_template.tpl");

		$db2->query("SELECT `template_id`, `template_name`, `template_folder`, `template_usable` FROM `_PREFIX_templates` WHERE `template_id`=:id", array(":id" => $_GET['id']));

		if($result = $db2->fetch())
		{
			$theme->replace_tags("edit_template", array(
				"ACTION" => $lang['Edit_Template'],
				"ID" => $result['template_id'],
				"NAME" => $result['template_name'],
				"FOLDER" => $result['template_folder'],
				"USABLE" => ($result['template_usable'] == 1) ? "checked=\"checked\"" : ""
			));
		}
		else
		{
			error_msg($lang['Error'], $lang['Invalid_template_id']);
		}

		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("edit_template");

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
		error_msg($lang['Error'], $lang['Invalid_template_id']);
	}
	$db2->query("SELECT count(`template_id`) AS 'count' FROM `_PREFIX_templates`");
	$result = $db2->fetch();
	if($result['count'] <= 1) {
		error_msg($lang['Error'], $lang['Cannot_Delete_Last_Template_Msg']);
	}

	if(isset($_GET['confirm']) && $_GET['confirm'] == 1)
	{
		$values = array(":default" => $config['default_template'], ":id" => $_GET['id']);
		$db2->query("UPDATE `_PREFIX_users` SET `user_template`=:default WHERE `user_template`=:id");
		$db2->query("DELETE FROM `_PREFIX_templates` WHERE `template_id`=:id", array(":id" => $_GET['id']));

		info_box($lang['Delete_Template'], $lang['Template_Deleted_Msg'], "template.php");
	}
	else
	{
		confirm_msg($lang['Delete_Template'], $lang['Delete_Template_Confirm_Msg'], "template.php?func=delete&id=".$_GET['id']."&confirm=1", "template.php");
	}

}
else
{
	//
	// View installed templates
	//
	$theme->new_file("manage_templates", "manage_templates.tpl");

	$db2->query("SELECT `template_id`, `template_name`, `template_folder`, `template_usable` FROM `_PREFIX_templates`");

	while($result = $db2->fetch())
	{
		$theme->insert_nest("manage_templates", "template_row", array(
			"ID" => $result['template_id'],
			"NAME" => $result['template_name'],
			"FOLDER" => $result['template_folder'],
			"USABLE" => ($result['template_usable'] == 1) ? "checked=\"checked\"" : ""
		));
		$theme->add_nest("manage_templates", "template_row");
	}

	//
	// Output the page header
	//
	include($root_path . "includes/page_header.php");

	//
	// Output the main page
	//
	$theme->output("manage_templates");

	//
	// Output the page footer
	//
	include($root_path . "includes/page_footer.php");
}

?>
