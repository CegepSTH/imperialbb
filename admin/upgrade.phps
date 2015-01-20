<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: upgrade.php                                                # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
include($root_path . "includes/common.php");

set_time_limit(0);
ob_start('ob_logstdout', 2);

$upgrade_location = "http://www.imperialbb.com/scripts";

if($config['version'] == "") error_msg("Error", "Error cannot get installation version!");

require($root_path . "classes/modfile_parse.php");
require($root_path . "classes/ftp.php");

eval(file_get_contents("$upgrade_location/upgrades/"));

if($config['ftp_user'] == "")
{
	error_msg($lang['Error'], $lang['FTP_User_Required']);
}

$version = explode(".", $config['version']);
$latest_version_array = explode(".", $latest_version);
if(($version['0'] > $latest_version_array['0']) || ($version['0'] == $latest_version_array['0'] && ($version['1'] > $latest_version_array['1'] || ($version['1'] == $latest_version_array['1'] && $version['2'] >= $latest_version_array['2']))))
{
	error_msg($lang['Upgrade'], $lang['Forum_Currently_Upto_Date']);
}

while(1)
{
	$sql = $db->query("SELECT `config_value` FROM `".$db_prefix."config` WHERE `config_name` = 'version'");
	if($result = $db->fetch_array($sql))
	{
		$config['version'] = $result['config_value'];
	}
	else
	{
		error_msg($lang['Error'], $lang['Cannot_Get_Version']);
	}
	$version = explode(".", $config['version']);
	$latest_version_array = explode(".", $latest_version);
	if(($version['0'] >= $latest_version_array['0']) && ($version['1'] >= $latest_version_array['1']) && ($version['2'] >= $latest_version_array['2']))
	{
		error_msg($lang['Upgrade'], $lang['Forum_Now_Upto_Date']);
	}
	else
	{
		// Init Mod installer
		$upgrade = new mod_installer("$upgrade_location/upgrades/".$config['version']."/");
		// Run the mod installer (returning template data)
		list($template_updates, $version) = $upgrade->load_modfile("index.mod", true);
		foreach($template_updates as $file => $data)
		{
			$template_updates[$file] = (count($data) > 0) ? $db->escape_string(implode("\n", $data)) : "";
		}
		$template_updates = implode("\n", $template_updates);
		if(!empty($version))
		{
			$config['version'] = $db->escape_string($version);
		}

		// Insert upgrade into the upgrade table && Update version
		$db->query("INSERT INTO `".$db_prefix."upgrades` (`upgrade_version`, `upgrade_template_updates`) VALUES ('".$config['version']."', '$template_updates')");
		if(!empty($version))
		{
			$db->query("UPDATE `".$db_prefix."config` SET `config_value` = '".$config['version']."' WHERE `config_name` = 'version'");
		}

		$upgrade->write_failures("manual_updates_".$version);
 
		// Close FTP Connection
		$upgrade->ftp->close();
		if(empty($version))
		{
			error_msg($lang['Error'], $lang['Cannot_Get_Version']);
		}
	}
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
