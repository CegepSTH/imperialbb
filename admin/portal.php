<?php

define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
require_once($root_path."includes/common.php");
Template::setBasePath($root_path . "templates/original/admin/");

$language->add_file("admin/portal");
Template::addNamespace("L", $lang);

if(!isset($_GET['func'])) $_GET['func'] = "";

$tplPortal = new Template("portal.tpl");

if($_GET['func'] == "save") {
	CSRF::validate();
	
	if(trim($_POST['title']) == "" || trim($_POST['news_content']) == "") {
		$_SESSION["return_url"] = "portal.php";
		header("Location: error.php?code=".ERR_CODE_NEWS_ALL_FIELDS_REQUIRED);
		exit();
	}
	
	$datetime = new DateTime();
	$isNew = !(isset($_GET['nid']) && is_numeric($_GET['nid']));
	
	if($isNew) {
		$db2->query("INSERT INTO `_PREFIX_portal_news` VALUES(NULL, :newstitle, :authid, :content, :timestamp)",
			array(":newstitle" => $_POST['title'], 
			":authid" => intval($user['user_id']), 
			":content" => $_POST['news_content'], 
			":timestamp" => $datetime->getTimestamp()));
	} else {
		$db2->query("UPDATE `_PREFIX_portal_news` 
			SET `news_title`=:newstitle, `news_content`=:content
			WHERE `news_id`=:newsid", array(
			":newstitle" => $_POST['title'], 
			":content" => $_POST['news_content'], 
			":newsid" => $_GET['nid']));
	}
	
	if($db2->lastInsertId() > 0 || $db2->rowCount() > 0) {
		$_SESSION["return_url"] = "portal.php";
		if($isNew) {
			header("Location: error.php?code=".ERR_CODE_NEWS_INSERT_SUCCESS);
		} else {
			header("Location: error.php?code=".ERR_CODE_NEWS_UPDATE_SUCCESS);
		}
		exit();
	} else {
		$_SESSION["return_url"] = "portal.php";
		if($isNew) {
			header("Location: error.php?code=".ERR_CODE_NEWS_INSERT_FAILED);
		} else {
			header("Location: error.php?code=".ERR_CODE_NEWS_UPDATE_FAILED);
		}
		exit();
	}
} else if($_GET['func'] == "delete") {
	// delete.
	if(isset($_POST['Submit']) && isset($_GET['nid']) && is_numeric($_GET['nid'])) {
		$db2->query("DELETE FROM `_PREFIX_portal_news` WHERE `news_id`=:nid", 
			array(":nid" => $_GET['nid']));
			
		if($db2->rowCount() > 0) {
			$_SESSION["return_url"] = "portal.php";
			header("Location: error.php?code=".ERR_CODE_NEWS_DELETE_SUCCESS);
			exit();				
		} else {
			$_SESSION["return_url"] = "portal.php";
			header("Location: error.php?code=".ERR_CODE_NEWS_DELETE_FAILED);
			exit();			
		}
	} else {
		$_SESSION["return_url"] = "portal.php";
		header("Location: error.php?code=".ERR_CODE_INVALID_NEWS_ID);
		exit();	
	}
} else if($_GET['func'] == "edit") {
	if(!isset($_GET['nid']) || !is_numeric($_GET['nid'])) {
		$_SESSION["return_url"] = "portal.php";
		header("Location: error.php?code=".ERR_CODE_INVALID_NEWS_ID);
		exit();		
	}
	
	$db2->query("SELECT `news_id`, `news_content`, `news_title` 
		FROM `_PREFIX_portal_news`
		WHERE `news_id`=:newsid", array(":newsid" => $_GET['nid']));
	
	if($result = $db2->fetch()) {
		$tplPortal->addToBlock("edit_news", array(
			"CSRF_TOKEN" => CSRF::getHTML(),
			"NEWS_ID" => $result['news_id'], 
			"NEWS_TITLE" => $result['news_title'],
			"NEWS_CONTENT" => $result['news_content']		
		));
	} else {
		$_SESSION["return_url"] = "portal.php";
		header("Location: error.php?code=".ERR_CODE_NEWS_NOT_FOUND);
		exit();			
	}
} else if($_GET['func'] == "create") {
	// This code is too long. We definitely can do better.
	$tplPortal->addToBlock("create_news", array(
		"CSRF_TOKEN" => CSRF::getHTML(),
		));
} else {
	// Show all news.
	$db2->query("SELECT COUNT(*) AS `count` FROM `_PREFIX_portal_news`", array());
	$count = $db2->fetch();
	$pagination = $pp->paginate($count['count'], $config['posts_per_page']);
	$blockNewsItem = "";
	
	$db2->query("SELECT `news_id`, `username`, `news_title` 
		FROM `_PREFIX_portal_news`
		JOIN `_PREFIX_users` ON `user_id` = `news_author_id`
		LIMIT ".$pp->limit, array());
	
	while($result = $db2->fetch()) {
		$blockNewsItem .= $tplPortal->renderBlock("news_item",
			array(
			"CSRF_TOKEN" => CSRF::getHTML(),
			"NEWS_ID" => $result['news_id'], 
			"NEWS_TITLE" => $result['news_title'],
			"NEWS_AUTHOR" => $result['username'],
			));
	}
	
	$tplPortal->addToBlock("news_main", array(
		"block_news_item" => $blockNewsItem,
		"PAGINATION" => $pagination
		));
}

outputPage($tplPortal);
exit();

?>
