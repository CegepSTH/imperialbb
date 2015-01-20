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
include($root_path . "includes/common.php");

set_time_limit(0);
ob_implicit_flush(1);

$mod_location = "http://www.imperialbb.com/scripts";
if(!isset($_GET['id'])) $_GET['id'] = "";
if(!isset($_GET['func'])) $_GET['func'] = "";


if($_GET['func'] == "install")
{
	$query = $db->query("SELECT `mod_id` FROM `".$db_prefix."mods` WHERE `mod_id` = '".$_GET['id']."'");
	if($result = $db->fetch_array($query))
	{
		error_msg($lang['Error'], $lang['Mod_Already_Installed']);
	}

	if(isset($_POST['confirm']))
	{
		require($root_path . "classes/modfile_parse.php");
		require($root_path . "classes/ftp.php");

		$mod = new mod_installer("$mod_location/mods/".$_GET['id']."/");

		$data = $mod->load_modfile("index.mod", true);
		$template_updates = $db->escape_string(implode("\n", $data[0]));
		$version = $db->escape_string($data[1]);

		$db->query("INSERT INTO `".$db_prefix."mods` (`mod_id`, `mod_template_updates`, `mod_version`) VALUES ('".$_GET['id']."', '$template_updates', '$version')");

		$mod->write_failures("mod_".$_GET['id']."_install");

		// Close FTP Connection
		$mod->ftp_close();
	}
	else
	{
		confirm_msg($lang['Install_Mod'], sprintf($lang['Install_Mod_X'], $_GET['id']), "mods.php?id=".$_GET['id']."&func=install", "mods.php");
	}
}
else if($_GET['func'] == "upgrade" || $_GET['func'] == "uninstall")
{
	error_msg($lang['Error'], "Sorry this is currently under construction");
}
else
{
	if(@fopen("$mod_location/mods_list.php", 'r'))
	{
		eval(file_get_contents("$mod_location/mods_list.php"));
		$theme->new_file("mods_list", "mods.tpl");
		foreach($mods as $id => $attr)
		{
			$query = $db->query("SELECT * FROM `".$db_prefix."mods` WHERE `mod_id` = '$id'");
			if($result = $db->fetch_array($query))
			{
				$theme->insert_nest("mods_list", "modrow_false", array(
					"ID" => $id,
					"NAME" => $attr['name'],
					"DESC" => $attr['desc']
				));
				$theme->add_nest("mods_list", "modrow_false");
			}
			else
			{
				$theme->insert_nest("mods_list", "modrow_true", array(
					"ID" => $id,
					"NAME" => $attr['name'],
					"DESC" => $attr['desc']
				));
				$theme->add_nest("mods_list", "modrow_true");
			}
		}
		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("mods_list");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");

	}
}
?>
