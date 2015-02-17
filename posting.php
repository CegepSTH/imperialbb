<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: posting.php                                                # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright � 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

define("IN_IBB", 1);

$root_path = "./";
include($root_path . "includes/common.php");

$language->add_file("posting");
Template::addNamespace("L", $lang);

function renderPoll($page_master) {
	global $lang;

	$page_master->addToBlock("poll_header", array(
		"POLL_TITLE" => (isset($_POST['poll_title'])) ? $_POST['poll_title'] : ""
	));
	$page_master->addToBlock("poll_trailer", array(
		"POLL_ADD_CHOICE_URL" => "posting.php?func=newtopic&fid=" . $_GET['fid'] .
			"&poll_choices=" . ($_GET['poll_choices'] + 1 ) . ""
	));

	if(!isset($_GET['poll_choices'])) $_GET['poll_choices'] = 5;

	for($i=1; $i<=$_GET['poll_choices']; $i++)
	{
		$page_master->addToBlock("pollchoice_row", array(
			"POLL_CHOICE_DESC" => sprintf($lang['Poll_Choice_X'], intval($i)),
			"POLL_CHOICE_NUMBER" => strval($i),
			"POLL_CHOICE_VALUE" => (isset($_POST['pollchoice'][$i])) ? $_POST['pollchoice'][$i] : ""
		));
	}
}

function renderPostEditBlocks($page_master) {
	global $config;

	if($config['html_enabled'] == true) {
		$page_master->addToBlock("disable_html", array());
	}

	if($config['bbcode_enabled'] == true) {
		$page_master->addToBlock("disable_bbcode", array());

		$page_master->setVar("BBCODE_EDITOR",
			renderBBCodeEditor()
		);
	}

	if($config['smilies_enabled'] == true) {
		$page_master->addToBlock("disable_smilies", array());
	
		$page_master->setVar("SMILIE_PICKER",
			renderSmiliePicker()
		);
	}

	if($user['user_id'] > 0) {
		$page_master->addToBlock("logged_in", array());
	}
}

function hasPermissionToCreatePoll() {
	global $db2, $user;

	$forum_sql = $db2->query("SELECT f.`forum_name`, f.`forum_poll`, g.`ug_poll`
		FROM (`_PREFIX_forums` f
			LEFT JOIN `_PREFIX_ug_auth` g ON g.`ug_forum_id` = f.`forum_id`
			AND g.`usergroup` = :user_group)
		WHERE `forum_id` = :fid",
		array(
			":user_group" => $user['user_usergroup'],
			":fid" => $_GET['fid']
		)
	);

	if($forum_result = $forum_sql->fetch())
	{
		if((($forum_result['forum_poll'] <= $user['user_level'] && $forum_result['ug_poll'] == 0) ||
			$forum_result['ug_poll'] == 1))
		{
			return true;
		}
	}

	return false;
}

function renderPollIfHasPermission($page_master) {
	if(hasPermissionToCreatePoll()) {
		renderPoll($page_master);
	}
}

if(!isset($_GET['func'])) $_GET['func'] = "";
if($_GET['func'] == "newtopic")
{

	if(!isset($_GET['fid'])) error_msg($lang['Error'], $lang['Invalid_Forum_Id']);
	$forum_sql = $db2->query("SELECT f.`forum_name`, f.`forum_reply`, f.`forum_post`, g.`ug_post`
		FROM (`_PREFIX_forums` f
			LEFT JOIN `_PREFIX_ug_auth` g ON g.`ug_forum_id` = f.`forum_id`
			AND g.`usergroup` = :user_group)
		WHERE `forum_id` = :fid",
		array(
			":user_group" => $user['user_usergroup'],
			":fid" => $_GET['fid']
		)
	);
	if($forum_result = $forum_sql->fetch())
	{

		if(!(($forum_result['forum_post'] <= $user['user_level'] && $forum_result['ug_post'] == 0) || $forum_result['ug_post'] == 1))
		{
			if($user['user_id'] > 0)
			{
				error_msg($lang['Error'], $lang['Invalid_Permissions_Post']);
			}
			else
			{
				header("Location: login.php");
				exit();
			}
		}
	}
	else
	{
		error_msg("Error", $lang['Invalid_Forum_Id']);
	}

	if(isset($_POST['Submit']))
	{
		CSRF::validate();

		$error = "";
		if(strlen($_POST['title']) < 1 )
		{
			$error .= sprintf($lang['No_x_content'], strtolower($lang['Title'])) . "<br />";
		}
		else if(strlen($_POST['title']) > 75)
		{
			$error .= $lang['Title_Too_Long'] . "<br />";
		}
		if(strlen($_POST['body']) < 1)
		{
			$error .= $lang['No_Post_Content'] . "<br />";
		}
		if(strlen($_POST['body']) > 2000)
		{
			$error .= $lang['post_has_too_many_chars'] . "<br />";
		}

		$poll_choices_blank = true;
		foreach($_POST['pollchoice'] as $pollchoice) {
			if(!empty($pollchoice)) {
				$poll_choices_blank = false;
			}
		}

		if(empty($_POST['poll_title']) && !$poll_choices_blank) {
			$error .= $lang['Poll_Title_Blank_Msg'] . "<br />";
		}

		if(!empty($_POST['poll_title']) && $poll_choices_blank) {
			$error .= $lang['Poll_Choices_Blank_Msg'] . "<br />";
		}

		if(strlen($error) > 0)
		{
			$page_master = new Template("post.tpl");

			$page_master->setVars(array(
				"FORUM_ID" => $_GET['fid'],
				"FORUM_NAME" => $forum_result['forum_name'],
				"ACTION" => $lang['New_Topic'],
				"BODY" => stripcslashes($_POST['body']),
				"HTML_ENABLED_MSG" => ($config['html_enabled'] == true) ? sprintf($lang['HTML_is_x'], $lang['enabled']) : sprintf($lang['HTML_is_x'], $lang['disabled']),
				"BBCODE_ENABLED_MSG" => ($config['bbcode_enabled'] == true) ? sprintf($lang['BBCode_is_x'], $lang['enabled']) : sprintf($lang['BBCode_is_x'], $lang['disabled']),
				"SMILIES_ENABLED_MSG" => ($config['smilies_enabled'] == true) ? sprintf($lang['Smilies_are_x'], $lang['enabled']) : sprintf($lang['Smilies_are_x'], $lang['disabled']),
				"CSRF_TOKEN" => CSRF::getHTML()
			));

			$page_title = $config['site_name'] . " &raquo; " . $forum_result['forum_name'] . " &raquo; " . $lang['New_Topic'];

			$page_master->addToBlock("title", array(
				"TITLE" => $_POST['title']
			));

			$page_master->addToBlock("nav_new_topic", array(
				"FORUM_ID" => $_GET['fid'],
				"FORUM_NAME" => $forum_result['forum_name'],
				"ACTION" => $lang['New_Topic']
			));

			$page_master->addToBlock("error", array(
				"ERRORS" => $error
			));

			renderPostEditBlocks($page_master);
			renderPollIfHasPermission($page_master);

			outputPage($page_master, $page_title);
		}
		else
		{
			// Disable checkboxes && attach signature
			if($config['html_enabled'] == false || !isset($_POST['disable_html']))
			{
				$_POST['disable_html'] = "0";
			}
			else
			{
				$_POST['disable_html'] = "1";
			}

			if($config['bbcode_enabled'] == false || !isset($_POST['disable_bbcode']))
			{
				$_POST['disable_bbcode'] = "0";
			}
			else
			{
				$_POST['disable_bbcode'] = "1";
			}

			if($config['smilies_enabled'] == false || !isset($_POST['disable_smilies']))
			{
				$_POST['disable_smilies'] = "0";
			}
			else
			{
				$_POST['disable_smilies'] = "1";
			}

			if(isset($_POST['attach_signature']))
			{
				$_POST['attach_signature'] = "1";
			}
			else
			{
				$_POST['attach_signature'] = "0";
			}
            $auth_poll = true;
			if(!empty($_POST['poll_title'])) {
				$forum_sql = $db2->query("SELECT f.`forum_name`, f.`forum_poll`, g.`ug_poll`
					FROM (`_PREFIX_forums` f
					LEFT JOIN `_PREFIX_ug_auth` g ON g.`ug_forum_id` = f.`forum_id`
					AND g.`usergroup` = :user_group)
					WHERE `forum_id` = :fid",
					array(
						":user_group" => $user['user_usergroup'],
						":fid" => $_GET['fid']
					)
				);
				if(!$forum_result = $forum_sql->fetch())
				{
					$_POST['poll_title'] == "";
					$auth_poll = false;
				}
			}
			// Insert topic info
			$db2->query("INSERT INTO `_PREFIX_topics` (
				`topic_forum_id`,
				`topic_title`,
				`topic_poll_title`,
				`topic_user_id`,
				`topic_time`
				)
				VALUES (
				:fid,
				:title,
				:poll_title,
				:user_id,
				:time
				)",
				array(
					":fid" => $_GET['fid'],
					":title" => $_POST['title'],
					":poll_title" => $_POST['poll_title'],
					":user_id" => $user['user_id'],
					":time" => time()
				)
			);

			$tid = $db2->lastInsertId();
			// Insert post info
			$db2->query("INSERT INTO `_PREFIX_posts` (
				`post_topic_id`,
				`post_user_id`,
				`post_text`,
				`post_timestamp`,
				`post_disable_html`,
				`post_disable_bbcode`,
				`post_disable_smilies`,
				`post_attach_signature`
				)
				VALUES (
				:tid,
				:user_id,
				:post_body,
				:post_time,
				:disable_html,
				:disable_bbcode,
				:disable_smilies,
				:attach_signature
				)",
				array(
					":tid" => $tid,
					":user_id" => $_SESSION['user_id'],
					":post_body" => $_POST['body'],
					":post_time" => time(),
					":disable_html" => $_POST['disable_html'],
					":disable_bbcode" => $_POST['disable_bbcode'],
					":disable_smilies" => $_POST['disable_smilies'],
					":attach_signature" => $_POST['attach_signature']
				)
			);
			$pid = $db2->lastInsertId();

   			if($auth_poll == true) {
				$poll_choice_id = 1;
				foreach($_POST['pollchoice'] as $poll_choice) {
					if(!empty($poll_choice)) {
						$db2->query("INSERT INTO `_PREFIX_pollvotes` (
							`poll_topic_id`,
							`poll_choice_id`,
							`poll_choice_name`
							)
							VALUES (
							:tid,
							:poll_choice_id,
							:poll_choice
							)",
							array(
								":tid" => $tid,
								":poll_choice_id" => $poll_choice_id,
								":poll_choice" => $poll_choice
							)
						);
						$poll_choice_id++;
					}
				}
			}
			// Update forum info
			$sql = $db2->query("SELECT * FROM `_PREFIX_forums`
				WHERE `forum_id` = :fid
				LIMIT 1",
				array(
					":fid" => $_GET['fid']
				)
			);
			if($row = $sql->fetch())
			{
				$new_topics = $row['forum_topics'] + 1;
				$new_posts = $row['forum_posts'] + 1;
				$db2->query("UPDATE `_PREFIX_forums`
					SET `forum_topics` = :new_topics,
					`forum_posts` = :new_posts,
					`forum_last_post` = :pid
					WHERE `forum_id` = :fid",
					array(
						":new_topics" => $new_topics,
						":new_posts" => $new_posts,
						":pid" => $pid,
						":fid" => $_GET['fid']
					)
				);
			}

			$db2->query("UPDATE `_PREFIX_topics`
				SET `topic_first_post` = :pid1,
				`topic_last_post` = :pid2
				WHERE `topic_id` = :tid",
				array(
					":pid1" => $pid,
					":pid2" => $pid,
					":tid" => $tid
				)
			);

			if($user['user_id'] > 0)
			{
				$db2->query("UPDATE `_PREFIX_users`
					SET `user_posts` = :user_posts
					WHERE `user_id` = :user_id",
					array(
						":user_posts" => ($user['user_posts'] + 1),
						":user_id" => $user['user_id']
					)
				);
				if(isset($_POST['subscribe_to_topic'])) {
					$db2->query("INSERT INTO `_PREFIX_topic_subscriptions` (
						`topic_subscription_user_id`,
						`topic_subscription_topic_id`
						)
						VALUES (
						:user_id,
						:tid
						)",
						array(
							":user_id" => $user['user_id'],
							":tid" => $tid
						)
					);
				}
			}
			info_box($lang['New_Topic'], $lang['New_Post_Msg'], "view_topic.php?tid=$tid");
		}

	}
	else
	{
		$page_master = new Template("post.tpl");

		$page_master->setVars(array(
			"FORUM_ID" => $_GET['fid'],
			"FORUM_NAME" => $forum_result['forum_name'],
			"ACTION" => $lang['New_Topic'],
			"BODY" => (isset($_POST['body'])) ? $_POST['body'] : "",
			"HTML_ENABLED_MSG" => ($config['html_enabled'] == true) ? sprintf($lang['HTML_is_x'], $lang['enabled']) : sprintf($lang['HTML_is_x'], $lang['disabled']),
			"BBCODE_ENABLED_MSG" => ($config['bbcode_enabled'] == true) ? sprintf($lang['BBCode_is_x'], $lang['enabled']) : sprintf($lang['BBCode_is_x'], $lang['disabled']),
			"SMILIES_ENABLED_MSG" => ($config['smilies_enabled'] == true) ? sprintf($lang['Smilies_are_x'], $lang['enabled']) : sprintf($lang['Smilies_are_x'], $lang['disabled']),
			"CSRF_TOKEN" => CSRF::getHTML()
		));

		$page_title = $config['site_name'] . " &raquo; " . $forum_result['forum_name'] . " &raquo; " . $lang['New_Topic'];

		$page_master->addToBlock("title", array(
			"TITLE" => (isset($_POST['title'])) ? $_POST['title'] : ""
		));

		$page_master->addToBlock("nav_new_topic", array(
			"FORUM_ID" => $_GET['fid'],
			"FORUM_NAME" => $forum_result['forum_name'],
			"ACTION" => $lang['New_Topic']
		));

		renderPostEditBlocks($page_master);
		renderPollIfHasPermission($page_master);

		outputPage($page_master, $page_title);
	}
}
else if($_GET['func'] == "reply")
{
	if(!isset($_GET['tid'])) error_msg($lang['Error'], $lang['Invalid_Topic_Id']);

	$forum_sql = $db2->query("SELECT f.`forum_id`,
		f.`forum_name`,
		f.`forum_reply`,
		f.`forum_mod`,
		t.`topic_id`,
		t.`topic_title`,
		t.`topic_status`,
		g.`ug_reply`,
		g.`ug_mod`
		FROM (((`_PREFIX_forums` f
		LEFT JOIN `_PREFIX_topics` t ON t.`topic_id` = :tid)
		LEFT JOIN `_PREFIX_posts` p ON p.`post_id` = t.`topic_first_post`)
		LEFT JOIN `_PREFIX_ug_auth` g ON g.`ug_forum_id` = f.`forum_id` AND g.`usergroup` = :user_group)
		WHERE f.`forum_id` = t.`topic_forum_id`",
		array(
			":tid" => $_GET['tid'],
			":user_group" => $user['user_usergroup']
		)
	);
	if($forum_result = $forum_sql->fetch())
	{
		$auth_type = ($forum_result['topic_status'] == "1") ? "mod" : "reply";
		if(!(($forum_result['forum_' . $auth_type] <= $user['user_level'] && $forum_result['ug_' . $auth_type] == 0) || $forum_result['ug_' . $auth_type] == 1))
		{
			  if($user['user_id'] > 0)
			  {
				   error_msg($lang['Error'], $lang['Invalid_Permissions_Reply']);
			  }
			  else
			  {
				   header("Location: login.php");
				   exit();
			  }
		 }
	}
	else
	{
		error_msg($lang['Error'], $lang['Invalid_Topic_Id']);
	}

	if(isset($_POST['Submit']))
	{
		CSRF::validate();

		$error = "";
		if(!isset($_POST['title'])) $_POST['title'] = "";

		if(strlen($_POST['title']) > 75)
		{
			  $error .= $lang['Title_Too_Long'] . "<br />";
		}
		if(strlen($_POST['body']) < 1)
		{
			 $error .= $lang['No_Post_Content'] . "<br />";
		}
		if(strlen($_POST['body']) > 2000)
		{
			$error .= $lang['post_has_too_many_chars'] . "<br />";
		}
		if(strlen($error) > 0)
		{
			//
			// Set Up Quote
			//
			$body = "";
			if(isset($_GET['quote']) && is_numeric($_GET['quote']))
			{

				$quote_query = $db2->query("SELECT p.`post_text`, u.`username`
					FROM `_PREFIX_posts` p, `_PREFIX_users` u
					WHERE p.`post_id` = :pid AND u.`user_id` = p.`post_user_id`
					LIMIT 1",
					array(
						":pid" => $_GET['quote']
					)
				);

				if($quote = $db2->fetch())
				{
					$body = "[quote=".$quote['username']."]".$quote['post_text']."[/quote]\n\n";
				}
			}

			$page_master = new Template("post.tpl");
			$page_master->setVars(array(
				"FORUM_ID" => $forum_result['forum_id'],
				"FORUM_NAME" => $forum_result['forum_name'],
				"TOPIC_ID" => $_GET['tid'],
				"TOPIC_NAME" => $forum_result['topic_title'],
				"ACTION" => $lang['Reply'],
				"BODY" => $body,
				"HTML_ENABLED_MSG" => ($config['html_enabled'] == true) ? sprintf($lang['HTML_is_x'], $lang['enabled']) : sprintf($lang['HTML_is_x'], $lang['disabled']),
				"BBCODE_ENABLED_MSG" => ($config['bbcode_enabled'] == true) ? sprintf($lang['BBCode_is_x'], $lang['enabled']) : sprintf($lang['BBCode_is_x'], $lang['disabled']),
				"SMILIES_ENABLED_MSG" => ($config['smilies_enabled'] == true) ? sprintf($lang['Smilies_are_x'], $lang['enabled']) : sprintf($lang['Smilies_are_x'], $lang['disabled']),
				"CSRF_TOKEN" => CSRF::getHTML()
			));

			$page_title = $config['site_name'] . " &raquo; " . $forum_result['forum_name'] . " &raquo; " . $forum_result['topic_title'] . " &raquo; " . $lang['Reply'];

			$page_master->addToBlock("nav_reply", array(
				"FORUM_ID" => $forum_result['forum_id'],
				"FORUM_NAME" => $forum_result['forum_name'],
				"TOPIC_ID" => $_GET['tid'],
				"TOPIC_NAME" => $forum_result['topic_title'],
				"ACTION" => $lang['Reply'],
			));

			$page_master->addToBlock("error", array(
				"ERRORS" => $error
			));

			renderPostEditBlocks($page_master);

			outputPage($page_master, $page_title);
		}
		else
		{
			 if(!isset($_POST['title'])) $_POST['title'] = "";
			 // Insert topic info
			 $topic_sql = $db2->query("SELECT *
			 	FROM `_PREFIX_topics`
				WHERE `topic_id` = :tid
				LIMIT 1",
				array(
					":tid" => $_GET['tid']
				)
			);
			 $topic_info = $topic_sql->fetch();
			 $new_replies = $topic_info['topic_replies'] + 1;

			 if($user['user_id'] > 0)
			 {
				  $set_new_posts = false;
				  $track_topics = (isset($_COOKIE['read_topics'])) ? unserialize($_COOKIE['read_topics']) : "";
				  if(!empty($track_topics[$_GET['tid']]))
				  {
					   $set_new_posts = true;
				  }
				  else if(count($track_topics) < 200)
				  {
					   $set_new_posts = true;
				  }
				  if($set_new_posts)
				  {
					   $track_topics[$_GET['tid']] = time();
					   setcookie("read_topics", serialize($track_topics), 0);
				  }
			 }

			 // Insert post info
			 $db2->query("INSERT INTO `_PREFIX_posts` (
			 	`post_topic_id`,
				`post_user_id`,
				`post_text`,
				`post_timestamp`
				)
				VALUES (
				:tid,
				:user_id,
				:post_body,
				:post_time
				)",
				array(
					":tid" => $_GET['tid'],
					":user_id" => $user['user_id'],
					":post_body" => $_POST['body'],
					":post_time" => time()
				)
			);

			 $post_id = $db2->lastInsertId();

			 $db2->query("UPDATE `_PREFIX_topics`
				SET `topic_replies` = :reply_count,
				`topic_time` = :last_reply_time,
				`topic_last_post` = :last_tid
				WHERE `topic_id` = :tid",
				array(
					":reply_count" => $new_replies,
					":last_reply_time" => time(),
					":last_tid" => $post_id,
					":tid" => $_GET['tid']
				)
			);

			 // Update forum info
			 $sql = $db2->query("SELECT *
			 	FROM `_PREFIX_forums`
				WHERE `forum_id` = :fid
				LIMIT 1",
				array(
					":fid" => $topic_info['topic_forum_id']
				)
			);
			 if($row = $sql->fetch())
			 {
				  $new_posts = $row['forum_posts'] + 1;
				  $db2->query("UPDATE `_PREFIX_forums`
				  	SET `forum_posts` = :post_count,
					`forum_last_post` = :last_pid
					WHERE `forum_id` = :fid",
					array(
						":post_count" => $new_posts,
						":last_pid" => $post_id,
						":fid" => $topic_info['topic_forum_id']
					)
				  );
			 }

			  if($user['user_id'] > 0)
			  {
				   $db2->query("UPDATE `_PREFIX_users`
			           SET `user_posts` = :user_post_count
					   WHERE `user_id` = :user_id",
					   array(
					       ":user_post_count" => ($user['user_posts'] + 1),
						   ":user_id" => $user['user_id']
					   )
				   );
			  }
			  info_box($lang['Reply'], $lang['New_Post_Msg'], "view_topic.php?tid=".$_GET['tid']."");
		}
	}
	else
	{
		$body = "";
		if(isset($_GET['quote']) && is_numeric($_GET['quote']))
		{
			$quote_query = $db2->query("SELECT p.`post_text`, u.`username`
				FROM `_PREFIX_posts` p, `_PREFIX_users` u
				WHERE p.`post_id` = :pid AND u.`user_id` = p.`post_user_id`
				LIMIT 1",
				array(
					":pid" => $_GET['quote']
				)
			);

			if($quote = $db2->fetch())
			{
				$body = "[quote=".$quote['username']."]".$quote['post_text']."[/quote]\n\n";
			}
		}

		$page_master = new Template("post.tpl");
		$page_master->setVars(array(
			"FORUM_ID" => $forum_result['forum_id'],
			"FORUM_NAME" => $forum_result['forum_name'],
			"TOPIC_ID" => $_GET['tid'],
			"TOPIC_NAME" => $forum_result['topic_title'],
			"ACTION" => $lang['Reply'],
			"BODY" => $body,
			"HTML_ENABLED_MSG" => ($config['html_enabled'] == true) ? sprintf($lang['HTML_is_x'], $lang['enabled']) : sprintf($lang['HTML_is_x'], $lang['disabled']),
			"BBCODE_ENABLED_MSG" => ($config['bbcode_enabled'] == true) ? sprintf($lang['BBCode_is_x'], $lang['enabled']) : sprintf($lang['BBCode_is_x'], $lang['disabled']),
			"SMILIES_ENABLED_MSG" => ($config['smilies_enabled'] == true) ? sprintf($lang['Smilies_are_x'], $lang['enabled']) : sprintf($lang['Smilies_are_x'], $lang['disabled']),
			"CSRF_TOKEN" => CSRF::getHTML()
		));

		$page_title = $config['site_name'] . " &raquo; " . $forum_result['forum_name'] . " &raquo; " . $forum_result['topic_title'] . " &raquo; " . $lang['Reply'];

		$page_master->addToBlock("nav_reply", array(
			"FORUM_ID" => $forum_result['forum_id'],
			"FORUM_NAME" => $forum_result['forum_name'],
			"TOPIC_ID" => $_GET['tid'],
			"TOPIC_NAME" => $forum_result['topic_title'],
			"ACTION" => $lang['Reply'],
		));
			
		renderPostEditBlocks($page_master);

		outputPage($page_master, $page_title);
	}
}
else if($_GET['func'] == "edit")
{

	if(!isset($_GET['pid']) || !is_numeric($_GET['pid'])) error_msg($lang['Error'], $lang['Invalid_Post_Id']);
	$query = $db2->query("SELECT p.`post_user_id`, p.`post_text`, t.`topic_id`, t.`topic_status`, t.`topic_title`, f.`forum_id`, f.`forum_name`, f.`forum_mod`, g.`ug_mod`
		FROM (((`_PREFIX_posts` p
		LEFT JOIN `_PREFIX_topics` t ON t.`topic_id` = p.`post_topic_id`)
		LEFT JOIN `_PREFIX_forums` f ON f.`forum_id` = t.`topic_forum_id`)
		LEFT JOIN `_PREFIX_ug_auth` g ON g.`usergroup` = :user_group AND g.`ug_forum_id` = f.`forum_id`)
		WHERE p.`post_id` = :pid",
		array(
			":user_group" => $user['user_usergroup'],
			":pid" => $_GET['pid']
		)
	);

	if($result = $query->fetch())
	{
		if($result['topic_status'] == 1){
			error_msg("Error", $lang['Topic_Is_Closed']);
		}

		if(!((($result['forum_mod'] <= $user['user_level'] && $result['ug_mod'] == 0) || $result['ug_mod'] == 1) || ($result['post_user_id'] == $user['user_id'] && $user['user_id'] > 0)))
		{

			if($user['user_id'] > 0) {
				error_msg("Error", $lang['User_Edit_Msg']);
			}
			else
			{
				header("Location: login.php");
				exit();
			}
		}

		if(isset($_POST['Submit']))
      	{
			CSRF::validate();

      		$error = "";
      		if(strlen($_POST['body']) < 1)
      		{
      			$error .= $lang['No_Post_Content'] . "<br />";
      		}
      		if(strlen($error) > 0)
      		{
				$page_master = new Template("post.tpl");
				$page_master->setVars(array(
      				"ACTION" => $lang['Edit'],
      				"TOPIC_ID" => $result['topic_id'],
      				"TOPIC_NAME" => $result['topic_title'],
      				"FORUM_ID" => $result['forum_id'],
      				"FORUM_NAME" => $result['forum_name'],
      				"BODY" => $_POST['body'],
      				"HTML_ENABLED_MSG" => ($config['html_enabled'] == true) ? sprintf($lang['HTML_is_x'], $lang['enabled']) : sprintf($lang['HTML_is_x'], $lang['disabled']),
      				"BBCODE_ENABLED_MSG" => ($config['bbcode_enabled'] == true) ? sprintf($lang['BBCode_is_x'], $lang['enabled']) : sprintf($lang['BBCode_is_x'], $lang['disabled']),
      				"SMILIES_ENABLED_MSG" => ($config['smilies_enabled'] == true) ? sprintf($lang['Smilies_are_x'], $lang['enabled']) : sprintf($lang['Smilies_are_x'], $lang['disabled']),
					"CSRF_TOKEN" => CSRF::getHTML()
      			));

      			$page_title = $config['site_name'] . " &raquo; " . $result['forum_name'] . " &raquo; " . $result['topic_title'] . " &raquo; " . $lang['Edit'];

				$page_master->addToBlock("nav_reply", array(
					"ACTION" => $lang['Edit'],
					"TOPIC_ID" => $result['topic_id'],
					"TOPIC_NAME" => $result['topic_title'],
					"FORUM_ID" => $result['forum_id'],
					"FORUM_NAME" => $result['forum_name'],
				));

				$page_master->addToBlock("error", array(
      				"ERRORS" => $error
      			));

				renderPostEditBlocks($page_master);

				outputPage($page_master, $page_title);
      		}
      		else
      		{
      			if(!isset($_POST['title'])) $_POST['title'] = "";

      			// Insert post info
      			$db2->query("UPDATE `_PREFIX_posts`
					SET `post_text` = :post_body
					WHERE `post_id` = :pid",
					array(
						":post_body" => $_POST['body'],
						":pid" => $_GET['pid']
					)
				);

      			$query = $db2->query("SELECT `post_topic_id`
					FROM `_PREFIX_posts`
					WHERE `post_id` = :pid",
					array(
						":pid" => $_GET['pid']
					)
				);
      			$result = $query->fetch();

      			$db2->query("UPDATE `_PREFIX_topics`
					SET `topic_time` = :time
					WHERE `topic_id` = :tid",
					array(
						":time" => time(),
						":tid" => $result['post_topic_id']
					)
				);

      			info_box($lang['Edit_Post'], $lang['Post_Edited_Msg'], "view_topic.php?tid=".$result['post_topic_id']."");
      		}
      	}
		else
    	{
			$page_master = new Template("post.tpl");
			$page_master->setVars(array(
				"ACTION" => $lang['Edit'],
				"TOPIC_ID" => $result['topic_id'],
				"TOPIC_NAME" => $result['topic_title'],
				"FORUM_ID" => $result['forum_id'],
				"FORUM_NAME" => $result['forum_name'],
   				"BODY" => $result['post_text'],
   				"HTML_ENABLED_MSG" => ($config['html_enabled'] == true) ? sprintf($lang['HTML_is_x'], $lang['enabled']) : sprintf($lang['HTML_is_x'], $lang['disabled']),
   				"BBCODE_ENABLED_MSG" => ($config['bbcode_enabled'] == true) ? sprintf($lang['BBCode_is_x'], $lang['enabled']) : sprintf($lang['BBCode_is_x'], $lang['disabled']),
   				"SMILIES_ENABLED_MSG" => ($config['smilies_enabled'] == true) ? sprintf($lang['Smilies_are_x'], $lang['enabled']) : sprintf($lang['Smilies_are_x'], $lang['disabled']),
				"CSRF_TOKEN" => CSRF::getHTML()
   			));
   			$page_title = $config['site_name'] . " &raquo; " . $result['forum_name'] . " &raquo; " . $result['topic_title'] . " &raquo; " . $lang['Edit'];

			$page_master->addToBlock("nav_reply", array(
				"ACTION" => $lang['Edit'],
				"TOPIC_ID" => $result['topic_id'],
				"TOPIC_NAME" => $result['topic_title'],
				"FORUM_ID" => $result['forum_id'],
				"FORUM_NAME" => $result['forum_name'],
			));

			renderPostEditBlocks($page_master);

			outputPage($page_master, $page_title);
    	}
	}
	else
	{
		error_msg($lang['Error'], $lang['Invalid_Post_Id']);
	}
}
else if($_GET['func'] == "delete")
{
	// Deleting a topic
	if(isset($_GET['tid'])) {
		$db2->query("SELECT f.`forum_id`, t.`topic_user_id`
			FROM ((`_PREFIX_topics` t
				LEFT JOIN `_PREFIX_forums` f ON f.`forum_id` = t.`topic_forum_id`)
			WHERE t.`topic_id`=:tid",
			array(":tid" => $_GET['tid']));

		if($ug_auth = $db2->fetch()) {

			if($user['user_id'] != $ug_auth['post_user_id']) {
				error_msg($lang['Error'], $lang['Invalid_Permissions_Mod'] . "topic_user_id : " . $ug_auth['topic_user_id']);

			}
			else if ($ug_auth['topic_status'] == 1){
				error_msg($lang['Error'], $lang['Topic_Is_Closed']);
			}
			else if ($user['user_id'] < 0){
				header("Location: login.php");
				exit;
			}
//				if($user['user_id'] > 0) {
//					error_msg($lang['Error'], $lang['Invalid_Permissions_Mod']);
//				} else {
//					header("Location: login.php");
//					exit;
//				}

			$fid = $ug_auth['forum_id'];
		} else {
			error_msg($lang['Error'], $lang['Invalid_Topic_Id']);
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
			info_box($lang['Delete_Topic'], $lang['Topic_Deleted_Msg'], "view_forum.php?fid=$fid");
		}
	}
	else if(isset($_GET['pid']))
	{
		// Get the current thread and the id of the user who posted the comments.
		$result = $db2->query("SELECT  t.`topic_id`, t.`topic_status`, f.`forum_id`, p.`post_user_id`
					  FROM ((_PREFIX_posts p
							LEFT JOIN `_PREFIX_topics` t ON t.`topic_id` = p.`post_topic_id`)
							LEFT JOIN `_PREFIX_forums` f ON f.`forum_id` = t.`topic_forum_id`)
					  WHERE p.`post_id` = :pid",
			array(":pid" => $_GET['pid']));

		// The can only delete his own post!
		if($ug_auth = $result->fetch()) {
			if($user['user_id'] != $ug_auth['post_user_id']) {
				error_msg($lang['Error'], $lang['Invalid_Permissions_Mod']);

			}
			else if ($ug_auth['topic_status'] == 1){
				error_msg($lang['Error'], $lang['Topic_Is_Closed']);
			}
			else if ($user['user_id'] < 0){
				header("Location: login.php");
				exit;
			}

			$tid = $ug_auth['topic_id'];
			$fid = $ug_auth['forum_id'];
		} else {
			error_msg($lang['Error'], $lang['Invalid_Post_Id']);
		}

		$db2->query("SELECT count(*) AS `cc` FROM `_PREFIX_posts` WHERE `post_topic_id`=:tid", array(":tid" => $tid));
		if($result = $db2->fetch()) {
			if($result["cc"] == 1) {
				error_msg($lang['Error'], sprintf($lang['Delete_Last_Post_In_Topic_Msg'], "<a href=\"view_topic.php?tid=$tid\">", "</a>"));
			}
		}

		if(!isset($_GET['confirm']) || $_GET['confirm'] != "1") {
			$db2->query("SELECT t.`topic_id`
				FROM (`_PREFIX_posts` p
					LEFT JOIN `_PREFIX_topics` t ON t.`topic_id` = p.`post_topic_id`)
				WHERE p.`post_id`=:pid",
				array(":pid" => $_GET['pid']));

			$topic = $db2->fetch();
			confirm_msg($lang['Delete_Post'], $lang['Delete_Post_Confirm_Msg'], "posting.php?func=delete&pid=".$_GET['pid']."&confirm=1", "view_topic.php?tid=".$topic['topic_id']."");
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
				error_msg($lang['Error'], $lang['Select_last_post_in_topic_error']);
			}

			$db2->query("SELECT p.`post_id`
				FROM (`_PREFIX_topics` t
					LEFT JOIN `_PREFIX_posts` p ON p.`post_id` = t.`topic_last_post`)
				ORDER BY t.`topic_time` DESC LIMIT 1");

			info_box($lang['Delete_Post'], $lang['Post_Deleted_Msg'], "view_topic.php?tid=$tid");
		}
	}
}
else if(isset($_GET['mark']))
{
	if(preg_match("/^[0-9]+$/", $_GET['mark']))
	{
		$marked_read = (isset($_COOKIE['marked_read'])) ? unserialize($_COOKIE['marked_read']) : array();
		$marked_read[$_GET['mark']] = time();
		setcookie("marked_read", serialize($marked_read));
		header("Location: index.php");
	}
	else
	{
		error_msg($lang['Error'], $lang['Marked_forum_id_must_be_positive']);
	}
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright � 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
