<?php
define("IN_IBB", 1);

$root_path = "./";
require_once($root_path . "includes/common.php");
$language->add_file("view_topic");
Template::addNamespace("L", $lang);

if(!isset($_GET['tid']) || trim($_GET['tid']) == "") showMessage(ERR_CODE_NO_TOPIC_ID_SPECIFIED, "index.php");

if(isset($_POST['vote'])) {
	CSRF::validate();

	$cookie_vote_array = isset($_COOKIE['poll_votes']) ? unserialize($_COOKIE['poll_votes']) : array();
    if(!isset($_SESSION['poll_votes'])) $_SESSION['poll_votes'] = array();

	// Check for vote cast validity.
	if(in_array($_GET['tid'], $cookie_vote_array) || in_array($_GET['tid'], $_SESSION['poll_votes'])) {
		showMessage(ERR_CODE_VOTE_ALREADY_CASTED, "view_topic.php?tid=".$_GET['tid']);
	}

	// Check for invalid vote id.
	if(!preg_match("#([0-9]|1[0-5])#", $_POST['poll_vote_choice']) || $_POST['poll_vote_choice'] < 1 || $_POST['poll_vote_choice'] > 15) {
		showMessage(ERR_CODE_INVALID_VOTE_ID, "view_topic.php?tid=".$_GET['tid']);
	}
	
	$db2->query("UPDATE `_PREFIX_pollvotes` 
		SET `poll_choice_votes` = (poll_choice_votes+1) 
		WHERE `poll_choice_id`=:pchoice AND `poll_topic_id`=:tid",
		array(":pchoice" => $_POST['poll_vote_choice'], ":tid" => $_GET['tid']));

	$vote_array[] = $_GET['tid'];
    setcookie("poll_votes", serialize($cookie_vote_array), (time() + 7776000));
	$_SESSION['poll_votes'][] = $_GET['tid'];
	showMessage(ERR_CODE_VOTE_CASTED_SUCCESS, "view_topic.php?tid=".$_GET['tid']."");
}

$topic_sql = $db2->query("SELECT count(p.`post_id`) AS 'post_count', t.*, f.`forum_id`, f.`forum_name`, f.`forum_cat_id`, f.`forum_type`, f.`forum_read`, f.`forum_reply`, f.`forum_mod`, g.`ug_read`, g.`ug_reply`, g.`ug_mod`
	FROM (((`_PREFIX_topics` t
		LEFT JOIN `_PREFIX_posts` p ON p.`post_topic_id` = t.`topic_id`)
		LEFT JOIN `_PREFIX_forums` f ON f.`forum_id` = t.`topic_forum_id`)
		LEFT JOIN `_PREFIX_ug_auth` g ON g.`usergroup`=:ugroup AND g.`ug_forum_id` = f.`forum_id`)
	WHERE t.`topic_id`=:tid
	GROUP BY t.`topic_id`", array(":ugroup" => $user['user_usergroup'], ":tid" => $_GET['tid']));

if($topic = $db2->fetch()) {
	if(!(($topic['forum_read'] <= $user['user_level'] && $topic['ug_read'] == 0) || $topic['ug_read'] == 1)) {
		if($user['user_id'] > 0) {
			showMessage(ERR_CODE_NEED_READ_PERMISSIONS, "index.php");
		} else {
			header("Location: login.php");
			exit();
		}
	}
	
	$new_topic_views = $topic['topic_views'] + 1;
	
	$db2->query("UPDATE `_PREFIX_topics` SET `topic_views`=:ntopic WHERE `topic_id`=:tid",
		array(":ntopic" => $new_topic_views, ":tid" => $topic['topic_id']));
	
	$tplViewTopic = new Template("view_topic.tpl");

	$forum_route = array();
	$forum_route[1]['id'] = $topic['forum_id'];
	$forum_route[1]['name'] = $topic['forum_name'];
	$forum_route_last = 1;
	$forum_route_next = $topic['forum_cat_id'];
	
	if($topic['forum_type'] == "f") {
		$forum_trace = false;
		
		while($forum_trace == false) {
			
			$forum_route_query = $db2->query("SELECT `forum_id`, `forum_cat_id`, `forum_name`, `forum_type` 
				FROM `_PREFIX_forums`
				WHERE `forum_id`=:fid", array(":fid" => $forum_route_next));
				
			if($forum_route_result = $db2->fetch()) {
				foreach($forum_route as $forum_route_check) {
					if($forum_route_check['id'] == $forum_route_result['forum_id']) {
						$forum_trace = true;
						break;
					}
				}
				
				$forum_route_last++;
				$forum_route[$forum_route_last]['id'] = $forum_route_result['forum_id'];
				$forum_route[$forum_route_last]['name'] = $forum_route_result['forum_name'];
				$forum_route_next = $forum_route_result['forum_cat_id'];
				
				if($forum_route_result['forum_type'] == "c") {
					$forum_trace = true;
					break;
				}
			} else {
				$forum_trace = true;
				break;
			}
		}
	}

    //==================================
    // Setup pagination
    //==================================
	$pagination = $pp->paginate($topic['post_count'], $config['posts_per_page']);
	$tplViewTopic->setVars(array(
		"TOPIC_ID" => $topic['topic_id'],
		"FORUM_ID" => $topic['forum_id'],
		"FORUM_NAME" => $topic['forum_name'],
		"TOPIC_NAME" => $topic['topic_title'],
		"PAGINATION" => $pagination,
		"CSRF_TOKEN" => CSRF::getHTML()
	));

	for($i = count($forum_route); $i >= 1; $i--) {
		$tplViewTopic->addToBlock("location_top_forum", array(
			"LOCATION_FORUM_ID" => $forum_route[$i]['id'],
			"LOCATION_FORUM_NAME" => $forum_route[$i]['name']
		));

		$tplViewTopic->addToBlock("location_bottom_forum", array(
			"LOCATION_FORUM_ID" => $forum_route[$i]['id'],
			"LOCATION_FORUM_NAME" => $forum_route[$i]['name']
		));
	}

 	$page_title = $config['site_name'];
 	
	for($i = count($forum_route); $i >= 1; $i--) {
		$page_title .= " &raquo; " . $forum_route[$i]['name'];
	}
	
	$page_title .= " &raquo; " . $topic['topic_title'];

	if(!empty($topic['topic_poll_title'])) {
		$tplViewTopic->addToBlock("poll", array(
			"POLL_TITLE" => $topic['topic_poll_title']
		));

		$cookie_vote_array = isset($_COOKIE['poll_votes']) ? unserialize($_COOKIE['poll_votes']) : array();
    	if(!isset($_SESSION['poll_votes'])) $_SESSION['poll_votes'] = array();

		$vote_cast = false;
		if(in_array($_GET['tid'], $cookie_vote_array) || in_array($_GET['tid'], $_SESSION['poll_votes'])) {
			$vote_cast = true;
		}

		if(isset($_GET['view_poll_results']) && $_GET['view_poll_results'] == 1 || $vote_cast) {
			$query = $db2->query("SELECT SUM(t.`poll_choice_votes`) AS 'poll_total_votes', p.`poll_choice_name`, p.`poll_choice_votes`
				FROM (`_PREFIX_pollvotes` p
					LEFT JOIN `_PREFIX_pollvotes` t ON t.`poll_topic_id`=:tid)
				WHERE p.`poll_topic_id`=:tidd
				GROUP BY p.`poll_choice_name`", 
				array(":tid" => $_GET['tid'], ":tidd" => $_GET['tid']));
				
			while($poll = $db2->fetch()) {
				
				$theme->switch_nest("view_topic", "poll/poll_choice", false, array(
					"POLL_CHOICE_NAME" => $poll['poll_choice_name'],
					"POLL_CHOICE_WIDTH" => strval(round((($poll['poll_choice_votes'] / $poll['poll_total_votes']) * 100), 2)),
					"PERCENTAGE" => strval(round((($poll['poll_choice_votes'] / $poll['poll_total_votes']) * 100), 2)),
					"NO_OF_VOTES" => $poll['poll_choice_votes']
				));
				$theme->add_nest("view_topic", "poll/poll_choice");
			}
			
			// Viewing results.. Dont allow to vote
			if(!$vote_cast && !$config['allow_vote_after_results']) {
				$vote_array[] = $_GET['tid'];
    			setcookie("poll_votes", serialize($cookie_vote_array), (time() + 7776000));
				$_SESSION['poll_votes'][] = $_GET['tid'];
			}
		} else {
			$db2->query("SELECT `poll_choice_id`, `poll_choice_name`
				FROM `_PREFIX_pollvotes`
				WHERE `poll_topic_id`=:tid
				ORDER BY `poll_choice_id`", array(":tid" => $_GET['tid']));
				
			while($poll = $db2->fetch()) {
				$theme->switch_nest("view_topic", "poll/poll_choice", true, array(
					"POLL_CHOICE_ID" => $poll['poll_choice_id'],
					"POLL_CHOICE_NAME" => $poll['poll_choice_name']
				));
				$theme->add_nest("view_topic", "poll/poll_choice");
			}
			
			$theme->insert_nest("view_topic", "poll/poll_vote_buttons");
			$theme->add_nest("view_topic", "poll/poll_vote_buttons");
			$theme->insert_nest("view_topic", "poll/poll_vote_form");
			$theme->add_nest("view_topic", "poll/poll_vote_form");
		}

		$theme->add_nest("view_topic", "poll");
	}

	$newtime = 0;
	$db_posts = $db2->query("SELECT p.`post_id`, p.`post_timestamp`, p.`post_text`, u.`user_id`, u.`username`, u.`user_date_joined`, u.`user_posts`, u.`user_signature`, u.`user_avatar_type`, u.`user_avatar_location`, u.`user_website`, u.`user_location`, r.`rank_name`, r.`rank_image`
		FROM ((`_PREFIX_posts` p
			LEFT JOIN `_PREFIX_users` u ON p.`post_user_id` = u.`user_id`)
			LEFT JOIN `_PREFIX_ranks` r ON r.`rank_id` = u.`user_rank`)
		WHERE `post_topic_id`=:tid
	    ORDER BY `post_id` LIMIT ".$pp->limit."", 
	    array(":tid" => $_GET['tid']));

	while ($post = $db_posts->fetch()) {
		// Try give the user a rank if they dont already have one
		if(empty($post['rank_name']) && $post['user_id'] > 0) {
			$rank_query = $db2->query("SELECT `rank_name`
				FROM `_PREFIX_ranks`
				WHERE `rank_special` = 0 AND `rank_minimum_posts` < :uposts
				ORDER BY `rank_minimum_posts` DESC
				LIMIT 1", array(":uposts" => $post['user_posts']));

			if($rank_result = $db2->fetch()) {
				$post['rank_name'] = $rank_result['rank_name'];
			}
		}


		if(!empty($post['user_signature'])) {
			$post['user_signature'] = format_text($post['user_signature']);
		}
		
		if($post['post_timestamp'] > $newtime) {
			$newtime = $post['post_timestamp'];
		}
		
		if($post['user_avatar_type'] == UPLOADED_AVATAR) {
			$post['user_avatar_location'] = $root_path . $config['avatar_upload_dir'] . "/" . $post['user_avatar_location'];
		}
		$blockMessageItemVars = array(
			"DATE" => create_date("D d M Y", $post['post_timestamp']) . " " . $lang['at'] . " " . create_date("h:i a", $post['post_timestamp']),
			"POST_ID" => $post['post_id'],
			"AUTHOR_ID" => $post['user_id'],
			"AUTHOR_NAME" => $post['username'],
			"AUTHOR_RANK" => ($post['user_id'] > 0) ? $post['rank_name'] : "",
			"TEXT" => format_text($post['post_text']),
			"SIGNATURE" => $post['user_signature'],
			"AUTHOR_JOINED" => create_date("D d M Y", $post['user_date_joined']),
			"AUTHOR_POSTS" => $post['user_posts'],
			"AUTHOR_LOCATION" => $post['user_location'],
			"AUTHOR_RANK" => $post['rank_name'],
			"RANK_IMG_URL" => $post['rank_image'],
			"AUTHOR_AVATAR_LOCATION" => $post['user_avatar_location'], 
			"block_post_mod_links" => "",
			"block_quote_button" => "",
			"block_topic_email_link" => "",
			"block_topic_pm_link" => "",
			"block_topic_profile_link" => "",
			"block_topic_website_link" => "");

		// Verify some moderation tricks + if user can delete or modify message.
		if(($topic['forum_mod'] <= $user['user_level'] && $topic['ug_mod'] == 0) || $topic['ug_mod'] == 1) {
			$blockMessageItemVars["block_post_mod_links"] = $tplViewTopic->renderBlock(
				"post_mod_links_on", array("POST_ID" => $post['post_id'], "TOPIC_ID" => $_GET['tid']));
		} else if($post['user_id'] == $user['user_id'] && $user['user_id'] > 0 && $topic['topic_status'] != 1) {
			$blockMessageItemVars["block_post_mod_links"] = $tplViewTopic->renderBlock(
				"post_mod_links_off", array("POST_ID" => $post['post_id'], "TOPIC_ID" => $_GET['tid']));
		}

		// Can we quote?
		if(($topic['forum_reply'] <= $user['user_level'] && $topic['ug_reply'] == 0) || $topic['ug_reply'] == 1) {
			$blockMessageItemVars["block_quote_button"] = $tplViewTopic->renderBlock("quote_button", 
				array("POST_ID" => $post['post_id'], "TOPIC_ID" => $_GET['tid']));
		}

		// Verify if user has websites and stuff.
		if($post['user_id'] > 0) {
			$blockMessageItemVars["block_topic_pm_link"] = $tplViewTopic->renderBlock("topic_pm_link", array(
				"AUTHOR_USERNAME" => $post['username']));
				
			$blockMessageItemVars["block_topic_email_link"] = $tplViewTopic->renderBlock("topic_email_link", array(
				"AUTHOR_USERNAME" => $post['username']));
			
			$blockMessageItemVars["block_topic_profile_link"] = $tplViewTopic->renderBlock(
				"topic_profile_link", array("AUTHOR_ID" => $post['user_id']));
			
			if(!empty($post['user_website'])) {
				$blockMessageItemVars["block_topic_website_link"] = $tplViewTopic->renderBlock(
					"topic_website_link", array("AUTHOR_WEBSITE" => $post['user_website']));
			}
		}
	
		$tplViewTopic->addToBlock("topic_message_item", $blockMessageItemVars);
	}

	// Cookies.
	if($user['user_id'] > 0) {
		$set_new_posts = false;
		
		if($user['user_lastvisit'] < $newtime) {
			$track_topics = (isset($_COOKIE['read_topics'])) ? unserialize($_COOKIE['read_topics']) : "";
			
			if(!empty($track_topics[$_GET['tid']]) && $track_topics[$_GET['tid']] < $newtime) {
				$set_new_posts = true;
			} else if(count($track_topics) <  200) {
				$set_new_posts = true;
			}
		}
		
		if($set_new_posts) {
			$track_topics[$_GET['tid']] = time();
			setcookie("read_topics", serialize($track_topics), 0);
		}
	}
	
	$blockModsVar = array("block_topic_lock" => "",
			"block_topic_move" => "",
			"block_topic_delete" => "",
			"block_topic_announce" => "",
			"block_topic_pin" => "",
			"block_topic_general" => "");
			
	// If the user is moderator.
	if(($topic['forum_mod'] <= $user['user_level'] && $topic['ug_mod'] == 0) || $topic['ug_mod'] == 1) {		
		$blockModsVar["block_topic_delete"] = $tplViewTopic->renderBlock("topic_mod_delete_link", 
			array("CSRF_TOKEN" => CSRF::getHTML(), "TOPIC_ID" => $_GET['tid']));
		$blockModsVar["block_topic_move"] = $tplViewTopic->renderBlock("topic_mod_move_link",
			array("CSRF_TOKEN" => CSRF::getHTML(), "TOPIC_ID" => $_GET['tid']));
		
		// Lockable
		if($topic['topic_status'] == "0") {
			$blockModsVar["block_topic_lock"] = $tplViewTopic->renderBlock("lock_topic_on", 
				array("CSRF_TOKEN" => CSRF::getHTML(),
				"TOPIC_ID" => $_GET['tid']));
		} else {
			$blockModsVar["block_topic_lock"] = $tplViewTopic->renderBlock("lock_topic_off", 
				array("CSRF_TOKEN" => CSRF::getHTML(),
				"TOPIC_ID" => $_GET['tid']));
		}
		
		// Announcable.		
		if($topic['topic_type'] != ANNOUNCMENT) {
			$blockModsVar["block_topic_announce"] = $tplViewTopic->renderBlock("announce_topic", 
				array("CSRF_TOKEN" => CSRF::getHTML(), "TOPIC_ID" => $_GET['tid']));
		}
		
		// Pinnable.
		if($topic['topic_type'] != PINNED) {
			$blockModsVar["block_topic_pin"] = $tplViewTopic->renderBlock("pin_topic", 
				array("CSRF_TOKEN" => CSRF::getHTML(), "TOPIC_ID" => $_GET['tid']));
		}
		
		// Generable.
		if($topic['topic_type'] != GENERAL) {
			$blockModsVar["block_topic_general"] = $tplViewTopic->renderBlock("general_topic", 
				array("CSRF_TOKEN" => CSRF::getHTML(), "TOPIC_ID" => $_GET['tid']));
		}
	}
	
	/* Allows user to delete his own topic; Disabled for now.
	if($topic['topic_user_id'] == $user['user_id'] && $user['user_id'] > 0) {
		if($topic['topic_status'] != 1) {
			
			$theme->switch_nest("view_topic", "mod_links", false);
			$theme->add_nest("view_topic", "mod_links");
		}
	}*/
	
	$tplViewTopic->setVars($blockModsVar);
}
else
{
	// Couldn't find topic with specified id in db.
	showMessage(ERR_CODE_INVALID_TOPIC_ID);
}

outputPage($tplViewTopic);
?>
