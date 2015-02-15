<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: common.php                                                 # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

// Define a constant
if(!defined("IN_IBB")) {
	die("Hacking Attempt");
}

// Start the page timer
$page_gen_start = explode(' ',microtime());
$page_gen_start = $page_gen_start[0] + $page_gen_start[1];

// Sessions start
session_start();

// Run all the includes
require_once($root_path . "includes/config.php");

// If not installed... redirect back/to installer
if(!defined("INSTALLED") || INSTALLED != 1)
{
	header("Location: install");
}

// Setup the debug area
switch($debug)
{
	case 2:
		error_reporting(E_ALL);
	break;
	case 1:
		error_reporting(E_ALL ^ E_NOTICE);
	break;
	default:
		error_reporting(0);
	break;
}

require_once($root_path . "classes/database.php");
$db2 = new Database($database, $db_prefix);

require_once($root_path . "classes/csrf.php");

require_once($root_path . "classes/template.php");
Template::setBasePath($root_path . "templates/original");
$template_vars = array(
	"TEMPLATE_PATH" => $root_path . "templates/original"
);
Template::addNamespace("T", $template_vars);
require_once($root_path . "includes/rendering.php");

include_once($root_path . "includes/constants.php");
include_once($root_path . "includes/init.php");
include_once($root_path . "includes/sessions.php");
include_once($root_path . "includes/functions.php");
include_once($root_path . "classes/class_template.php");
include_once($root_path . "classes/class_language.php");

$protected = (!defined("IN_ADMIN")) ? 'WHERE c.`config_protected` = \'0\'' : '';

// Config Select //
$sql = $db2->query("SELECT c.*, l.`language_folder`
	FROM (`_PREFIX_config` c
	LEFT JOIN `_PREFIX_languages` l
	ON c.`config_name` = 'default_language'
	AND l.`language_id` = c.`config_value`)".$protected.""
);
while($row = $sql->fetch())
{
	$config[$row['config_name']] = stripslashes($row['config_value']);
	if(isset($row['language_folder']))
	{
		$config['language_folder'] = $row['language_folder'];
		unset($row['language_folder']);
	}
}
Template::addNamespace("C", $config);
// CONSTANTS TEMPLATE
// Hack: for reference array. The thing is we want to keep namespaces 
// as array references, because $lang var would be heavy to copy from.
$contants_tpl_values_nocopyrino = array("ANNOUNCMENT" => "".ANNOUNCMENT,
	"PINNED" => "".PINNED, 
	"GENERAL" => "".GENERAL);
Template::addNamespace("Constant", $contants_tpl_values_nocopyrino);
	
// Get user data and put into array
$sql = $db2->query("SELECT u.*, l.`language_folder` AS 'user_language_folder', l.`language_name`
	FROM (`_PREFIX_users` u LEFT JOIN
	`_PREFIX_languages` l ON l.`language_id` =  u.`user_language`)
	WHERE u.`user_id` = :user_id",
	array(
		':user_id' => $_SESSION['user_id']
	)
);
if($row = $sql->fetch())
{
	$user = $row;
	unset($user['user_password']); // Unset the password just to be safe..
}
else
{
	setcookie("UserName");
	setcookie("Password");
	$_SESSION['user_id'] = -1;
	session_regenerate_id();
	$db2->query("DELETE FROM `_PREFIX_sessions`
		WHERE `ip` = :remote_addr",
		array(
			':remote_addr' => $_SERVER['REMOTE_ADDR']
		)
	);
	error_msg("Error", "Unable to select user information.");
}

if(defined("IN_ADMIN") && $user['user_level'] < 5)
{
	die("<script language='javascript'>top.document.location = '../index.php';</script>");
}

$language = new Language();
Template::addNamespace("L", $lang);

// Template Check
if($user['user_id'] < 0) {
	$user['user_template'] = $config['default_template'];
}

$sql = "SELECT `template_folder` FROM `_PREFIX_templates` WHERE `template_id`=:userTemplate";
if($user['user_level'] != "5")
{
	$sql .= " AND `template_usable` = '1'";
}

$db2->query($sql, array(":userTemplate" => $user['user_template']));

if($result = $db2->fetch()) {
	$user['user_template_folder'] = $result['template_folder'];
} else {
	if($user['user_id'] > 0)
	{
		$db2->query("UPDATE `_PREFIX_users`
			SET `user_template` = :default_template
			WHERE `user_id` = :userId",
			array(
				':default_template' => $config['default_template'],
				':userId' => $user['user_id']
			)
		);
	}

	$sql = $db2->query("SELECT *
		FROM `_PREFIX_templates`
		WHERE `template_id` = :default_template",
		array(
			':default_template' => $config['default_template']
	));
	if($result = $sql->fetch())
	{
		$user['user_template_folder'] = $result['template_folder'];
	}
	else
	{
		die($lang['Unable_to_select_template']);
	}
}

$theme = new Theme();

### Language and Template files are usable below this point ###
require_once($root_path . "classes/class_pagination.php");
$pp = new ibb_pagination();

// 1st thing to do is see if the user is banned!
if($user['user_level'] == 0)
{
	error_msg($lang['Error'], sprintf($lang['Banned_Msg'], $config['admin_email'], $config['admin_email']));
}

// See if the board is offline
if($config['board_offline'] == 1 && $user['user_level'] != 5 && (!isset($ignore_offline) || $ignore_offline != true))
{
	if(!isset($_GET['act']) || $_GET['act'] != "login")
	{
		$page_title = $config['site_name'] . " (" . $lang['Board_Offline'] . ")";
		error_msg($lang['Board_Offline'], $config['offline_message']);
	}
	else
	{
		$page_title = $config['site_name'] . " (" . $lang['Board_Offline'] . ")";
	}
}

$config['jscripts_dir'] = "./jscripts";

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
