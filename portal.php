<?php
define("IN_IBB", 1);

$root_path = "./";
require_once($root_path . "includes/common.php");
$language->add_file("portal");
Template::addNamespace("L", $lang);

$tplNews = new Template("portal.tpl");
// If the news id requested is valid, just show the news.
// Else we just show all the news. 
if(isset($_GET['nid']) && is_numeric($_GET['nid'])) {
	$db2->query("SELECT `news_title`, `news_content`, `username`, `user_id`, `user_avatar_location`, `news_timestamp`
		FROM `_PREFIX_portal_news`
		JOIN `_PREFIX_users` ON `user_id` = `news_author_id`
		WHERE `news_id`=:nid", 
		array(":nid" => $_GET['nid']));
	
	if($result = $db2->fetch()) {
		// Show news boyssss
		$tplNews->addToBlock("news_item", array("TITLE" => $result['news_title'], 
			"CONTENT" => format_text($result['news_content'], true, true, true, false), 
			"AUTHOR_AVATAR" => ($result['user_avatar_location'] ? "uploads/".$result['user_avatar_location'] : "blank_avatar.gif" ),
			"AUTHOR_NAME" => $result['username'], 
			"AUTHOR_ID" => $result['user_id'], 
			"DATE" => date("F j, Y, g:i a", $result['news_timestamp']),
			"block_news_read_complete" => ""));		
	} else {
		showMessage(ERR_CODE_NEWS_NOT_FOUND, "portal.php");
	}
} else {
	// I SAID SHOW ALL THE NEWS
	$db2->query("SELECT `news_id`, `news_title`, `news_content`, `username`, `user_id`, `user_avatar_location`, `news_timestamp`
		FROM `_PREFIX_portal_news`
		JOIN `_PREFIX_users` ON `user_id` = `news_author_id`",
		array());
	
	$newsCount = 0;
	while($result = $db2->fetch()) {
		$tplNews->addToBlock("news_item", array("TITLE" => $result['news_title'], 
			"AUTHOR_AVATAR" => ($result['user_avatar_location'] ? "uploads/".$result['user_avatar_location'] : "blank_avatar.gif" ),
			"CONTENT" => format_text(shortentext($result['news_content'], 500, false), true, true, true, false), 
			"AUTHOR_NAME" => $result['username'], 
			"AUTHOR_ID" => $result['user_id'], 
			"DATE" => date("F j, Y, g:i a", $result['news_timestamp']),
			"block_news_read_complete" => $tplNews->renderBlock("news_read_complete", array("NEWS_ID" => $result['news_id']))
			));
		$newsCount++;
	} 
	
	if($newsCount == 0) {
		$tplNews->addToBlock("no_news_message", array());
	}
}

outputPage($tplNews);
exit();
?>
