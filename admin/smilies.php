<?php

/**********************************************************
*
*			admin/smilies.php
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

if(!isset($_GET['func'])) $_GET['func'] = "";
if($_GET['func'] == "add")
{
	if(isset($_POST['Submit']))
	{
		$db->query ("INSERT INTO `".$db_prefix."smilies` (`smilie_name`, `smilie_code`, `smilie_url`) VALUES ('$name', '$code', '$url')");
		info_box($lang['Add_Smily'], $lang['Smily_Created_Msg'], "?module=Admin&act=smilies");
	}
	else
	{
		$theme->new_file("add_smilie", "add_smilie.tpl");
		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("add_smilie");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");
	}
}
else if($_GET['func'] == "edit")
{
	if(!isset($_GET['id']) || !preg_match("#^[0-9]+\$#", $_GET['id'])) {
		error_msg($lang['Edit_Smily'], $lang['Invalid_Smily_Id']);
	}
	if(isset($_POST['Submit']))
	{
		$db->query("UPDATE `".$db_prefix."smilies` SET `smilie_name` = '".$_POST['name']."', `smilie_code` = '".$_POST['code']."', `smilie_url` = '".$_POST['url']."' WHERE `smilie_id` = '".$_GET['id']."'");
		info_box($lang['Edit_Smily'], $lang['Smily_Updated_Msg'], "?module=Admin&act=smilies");
	}
	else
	{
		$sql = $db->query ("SELECT * FROM `".$db_prefix."smilies` WHERE `smilie_id`='$id' LIMIT 1");
		if ($result = $db->fetch_array ($sql))
		{
			$theme->new_file("edit_smilie", "edit_smilie.tpl");
			$theme->replace_tags("edit_smilie", array(
				"NAME" => $result['smilie_name'],
				"CODE" => $result['smilie_code'],
				"URL" => $result['smilie_url']
			));
			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("edit_smilie");

			//
			// Output the page footer
			//
			include($root_path . "includes/page_footer.php");
		}
		else
		{
			error_msg($lang['Error'], $lang['Invalid_Smily_Id']);
		}
	}
}
else if($_GET['func'] == "delete")
{
	if(!isset($_GET['id']) || !preg_match("#^[0-9]+\$#", $_GET['id'])) {
		error_msg($lang['Delete_Smily'], $lang['Invalid_Smily_Id']);
	}
	if(isset($_GET['confirm'])) {
		$db->query("DELETE FROM `".$db_prefix."smilies` WHERE `smilie_id`='$id'");
		info_box($lang['Delete_Smily'], $lang['Smily_Deleted_Msg'], "smilies.php");
	}
	else
	{
		confirm_msg($lang['Delete_Smily'], $lang['Delete_Smily_Confirm_Msg'], "smilies.php?id=".$_GET['id']."&confirm=1", "smilies.php");
	}
}
else
{

	$theme->new_file("smilies", "smilies.tpl");
	$sql = $db->query ("SELECT * FROM `".$db_prefix."smilies`");
	while ($result = $db->fetch_array($sql))
	{
		$theme->insert_nest("smilies", "smilies_row", array(
			"ID" => $result['smilie_id'],
			"NAME" => $result['smilie_name'],
			"CODE" => $result['smilie_code'],
			"URL" => $result['smilie_url']
		));
		$theme->add_nest("smilies", "smilies_row");
	}
	//
	// Output the page header
	//
	include($root_path . "includes/page_header.php");

	//
	// Output the main page
	//
	$theme->output("smilies");

	//
	// Output the page footer
	//
	include($root_path . "includes/page_footer.php");
}
?>
