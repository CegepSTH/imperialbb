<?php

/**********************************************************
*
*			admin/main.php
*
*		ImperialBB 2.X.X - By Nate and James
*
*		     (C) The IBB Group
*
***********************************************************/

define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
require_once($root_path."includes/common.php");

$config['admincp_notepad'] = $config['admincp_notepad'];
if(isset($_GET['func']) && $_GET['func'] == 'update_notepad') {
	$values = array(":admnote" => $_POST['admincp_notepad']);
	$db2->query("UPDATE `_PREFIX_config` 
		SET `config_value`=:admnote WHERE `config_name` = 'admincp_notepad'", $values);
	info_box($lang['notepad_updated'], $lang['notepad_updated_desc'], "main.php");
}

$db2->query("SELECT count(*) AS `tot_users` FROM `_PREFIX_users` WHERE `user_id` > 0");
if($result = $db2->fetch()) {
	$total_users = $result['tot_users'];
}

$db2->query("SELECT count(*) AS `nb_users` FROM `_PREFIX_users` WHERE `user_date_joined` > '".(time() - 86400)."' AND `user_id` > 0");
if($result = $db2->fetch()) {
	$users_today = $result['nb_users'];
}

$db2->query("SELECT count(*) AS `tot_topics` FROM `_PREFIX_topics`");
if($result = $db2->fetch()) {
	$total_topics = $result['tot_topics'];
}

$db2->query("SELECT count(*) AS `today_topics` FROM `_PREFIX_topics` WHERE `topic_time` > '".(time() - 86400)."'");
if($result = $db2->fetch()) {
	$topics_today = $result['today_topics'];
}

$db2->query("SELECT count(*) AS `tot_posts` FROM `_PREFIX_posts`");
if($result = $db2->fetch()) {
	$total_posts = $result['tot_posts'];
}

$db2->query("SELECT count(*) AS `posts_today` FROM `_PREFIX_posts` WHERE `post_timestamp` > '".(time() - 86400)."'");
if($result = $db2->fetch()) {
	$posts_today = $result['posts_today'];
}

$theme->new_file("main", "main.tpl", "");
$theme->replace_tags("main", array(
	"TOTAL_USERS"   => $total_users,
	"USERS_TODAY"   => $users_today,
	"TOTAL_POSTS"   => $total_posts,
	"POSTS_TODAY"   => $posts_today,
	"TOTAL_TOPICS"  => $total_topics,
	"TOPICS_TODAY"  => $topics_today
));

$theme->add_nest("main", "vcheck");
if(is_dir("../install")) {
	$theme->insert_nest("main", "install_warning");
	$theme->add_nest("main", "install_warning");
}

// Output the page header
include_once($root_path . "includes/page_header.php");

// Output the main page
$theme->output("main");

// Output the page footer
include_once($root_path . "includes/page_footer.php");
?>
