<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: view_topic.php                                             # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

define("IN_IBB", 1);

$root_path = "./";
require_once($root_path . "includes/common.php");

$language->add_file("view_topic");

if(!isset($_GET['tid'])) info_box("Error", "No topic ID specified, if you typed the link manually please go to the forum index and try find it from there", "index.php");

if(isset($_POST['vote'])) {
	$cookie_vote_array = isset($_COOKIE['poll_votes']) ? unserialize($_COOKIE['poll_votes']) : array();
    if(!isset($_SESSION['poll_votes'])) $_SESSION['poll_votes'] = array();

	if(in_array($_GET['tid'], $cookie_vote_array) || in_array($_GET['tid'], $_SESSION['poll_votes'])) {
		error_msg($lang['Error'], $lang['vote_already_cast_msg']);
	}

	if(!preg_match("#([0-9]|1[0-5])#", $_POST['poll_vote_choice']) || $_POST['poll_vote_choice'] < 1 || $_POST['poll_vote_choice'] > 15) {
		error_msg($lang['Error'], $lang['invalid_vote_id_msg']);
	}
	
	$db2->query("UPDATE `_PREFIX_pollvotes` 
		SET `poll_choice_votes` = (poll_choice_votes+1) 
		WHERE `poll_choice_id`=:pchoice AND `poll_topic_id`=:tid",
		array(":pchoice" => $_POST['poll_vote_choice'], ":tid" => $_GET['tid']));

	$vote_array[] = $_GET['tid'];
    setcookie("poll_votes", serialize($cookie_vote_array), (time() + 7776000));
	$_SESSION['poll_votes'][] = $_GET['tid'];
	info_box($lang['Vote'], $lang['vote_completion_msg'], "view_topic.php?tid=".$_GET['tid']."");
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
			error_msg($lang['Error'], $lang['Invalid_Permissions_Read']);
		} else {
			header("Location: login.php");
			exit();
		}
	}
	
	$new_topic_views = $topic['topic_views'] + 1;
	
	$db2->query("UPDATE `_PREFIX_topics` SET `topic_views`=:ntopic WHERE `topic_id`=:tid",
		array(":ntopic" => $new_topic_views, ":tid" => $topic['topic_id']));
		
	$theme->new_file("view_topic", "view_topic.tpl", "");

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

	$theme->replace_tags("view_topic", array(
		"TOPIC_ID" => $topic['topic_id'],
		"FORUM_ID" => $topic['forum_id'],
		"FORUM_NAME" => $topic['forum_name'],
		"TOPIC_NAME" => $topic['topic_title'],
		"PAGINATION" => $pagination
	));

	for($i = count($forum_route); $i >= 1; $i--) {
		$theme->insert_nest("view_topic", "location_top_forum", array(
			"LOCATION_FORUM_ID" => $forum_route[$i]['id'],
			"LOCATION_FORUM_NAME" => $forum_route[$i]['name']
		));
		$theme->add_nest("view_topic", "location_top_forum");

		$theme->insert_nest("view_topic", "location_bottom_forum", array(
			"LOCATION_FORUM_ID" => $forum_route[$i]['id'],
			"LOCATION_FORUM_NAME" => $forum_route[$i]['name']
		));
		$theme->add_nest("view_topic", "location_bottom_forum");
	}

 	$page_title = $config['site_name'];
 	
	for($i = count($forum_route); $i >= 1; $i--) {
		$page_title .= " &raquo; " . $forum_route[$i]['name'];
	}
	
	$page_title .= " &raquo; " . $topic['topic_title'];

	if(!empty($topic['topic_poll_title'])) {
		$theme->insert_nest("view_topic", "poll", array(
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
			$post['user_signature'] = "<br /><br />\n----------<br />\n".format_text($post['user_signature']);
		}
		
		if($post['post_timestamp'] > $newtime) {
			$newtime = $post['post_timestamp'];
		}
		// Insert the postrow nest and assign the variables
		$theme->insert_nest("view_topic", "postrow", array(
			"DATE" => create_date("D d M Y", $post['post_timestamp']) . " " . $lang['at'] . " " . create_date("h:i a", $post['post_timestamp']),
			"POST_ID" => $post['post_id'],
			"AUTHOR_ID" => $post['user_id'],
			"AUTHOR_USERNAME" => $post['username'],
			"AUTHOR_RANK" => ($post['user_id'] > 0) ? $post['rank_name'] : "",
			"TEXT" => format_text($post['post_text']),
			"SIGNATURE" => $post['user_signature'],
		));

		if($post['user_id'] > 0) {
			$theme->insert_nest("view_topic", "postrow/author_standard", array(
				"AUTHOR_JOINED" => create_date("D d M Y", $post['user_date_joined']),
				"AUTHOR_POSTS" => $post['user_posts']
			));

			if(!empty($post['user_location'])) {
				$theme->insert_nest("view_topic", "postrow/author_location", array(
					"AUTHOR_LOCATION" => $post['user_location']
				));
			}
		}

		if(!empty($post['rank_image'])) {
			$theme->insert_nest("view_topic", "postrow/rank_image", array(
				"AUTHOR_RANK" => $post['rank_name'],
				"AUTHOR_RANK_IMG" => $post['rank_image']
			));
		}

		if($post['user_avatar_type'] == UPLOADED_AVATAR || $post['user_avatar_type'] == REMOTE_AVATAR) {
			if($post['user_avatar_type'] == UPLOADED_AVATAR) {
				$post['user_avatar_location'] = $root_path . $config['avatar_upload_dir'] . "/" . $post['user_avatar_location'];
			}
			
			$theme->insert_nest("view_topic", "postrow/avatar", array(
				"AUTHOR_AVATAR_LOCATION" => $post['user_avatar_location']
			));
		}

		if(($topic['forum_mod'] <= $user['user_level'] && $topic['ug_mod'] == 0) || $topic['ug_mod'] == 1) {
			$theme->switch_nest("view_topic", "postrow/mod_links", true, array(
				"POST_ID" => $post['post_id']
			));
		} else if($post['user_id'] == $user['user_id'] && $user['user_id'] > 0) {
			$theme->switch_nest("view_topic", "postrow/mod_links", false);
		}

		if(($topic['forum_reply'] <= $user['user_level'] && $topic['ug_reply'] == 0) || $topic['ug_reply'] == 1) {
			$theme->insert_nest("view_topic", "postrow/quote_button");
			$theme->add_nest("view_topic", "postrow/quote_button");
		}

		if($post['user_id'] > 0) {
			$theme->insert_nest("view_topic", "postrow/pm_link", array(
				"AUTHOR_USERNAME" => $post['username'],
			));
			$theme->add_nest("view_topic", "postrow/pm_link");

			$theme->insert_nest("view_topic", "postrow/email_link", array(
				"AUTHOR_USERNAME" => $post['username'],
			));
			$theme->insert_nest("view_topic", "postrow/profile_link", array(
				"AUTHOR_ID" => $post['user_id'],
			));
			
			if(!empty($post['user_website'])) {
				$theme->insert_nest("view_topic", "postrow/website_link", array(
					"AUTHOR_WEBSITE" => $post['user_website']
				));
				$theme->add_nest("view_topic", "postrow/website_link");
			}
		}
		// Add the nest to the page..
		$theme->add_nest("view_topic", "postrow");

	}

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

	if(($topic['forum_mod'] <= $user['user_level'] && $topic['ug_mod'] == 0) || $topic['ug_mod'] == 1) {
		$theme->insert_nest("view_topic", "mod_links");
		
		if($topic['topic_status'] == "0") {
			$theme->switch_nest("view_topic", "mod_links/lock_topic", true);
		} else {
			$theme->switch_nest("view_topic", "mod_links/lock_topic", false);
		}
		
		if($topic['topic_type'] != ANNOUNCMENT) {
			$theme->insert_nest("view_topic", "mod_links/announce_topic");
		}
		
		if($topic['topic_type'] != PINNED) {
			$theme->insert_nest("view_topic", "mod_links/pin_topic");
		}
		
		if($topic['topic_type'] != GENERAL) {
			$theme->insert_nest("view_topic", "mod_links/general_topic");
		}
		
		$theme->add_nest("view_topic", "mod_links");
	}
}
else
{
	info_box($lang['Error'], $lang['Invalid_Topic_Id'], "index.php");
}

//
// Output the page header
//
include_once($root_path . "includes/page_header.php");

//
// Output the main page
//
$theme->output("view_topic");

//
// Output the page footer
//
include_once($root_path . "includes/page_footer.php");

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
