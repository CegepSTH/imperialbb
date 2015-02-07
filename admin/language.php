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
require_once($root_path . "includes/common.php");
Template::setBasePath($root_path . "templates/original/admin/");

$language->add_file("admin/language");
Template::addNamespace("L", $lang);

if(!isset($_GET['func'])) $_GET['func'] = "";

if($_GET['func'] == "add")
{
	$page_master = new Template("add_edit_language.tpl");

	if(isset($_POST['Submit']))
	{
		$error = "";

		if(!isset($_POST['name']) || empty($_POST['name'])) {
			$error .= $lang['No_Name_Entered_Error'] . "<br />";
		}
		
		if(!isset($_POST['folder']) || empty($_POST['folder'])) {
			$error .= $lang['No_Folder_Entered_Error'];
		} else {
			if(!is_dir($root_path . "/language/".$_POST['folder']."/")) {
				$error .= $lang['Invalid_Folder_Entered_Error'];
			}
		}

		if(!empty($error))
		{
			$page_master->setVars(array(
				"ACTION" => $lang['Add_Language'],
				"NAME" => $_POST['name'],
				"FOLDER" => $_POST['folder'],
				"USABLE" => (isset($_POST['usable'])) ? "checked=\"checked\"" : ""
			));

			$page_master->addToBlock("error", array(
				"ERROR" => $error
			));

			include_once($root_path . "includes/page_header.php");
			echo($page_master->render());
			include_once($root_path . "includes/page_footer.php");
		}
		else
		{
			if(isset($_POST['usable'])) {
				$usable = "1";
			} else {
				$usable = "0";
			}
			
			$db2->query("INSERT INTO `_PREFIX_languages` (`language_name`, `language_folder`, `language_usable`) 
			VALUES (:name, :folder, :usable)",
			array(":name" => $_POST['name'], ":folder" => $_POST['folder'], ":usable" => $usable));

			info_box($lang['Add_Language'], $lang['Language_Added_Msg'], "language.php");
		}
	}
	else
	{
		$page_master->setVars(array(
			"ACTION" => $lang['Add_Language'],
			"NAME" => "",
			"FOLDER" => "",
			"USABLE" => ""
		));

		include_once($root_path . "includes/page_header.php");
		echo($page_master->render());
		include_once($root_path . "includes/page_footer.php");
	}


}
else if($_GET['func'] == "download")
{
	// TODO: DOWNLOAD INSERT SECTION
}
else if($_GET['func'] == "edit")
{
	if(!(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0)) {
		error_msg($lang['Error'], $lang['Invalid_language_pack_id']);
	}

	$page_master = new Template("add_edit_language.tpl");

	if(isset($_POST['Submit']))
	{
		$error = "";

		if(!isset($_POST['name']) || empty($_POST['name'])) {
			$error .= $lang['No_Name_Entered_Error'] . "<br />";
		}
		
		if(!isset($_POST['folder']) || empty($_POST['folder'])) {
			$error .= $lang['No_Folder_Entered_Error'];
		} else {
			if(!is_dir($root_path . "/language/".$_POST['folder']."/")) {
				$error .= $lang['Invalid_Folder_Entered_Error'];
			}
		}

		if(!empty($error))
		{
			$page_master->setVars( array(
				"ACTION" => $lang['Edit_Language'],
				"ID" => $_GET['id'],
				"NAME" => $_POST['name'],
				"FOLDER" => $_POST['folder'],
				"USABLE" => (isset($_POST['usable'])) ? "checked=\"checked\"" : ""
			));

			$page_master->addToBlock(array(
				"ERROR" => $error
			));

			include_once($root_path . "includes/page_header.php");
			echo($page_master->render());
			include_once($root_path . "includes/page_footer.php");
		}
		else
		{
			if(isset($_POST['usable'])) {
				$usable = "1";
			} else {
				$usable = "0";
			}
			
			$db2->query("UPDATE `_PREFIX_languages` 
				SET `language_name`=:name, `language_folder`=:folder, `language_usable`=:usable
				WHERE `language_id`=:lid",
				array(":name" => $_POST['name'], ":folder" => $_POST['folder'], ":usable" => $usable, ":lid" => $_GET['id']));

			info_box($lang['Edit_Language'], $lang['Language_Edited_Msg'], "language.php");
		}
	}
	else
	{
		$db2->query("SELECT `language_id`, `language_name`, `language_folder`, `language_usable` 
			FROM `_PREFIX_languages` 
			WHERE `language_id`=:lid", array(":lid" => $_GET['id']));

		if($result = $db2->fetch()) {
			$page_master->setVars(array(
				"ACTION" => $lang['Edit_Language'],
				"ID" => $result['language_id'],
				"NAME" => $result['language_name'],
				"FOLDER" => $result['language_folder'],
				"USABLE" => ($result['language_usable'] == 1) ? "checked=\"checked\"" : ""
			));
		} else {
			error_msg($lang['Error'], $lang['Invalid_language_pack_id']);
		}

		include_once($root_path . "includes/page_header.php");
		echo($page_master->render());
		include_once($root_path . "includes/page_footer.php");
	}
}
else if($_GET['func'] == "delete")
{
	if(!(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0)) {
		error_msg($lang['Error'], $lang['Invalid_language_pack_id']);
	}
	
	$db2->query("SELECT count(`language_id`) AS 'count' FROM `_PREFIX_languages`");
	$result = $db2->fetch();
	
	if($result['count'] <= 1) {
		error_msg($lang['Error'], $lang['Cannot_Delete_Last_Language_Msg']);
	}

	if(isset($_GET['confirm']) && $_GET['confirm'] == 1) {
		$db2->query("UPDATE `_PREFIX_users` 
			SET `user_language`=:def
			WHERE `user_language`=:lid",
			array(":def" => $config['default_language'], ":lid" => $_GET['id']));
			
		$db2->query("DELETE FROM `_PREFIX_languages` WHERE `language_id`=:lid", array(":lid" => $_GET['id']));

		info_box($lang['Delete_Language'], $lang['Language_Deleted_Msg'], "language.php");
	} else {
		confirm_msg($lang['Delete_Language'], $lang['Delete_Language_Confirm_Msg'], "language.php?func=delete&id=".$_GET['id']."&confirm=1", "language.php");
	}
}
else
{
	//
	// View installed language packs
	//
	$theme->new_file("manage_languages", "manage_languages.tpl");
	$page_master = new Template("manage_languages.tpl");

	$db2->query("SELECT `language_id`, `language_name`, `language_folder`, `language_usable` FROM `_PREFIX_languages`");

	while($result = $db2->fetch())
	{
		$page_master->addToBlock("language_row", array(
			"ID" => $result['language_id'],
			"NAME" => $result['language_name'],
			"FOLDER" => $result['language_folder'],
			"USABLE" => ($result['language_usable'] == 1) ? "checked=\"checked\"" : ""
		));
	}

	include_once($root_path . "includes/page_header.php");
	echo($page_master->render());
	include_once($root_path . "includes/page_footer.php");
}
?>
