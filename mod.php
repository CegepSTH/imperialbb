<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: mod.php                                                    # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

define("IN_IBB", 1);

$root_path = "./";
require_once($root_path . "includes/common.php");
$language->add_file("mod");
Template::addNamespace("L", $lang);

if(!isset($_GET['func'])) $_GET['func'] = "";

$is_mod_action_post = isset($_POST['modaction']);
if($is_mod_action_post) {
	CSRF::validate();
}
if(!isset($_POST['func'])) {
	$_POST['func'] = "";
}

if($_GET['func'] == "delete")
{
	// Deleting a topic
	if(isset($_GET['tid'])) {   
		$db2->query("SELECT f.`forum_id`, f.`forum_mod`, g.`ug_mod` 
			FROM ((`_PREFIX_topics` t 
				LEFT JOIN `_PREFIX_forums` f ON f.`forum_id` = t.`topic_forum_id`) 
				LEFT JOIN `_PREFIX_ug_auth` g ON g.`usergroup`=:ugroup AND g.`ug_forum_id` = f.`forum_id`)  
			WHERE t.`topic_id`=:tid", 
			array(":ugroup" => $user['user_usergroup'], ":tid" => $_GET['tid']));
			
		if($ug_auth = $db2->fetch()) {
			if(!(($ug_auth['forum_mod'] <= $user['user_level'] && $ug_auth['ug_mod'] == 0) || $ug_auth['ug_mod'] == 1))
			{
				if($user['user_id'] > 0) {
					showMessage(ERR_CODE_INVALID_PERMISSION_MOD, "view_topic.php?tid=".$_GET['tid']);
				} else {
					showMessage(ERR_CODE_REQUIRE_LOGIN, "login.php");
				}
			}
			$fid = $ug_auth['forum_id'];
		} else {			
			showMessage(ERR_CODE_INVALID_TOPIC_ID, "index.php");
		}
		
		if(!isset($_GET['confirm']) || $_GET['confirm'] != "1") {			
			$db2->query("SELECT `topic_title`
				FROM `_PREFIX_topics`
				WHERE `topic_id`=:tid
				LIMIT 1", array(":tid" => $_GET['tid']));
			$result = $db2->fetch();
			
			confirm_msg($lang['Delete_Topic'], sprintf($lang['Delete_Topic_Confirm_Msg'], $result['topic_title']), "mod.php?func=delete&tid=".$_GET['tid']."&confirm=1", "view_topic.php?tid=".$_GET['tid']."");
		} else {
			CSRF::validate();

			$values = array(":tid" => $_GET['tid']);
			// Delete Topid Data
			$db2->query("DELETE FROM `_PREFIX_topics` WHERE `topic_id`=:tid", $values);
            // Delete Post Data
			$db2->query("DELETE FROM `_PREFIX_posts` WHERE `post_topic_id`=:tid", $values);
            // Delete Poll Data
			$db2->query("DELETE FROM `_PREFIX_pollvotes` WHERE `poll_topic_id`=:tid", $values);
            // Delete Topic Subscription Data
			$db2->query("DELETE FROM `_PREFIX_topic_subscriptions` WHERE `topic_subscription_topic_id`=:tid", $values);

			$db2->query("SELECT p.`post_id`
								FROM (`_PREFIX_topics` t
								LEFT JOIN `_PREFIX_posts` p ON p.`post_id` = t.`topic_last_post`)
								WHERE `topic_forum_id`=:fid ORDER BY `topic_time` DESC LIMIT 1", 
								array(":fid" => $fid));
								
			if($result = $db2->fetch()) {
				$db2->query("UPDATE `_PREFIX_forums` SET `forum_last_post`=:pid WHERE `forum_id`=:fid", 
					array(":pid" => $result['post_id'], ":fid" => $fid));
			} else {
				$db2->query("UPDATE `_PREFIX_forums` SET `forum_last_post` = '0' WHERE `forum_id`=:fid", array(":fid" => $fid));
			}
			
			showMessage(ERR_CODE_TOPIC_DELETE_SUCCESS, "view_forum.php?fid=".$fid);
		}
	}
	else if(isset($_GET['pid']))
	{
		$db2->query("SELECT t.`topic_id`, f.`forum_id`, f.`forum_mod`, g.`ug_mod` 
			FROM (((`_PREFIX_posts` p 
				LEFT JOIN `_PREFIX_topics` t ON t.`topic_id` = p.`post_topic_id`) 
				LEFT JOIN `_PREFIX_forums` f ON f.`forum_id` = t.`topic_forum_id`) 
				LEFT JOIN `_PREFIX_ug_auth` g ON g.`usergroup`=:ugroup AND g.`ug_forum_id` = f.`forum_id`)  
			WHERE p.`post_id`=:pid",
			array(":ugroup" => $user['user_usergroup'], ":pid" => $_GET['pid']));
			
		if($ug_auth = $db2->fetch()) {
			if(!(($ug_auth['forum_mod'] <= $user['user_level'] && $ug_auth['ug_mod'] == 0) || $ug_auth['ug_mod'] == 1)) {
				if($user['user_id'] > 0) {
					showMessage(ERR_CODE_INVALID_PERMISSION_MOD, "view_topic.php?tid=".$ug_auth['topic_id']);
				} else {
					showMessage(ERR_CODE_REQUIRE_LOGIN, "login.php");
				}
			}
			
			$tid = $ug_auth['topic_id'];
			$fid = $ug_auth['forum_id'];
		} else {
			showMessage(ERR_CODE_INVALID_POST_ID, "index.php");
		}

		$db2->query("SELECT count(*) AS `cc` FROM `_PREFIX_posts` WHERE `post_topic_id`=:tid", array(":tid" => $tid));
		if($result = $db2->fetch()) {
			if($result["cc"] == 1) {
				showMessage(ERR_CODE_TOPIC_CANT_DELETE_LAST_MSG, "view_topic.php?tid=".$tid);
			}
		}
		
		if(!isset($_GET['confirm']) || $_GET['confirm'] != "1") {
			$db2->query("SELECT t.`topic_id`
				FROM (`_PREFIX_posts` p
					LEFT JOIN `_PREFIX_topics` t ON t.`topic_id` = p.`post_topic_id`)
				WHERE p.`post_id`=:pid", 
				array(":pid" => $_GET['pid']));
				
			$topic = $db2->fetch();
			confirm_msg($lang['Delete_Post'], $lang['Delete_Post_Confirm_Msg'], "mod.php?func=delete&pid=".$_GET['pid']."&confirm=1", "view_topic.php?tid=".$topic['topic_id']."");
		} else {			
			CSRF::validate();

			$db2->query("DELETE FROM `_PREFIX_posts` WHERE `post_id`=:pid", array(":pid" => $_GET['pid']));

			$db2->query("SELECT `post_id`, `post_timestamp`
				FROM `_PREFIX_posts`
				WHERE `post_topic_id`=:tid 
				ORDER BY `post_timestamp` DESC LIMIT 1",
				array(":tid" => $tid));

			if($result = $db2->fetch()) {
				$db2->query("UPDATE `_PREFIX_topics` 
					SET `topic_last_post`=:pid, `topic_time`=:time, `topic_replies` = (`topic_replies` - 1) 
					WHERE `topic_id`=:tid", 
					array("pid" => $result['post_id'], ":time" => $result['post_timestamp'], ":tid" => $tid));
			} else {
				showMessage(ERR_CODE_TOPIC_CANT_DELETE_LAST_MSG, "view_topic.php?tid=".$tid);
			}
			
			$db2->query("SELECT p.`post_id`
				FROM (`_PREFIX_topics` t
					LEFT JOIN `_PREFIX_posts` p ON p.`post_id` = t.`topic_last_post`)
				ORDER BY t.`topic_time` DESC LIMIT 1");
				
			if($result = $db2->fetch()) {
				$db2->query("UPDATE `_PREFIX_forums` SET `forum_last_post`=:pid WHERE `forum_id`=:fid",
					array(":pid" => $result['pid'], ":fid" => $fid));
			} else {
				showMessage(ERR_CODE_LAST_POST_IN_FORUM);
			}
			
			showMessage(ERR_CODE_POST_DELETE_SUCCESS, "view_topic.php?tid=".$tid);
		}
	}
} else if($_GET['func'] == "move")
{
	if(!isset($_GET['tid']) || !preg_match("/^[0-9]+$/", $_GET['tid'])) {
		showMessage(ERR_CODE_INVALID_TOPIC_ID, "index.php");
	}

	if(!isset($_POST['Submit'])) {
		$tplMoveTopic = new Template("move_topic.tpl");

		$db2->query("SELECT t.`topic_id`, t.`topic_title`, f.`forum_id`, f.`forum_name`
			FROM ((`_PREFIX_topics` t
				LEFT JOIN `_PREFIX_forums` f ON f.`forum_id` = t.`topic_forum_id`)
				LEFT JOIN `_PREFIX_posts` p ON p.`post_id` = t.`topic_first_post`)
			WHERE t.`topic_id`=:tid", array(":tid" => $_GET['tid']));

		if($result = $db2->fetch()) {
			$tplMoveTopic->setVars(array(
				"FORUM_ID" => $result['forum_id'],
				"FORUM_NAME" => $result['forum_name'],
				"TOPIC_ID" => $result['topic_id'],
				"TOPIC_NAME" => $result['topic_title'],
				"MOVE_TOPIC" => sprintf($lang['Move_Topic_Name'], $result['topic_title']),
				"CSRF_TOKEN" => CSRF::getHTML()
			));
			
			$db2->query("SELECT `forum_id`, `forum_name` FROM `_PREFIX_forums`");
			while($result = $db2->fetch()) {
				$tplMoveTopic->addToBlock("move_topic_forumrow",array(
					"FID" => $result['forum_id'],
					"FNAME" => $result['forum_name']
				));
			}

			outputPage($tplMoveTopic);
		} else {
			showMessage(ERR_CODE_INVALID_TOPIC_ID);
		}
	} else {
		CSRF::validate();

		if(!isset($_POST['fid']) || !preg_match("/^[0-9]+$/", $_POST['fid'])) {
			showMessage(ERR_CODE_INVALID_FORUM_ID);
		} 

		$db2->query("SELECT t.`topic_id`, f.`forum_id`, f.`forum_name`
			FROM (`_PREFIX_topics` t
				LEFT JOIN `_PREFIX_forums` f ON f.`forum_id`=:fid)
			WHERE t.`topic_id`=:tid",
			array(":fid" => $_POST['fid'], ":tid" => $_GET['tid']));
			
		if($result = $db2->fetch()) {			
			if(!$result['forum_id']) {
				showMessage(ERR_CODE_INVALID_FORUM_ID);
			}
			
			$db2->query("UPDATE `_PREFIX_topics` SET `topic_forum_id`=:fid WHERE `topic_id`=:tid",
				array(":fid" => $result['forum_id'], ":tid" => $result['topic_id']));
			showMessage(ERR_CODE_TOPIC_MOVE_SUCCESS, "view_topic.php?tid=".$result['topic_id']);
		} else {			
			showMessage(ERR_CODE_INVALID_TOPIC_ID);
		}
	}
}
else if($is_mod_action_post && $_POST['func'] == "lock")
{
	if(!(isset($_POST['tid']) && is_numeric($_POST['tid']))) {
		showMessage(ERR_CODE_INVALID_TOPIC_ID);
	}

	$db2->query("SELECT t.`topic_id`, t.`topic_status`, f.`forum_mod`, g.`ug_mod`
		FROM ((`_PREFIX_topics` t
			LEFT JOIN `_PREFIX_forums` f ON f.`forum_id` = t.`topic_forum_id`)
			LEFT JOIN `_PREFIX_ug_auth` g ON g.`usergroup`=:ugroup AND g.`ug_forum_id` = f.`forum_id`)
		WHERE t.`topic_id`=:tid",
		array(":ugroup" => $user['user_usergroup'], ":tid" => $_POST['tid']));

	if($ug_auth = $db2->fetch()) {		
		if(!(($ug_auth['forum_mod'] <= $user['user_level'] && $ug_auth['ug_mod'] == 0) || $ug_auth['ug_mod'] == 1)) {
			if($user['user_id'] > 0) {
				showMessage(ERR_CODE_INVALID_PERMISSION_MOD, "view_topic.php?tid=".$_POST['tid']);
			} else {
				showMessage(ERR_CODE_REQUIRE_LOGIN, "login.php");
			}
		}
		
		$tid = $ug_auth['topic_id'];
	}

	if(empty($tid)) {
		showMessage(ERR_CODE_INVALID_TOPIC_ID);
	} 
	
	$db2->query("UPDATE `_PREFIX_topics`
		SET `topic_status` = '1'
		WHERE `topic_id`=:tid",
		array(":tid" => $tid));
	
	showMessage(ERR_CODE_TOPIC_LOCK_SUCCESS, "view_topic.php?tid=".$tid);
}
else if($is_mod_action_post && $_POST['func'] == "unlock")
{
	if(!(isset($_POST['tid']) && is_numeric($_POST['tid']))) {
		showMessage(ERR_CODE_INVALID_TOPIC_ID);
	}

	$db2->query("SELECT t.`topic_id`, t.`topic_status`, f.`forum_mod`, g.`ug_mod`
		FROM ((`_PREFIX_topics` t
			LEFT JOIN `_PREFIX_forums` f ON f.`forum_id` = t.`topic_forum_id`)
			LEFT JOIN `_PREFIX_ug_auth` g ON g.`usergroup`=:ugroup AND g.`ug_forum_id` = f.`forum_id`)
		WHERE t.`topic_id`=:tid", 
		array(":ugroup" => $user['user_usergroup'], ":tid" => $_POST['tid']));

	if($ug_auth = $db2->fetch()) {		
		if(!(($ug_auth['forum_mod'] <= $user['user_level'] && $ug_auth['ug_mod'] == 0) || $ug_auth['ug_mod'] == 1)) {			
			if($user['user_id'] > 0) {			
				showMessage(ERR_CODE_INVALID_PERMISSION_MOD);	
			} else {			
				showMessage(ERR_CODE_REQUIRE_LOGIN, "login.php");	
			}
		}
		$tid = $ug_auth['topic_id'];
	}

	if(empty($tid)) {
		showMessage(ERR_CODE_INVALID_TOPIC_ID);
	}
	
	$db2->query("UPDATE `_PREFIX_topics` SET `topic_status` = '0' WHERE `topic_id`=:tid", array(":tid" => $tid));

	showMessage(ERR_CODE_TOPIC_UNLOCK_SUCCESS, "view_topic.php?tid=".$tid);
}
else if($is_mod_action_post && $_POST['func'] == "topic_type")
{
	if(!(isset($_POST['tid']) && is_numeric($_POST['tid']))) {
		showMessage(ERR_CODE_INVALID_TOPIC_ID);
	}

	if(!(isset($_POST['type']) && is_numeric($_POST['type']))) {
		showMessage(ERR_CODE_TOPIC_INVALID_TYPE, "view_topic.php?tid=".$_POST['tid']);
	}

	$db2->query("SELECT t.`topic_id`, t.`topic_status`, f.`forum_mod`, g.`ug_mod`
		FROM ((`_PREFIX_topics` t
			LEFT JOIN `_PREFIX_forums` f ON f.`forum_id` = t.`topic_forum_id`)
			LEFT JOIN `_PREFIX_ug_auth` g ON g.`usergroup`=:ugroup AND g.`ug_forum_id` = f.`forum_id`)
		WHERE t.`topic_id`=:tid",
		array(":ugroup" => $user['user_usergroup'], ":tid" => $_POST['tid']));

	if($ug_auth = $db2->fetch()) {
		if(!(($ug_auth['forum_mod'] <= $user['user_level'] && $ug_auth['ug_mod'] == 0) || $ug_auth['ug_mod'] == 1)) {
			if($user['user_id'] > 0) {
				showMessage(ERR_CODE_INVALID_PERMISSION_MOD, "view_topic.php?tid=".$_POST['tid']);
			} else {
				showMessage(ERR_CODE_REQUIRE_LOGIN, "login.php");
			}
		}
		
		$tid = $ug_auth['topic_id'];
	}

	if(empty($tid)) {
		showMessage(ERR_CODE_INVALID_TOPIC_ID);
	} 

	$db2->query("UPDATE `_PREFIX_topics` SET `topic_type`=:type WHERE `topic_id`=:tid",
		array(":type" => $_POST['type'], ":tid" => $tid));

	showMessage(ERR_CODE_TOPIC_TYPE_CHANGE_SUCCESS, "view_topic.php?tid=".$tid);
}
else
{	header("Location: index.php");
	exit();
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
