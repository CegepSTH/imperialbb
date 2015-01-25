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
require_once($root_path . "includes/common.php");

if(!isset($_GET['func'])) $_GET['func'] = "";
if($_GET['func'] == "add")
{
	if(isset($_POST['Submit']))
	{
		$values = array(":name" => $name, ":code" => $code, ":url" => $url);
		$db2->query("INSERT INTO `_PREFIX_smilies` (`smilie_name`, `smilie_code`, `smilie_url`) VALUES (:name, :code, :url)", $values);
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
		$values = array(":sname" => $_POST['name'], ":scode" => $_POST['code'], ":url" => $_POST['url'], ":sid" => $_GET['id']);
		$db2->query("UPDATE `_PREFIX_smilies` SET `smilie_name`=:sname, `smilie_code`=:scode, `smilie_url`=:url WHERE `smilie_id`=:sid", $values);
		info_box($lang['Edit_Smily'], $lang['Smily_Updated_Msg'], "?module=Admin&act=smilies");
	}
	else
	{
		$db2->query ("SELECT * FROM `_PREFIX_smilies` WHERE `smilie_id`=:id LIMIT 1", array(":id" => $id));
		if ($result = $db2->fetch())
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
		$db2->query("DELETE FROM `_PREFIX_smilies` WHERE `smilie_id`=:id", array(":id" => $id));
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
	$db2->query ("SELECT * FROM `_PREFIX_smilies`");
	while($result = $db2->fetch())
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
