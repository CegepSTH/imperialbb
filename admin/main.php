<?php
define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
require_once($root_path."includes/common.php");
Template::setBasePath($root_path . "templates/original/admin/");

if(isset($_GET['func']) && $_GET['func'] == 'update_notepad') {
	$values = array(":admnote" => $_POST['admincp_notepad']);
	$db2->query("UPDATE `_PREFIX_config` 
		SET `config_value`=:admnote WHERE `config_name` = 'admincp_notepad'", $values);
	
	$_SESSION["return_url"] = "main.php";
	header("Location: error.php?code=".ERR_CODE_NOTEPAD_UPDATED);
	exit();
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

$page_master = new Template("main.tpl");
$page_master->setVars(array(
	"TOTAL_USERS"   => $total_users,
	"USERS_TODAY"   => $users_today,
	"TOTAL_POSTS"   => $total_posts,
	"POSTS_TODAY"   => $posts_today,
	"TOTAL_TOPICS"  => $total_topics,
	"TOPICS_TODAY"  => $topics_today
));

if(is_dir("../install")) {
	$page_master->addToBlock("install_warning", array());
}

outputPage($page_master);
exit();
?>
