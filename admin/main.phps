<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: main.php                                                   # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
include($root_path."includes/common.php");

if(isset($_GET['func']) && $_GET['func'] == "phpinfo")
{
	phpinfo();
    exit();
}

$config['admincp_notepad'] = htmlspecialchars($config['admincp_notepad']);
if(isset($_GET['func']) && $_GET['func'] == 'update_notepad')
{
	$db->query("UPDATE `".$db_prefix."config` SET `config_value` = '".$db->escape_string(strip_tags(trim($_POST['admincp_notepad'])))."' WHERE `config_name` = 'admincp_notepad'");
	info_box($lang['notepad_updated'], $lang['notepad_updated_desc'], "main.php");
}

$sql = $db->query("SELECT count(*) FROM `".$db_prefix."users` WHERE `user_id` > 0");
if($result = $db->fetch_row($sql))
{
	$total_users = $result[0];
}
$db->free($sql);
$sql = $db->query("SELECT count(*) FROM `".$db_prefix."users` WHERE `user_date_joined` > '" . (time() - 86400) . "' AND `user_id` > 0");
if($result = $db->fetch_row($sql))
{
	$users_today = $result[0];
}
$db->free($sql);
$sql = $db->query("SELECT count(*) FROM `".$db_prefix."topics`");
if($result = $db->fetch_row($sql))
{
	$total_topics = $result[0];
}
$db->free($sql);
$sql = $db->query("SELECT count(*) FROM `".$db_prefix."topics` WHERE `topic_time` > '" . (time() - 86400) . "'");
if($result = $db->fetch_row($sql))
{
	$topics_today = $result[0];
}
$db->free($sql);
$sql = $db->query("SELECT count(*) FROM `".$db_prefix."posts`");
if($result = $db->fetch_row($sql))
{
	$total_posts = $result[0];
}
$db->free($sql);
$sql = $db->query("SELECT count(*) FROM `".$db_prefix."posts` WHERE `post_timestamp` > '" . (time() - 86400) . "'");
if($result = $db->fetch_row($sql))
{
	$posts_today = $result[0];
}
$db->free($sql);
$theme->new_file("main", "main.tpl", "");
$theme->replace_tags("main", array(
	"TOTAL_USERS"   => $total_users,
	"USERS_TODAY"   => $users_today,
	"TOTAL_POSTS"   => $total_posts,
	"POSTS_TODAY"   => $posts_today,
	"TOTAL_TOPICS"  => $total_topics,
	"TOPICS_TODAY"  => $topics_today
));

$vcheck = file_get_contents("http://www.imperialbb.com/scripts/upgrades/");
eval($vcheck);
if($config['version'] < $latest_version)
{
	$upgrade_info = "";
	foreach($upgrade_notes as $title => $upgrade_note)
    {
		if(is_array($upgrade_note))
        {
			$upgrade_info .= "$title:<br />";
			foreach($upgrade_note as $upgrade_temp)
            {
				$upgrade_info .= " - $upgrade_temp<br />";
			}
			$upgrade_info .= "<br />";
		}
        else
        {
			$upgrade_info .= "$title<br /><br />";
		}
	}
	$theme->switch_nest("main", "vcheck", false, array(
		"INSTALL_NOTES" => $upgrade_info
	));
}
else
{
	$theme->switch_nest("main", "vcheck", true);
}
$theme->add_nest("main", "vcheck");
if(is_dir("../install"))
{
        $theme->insert_nest("main", "install_warning");
        $theme->add_nest("main", "install_warning");
}

// Output the page header
include($root_path . "includes/page_header.php");

// Output the main page
$theme->output("main");

// Output the page footer
include($root_path . "includes/page_footer.php");

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
