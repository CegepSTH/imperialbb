<?php
define("IN_IBB", 1);
$root_path = "./";
require_once($root_path . "includes/common.php");
$language->add_file("view_forum");
Template::addNamespace("L", $lang);

if(!isset($_GET['fid']) || !is_numeric($_GET['fid'])) {
	showMessage(ERR_CODE_INVALID_FORUM_ID);
}

$fid = intval($_GET['fid']);

$db_forum = $db2->query("SELECT * FROM (`_PREFIX_forums` f
	LEFT JOIN `_PREFIX_ug_auth` g ON g.`usergroup`=:ugroup
		AND g.`ug_forum_id` = f.`forum_id`)
	WHERE `forum_id`=:fid LIMIT 1", 
	array(":ugroup" => $user['user_usergroup'], ":fid" => $fid));

$forum = $db_forum->fetch();
if(!($forum)) {
	showMessage(ERR_CODE_INVALID_FORUM_ID);
}

// Does the user have permission to read this forum?
if(!(($forum['forum_read'] <= $user['user_level'] && $forum['ug_read'] == 0) || $forum['ug_read'] == 1)) {
	if($user['user_id'] > 0) {
		showMessage(ERR_CODE_INVALID_PERMISSION_READ);
	} else {
		showMessage(ERR_CODE_REQUIRE_LOGIN);
	}
}

// Check if forum has redirection rule.
if($forum['forum_redirect_url'] != null) {
	$db2->query("UPDATE `_PREFIX_forums` 
		SET `forum_topics` = (`forum_topics` + 1) 
		WHERE `forum_id`=:fid", 
		array(":fid" => $forum['forum_id']));
			
	header("Location: ".$forum['forum_redirect_url']);
	exit();
}

// Instanciate template data.
$tplViewForum = new Template("view_forum.tpl");
$tplViewForum->setVars(array(
	"FORUM_NAME" => $forum['forum_name'],
	"TIMEZONE" => sprintf($lang['All_Times_Are_TZ'], $lang['tz'][$user['user_timezone']])
));

// Route.
$forum_route = array();
$forum_route[1]['id'] = $forum['forum_id'];
$forum_route[1]['name'] = $forum['forum_name'];
$forum_route_last = 1;
$forum_route_next = $forum['forum_cat_id'];

// If it is a forum.
if($forum['forum_type'] == "f") {
	$forum_trace = false;
		
	while($forum_trace == false) {
		$db2->query("SELECT `forum_id`, `forum_cat_id`, `forum_name`, `forum_type`
			 FROM `_PREFIX_forums`
			 WHERE `forum_id`=:route", array(":route" => $forum_route_next));

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
			
			// If it is a category.
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

// Add forum route to template.
$page_title = $config['site_name'];
for($i = count($forum_route); $i >= 1; $i--) {
	$page_title .= " &raquo; " . $forum_route[$i]['name'];
}

// idem.
for($i = count($forum_route); $i >= 2; $i--) {
	$tplViewForum->addToBlock("menu_top_forum", array(
		"MENU_FORUM_ID" => $forum_route[$i]['id'],
		"MENU_FORUM_NAME" => $forum_route[$i]['name']));

	$tplViewForum->addToBlock("menu_bottom_forum", array(
		"MENU_FORUM_ID" => $forum_route[$i]['id'],
		"MENU_FORUM_NAME" => $forum_route[$i]['name']));
}

// Get all forum informations from DB.
$db_forum = $db2->query("SELECT f.`forum_id`, f.`forum_name`, f.`forum_description`, f.`forum_redirect_url`,
	f.`forum_topics`, f.`forum_posts`, f.`forum_read`, g.`ug_read`, p.`post_timestamp`, t.`topic_id`, t.`topic_title`, u.`user_id`, u.`username`
	FROM ((((`_PREFIX_forums` f
		LEFT JOIN `_PREFIX_ug_auth` g ON g.`usergroup`=:ugroup
			AND g.`ug_forum_id` = f.`forum_id`)
		LEFT JOIN `_PREFIX_posts` p ON p.`post_id` = f.`forum_last_post`)
		LEFT JOIN `_PREFIX_topics` t ON t.`topic_id` = p.`post_topic_id`)
		LEFT JOIN `_PREFIX_users` u ON u.`user_id` = p.`post_user_id`)
	WHERE f.`forum_cat_id`=:fid
		AND f.`forum_type` = 'f' ORDER BY f.`forum_orderby`", 
	array(":ugroup" => $user['user_usergroup'], ":fid" => $fid));

$subforum_count = 0;
$blockSubforums = "";
while($forum_result = $db_forum->fetch()) {
	$blockSubForumsList = "";
	if(($forum_result['forum_read'] <= $user['user_level'] && $forum_result['ug_read'] == 0) || $forum_result['ug_read'] == 1) {
		
		// CHECK COOKIES FOR IS_READ
		$new_posts = false;
		if($user['user_id'] > 0) {
			$track_topics = (isset($_COOKIE['read_topics'])) ? unserialize($_COOKIE['read_topics']) : "";
			$marked_read = (isset($_COOKIE['marked_read'])) ? unserialize($_COOKIE['marked_read']) : 0;

			if($user['user_lastvisit'] < $forum_result['post_timestamp'] 
				&& ((!isset($marked_read['0']) || $marked_read['0'] < $forum_result['post_timestamp']) 
				&& (!isset($marked_read[$forum['forum_id']]) 
				|| $marked_read[$forum['forum_id']] <  $forum_result['post_timestamp'])))
			{
				if(!empty($track_topics) && isset($track_topics[$forum_result['topic_id']])) {
					if($track_topics[$forum_result['topic_id']] < $forum_result['post_timestamp']) {
						$new_posts = true;
					}
				} else {
					$new_posts = true;
				}
			}
		}
				
		$blockNewPost = "";
		if($new_posts) {
			$blockNewPost = $tplViewForum->renderBlock("new_posts_on", array());
		} else {
			$blockNewPost = $tplViewForum->renderBlock("new_posts_off", array());
		}
		
		// Check last post.
		$blockLastPost = "";
		if(!empty($forum_result['topic_id'])) {
			$blockLastPost = $tplViewForum->renderBlock("last_post_on", array(
				"SUBFORUM_LAST_POST_ID" => $forum_result['topic_id'],
				"SUBFORUM_LAST_POST_TITLE" => $forum_result['topic_title'],
				"SUBFORUM_LAST_POST_DATE" => create_date("D d M Y ", $forum_result['post_timestamp']),
				"SUBFORUM_LAST_POST_AUTHOR" => ( $forum_result['user_id'] == -1 ) ? "<b>".$forum_result['username']."</b>" : "<a href=\"profile.php?id=" . $forum_result['user_id'] . "\">".$forum_result['username']."</a>"
			));
				
		} else {
			$blockLastPost = $tplViewForum->renderBlock("last_post_off", array());
		}
				
		$db_sub = $db2->query("SELECT `forum_id`, `forum_name`
			FROM `_PREFIX_forums`
			WHERE `forum_cat_id`=:fid
				AND `forum_type` = 'f' LIMIT 5", 
			array(":fid" => $forum_result['forum_id']));
					
		$subforum_count = 0;
		$subforum_data = "";
			
		while($subforums_result = $db_sub->fetch()) {
			$subforum_data .= "<a href=\"view_forum.php?fid=".$subforums_result['forum_id']."\">".shortentext($subforums_result['forum_name'], 20, false)."</a>, ";
			$subforum_count++;
		}
			
		if($subforum_count > 0) {
			$blockSubForumsList = $tplViewForum->renderBlock("block_subforums_list", 
				array("SUBFORUMS" => sprintf($lang['Subforums_List'], $subforum_data)));
			$subforum_data = substr($subforum_data, 0, -2);
		}

		if($forum_result['forum_redirect_url'] != null) {
			$blockSubforums .= $tplViewForum->renderBlock("forum_row_off", array(
				"SUBFORUM_ID" => $forum_result['forum_id'],
				"SUBFORUM_NAME" => $forum_result['forum_name'],
				"SUBFORUM_DESCRIPTION" => $forum_result['forum_description'],
				"SUBFORUM_REDIRECTS" => sprintf($lang['Redirect_Hits_X'], $forum_result['forum_topics']),
			));
		
		} else {
			$blockSubForums .= $tplViewForum->renderBlock("forum_row_on", array(
				"SUBFORUM_ID" => $forum_result['forum_id'],
				"SUBFORUM_NAME" => $forum_result['forum_name'],
				"SUBFORUM_DESCRIPTION" => $forum_result['forum_description'],
				"SUBFORUM_TOPICS" => $forum_result['forum_topics'],
				"SUBFORUM_POSTS" => $forum_result['forum_posts'],
				"block_new_posts" => $blockNewPost,
				"block_last_post" => $blockLastPost,
				"block_subforums_list" => $blockSubForumsList,
				"block_last_post" => $blockLastPost
			));
		}

		$subforum_count++;
	}
}
	
if($subforum_count > 0) {
	$tplViewForum->addToBlock("subforums", array(
		"block_forum_row" => $blockSubForums)); 
}

$db2->query("SELECT * FROM `_PREFIX_topics` WHERE `topic_forum_id`=:fid", array(":fid" => $fid));
$topic_count = $db2->rowCount();

$pagination = $pp->paginate($topic_count, $config['topics_per_page']);

$tplViewForum->setVar("PAGINATION", $pagination);
	
$db2->query("SELECT t.`topic_id`, t.`topic_title`, t.`topic_poll_title`, t.`topic_type`, t.`topic_replies`, t.`topic_views`, t.`topic_first_post`, t.`topic_time`, lp.`post_timestamp`, u.`user_id`, u.`username`, a.`user_id` AS 'author_id', a.`username` AS 'author_username'
	FROM ((((`_PREFIX_topics` t
		LEFT JOIN `_PREFIX_posts` fp ON fp.`post_id` = t.`topic_first_post`)
		LEFT JOIN `_PREFIX_posts` lp ON lp.`post_id` = t.`topic_last_post`)
		LEFT JOIN `_PREFIX_users` u ON u.`user_id` = lp.`post_user_id`)
		LEFT JOIN `_PREFIX_users` a ON a.`user_id` = fp.`post_user_id`)
	WHERE t.`topic_forum_id`=:fid
	ORDER BY t.`topic_type` DESC, t.`topic_time` DESC
	LIMIT ".$pp->limit."",
	array(":fid" => $fid));
		
while ($topic = $db2->fetch()) {
	$topic['user'] = ($topic['user_id'] > 0) ? "<a href='profile.php?id='".$topic['user_id']."'><b>".$topic['username']."</b></a>" : "<b>".$topic['username']."</b>";
	if(!empty($topic['topic_poll_title'])) {
		if($topic['topic_type'] != GENERAL) {
			$poll_prefix = $lang['Poll'] . ", ";
		} else {
			$poll_prefix = $lang['Poll'] . ": ";
		}
	} else {
		$poll_prefix = "";
	}
		
	if($topic['topic_type'] == ANNOUNCMENT) {
		$img_url = "announcment";
		$title_prefix = $poll_prefix . $lang['Announcment'] . ": ";
	} else if($topic['topic_type'] == PINNED) {
		$img_url = "pinned";
		$title_prefix = $poll_prefix . $lang['Pinned'] . ": ";
	} else {
		$img_url = "general";
		$title_prefix = $poll_prefix . "";
	}

	$new_posts = false;
	if($user['user_id'] > 0) {
		$marked_read = (isset($_COOKIE['marked_read'])) ? unserialize($_COOKIE['marked_read']) : array();
		if($user['user_lastvisit'] < $topic['post_timestamp'] && ((!isset($marked_read['0']) 
			|| $marked_read['0'] < $topic['post_timestamp']) && (!isset($marked_read[$fid]) 
			|| $marked_read[$fid] < $topic['post_timestamp'])))
		{
			$track_topics = (isset($_COOKIE['read_topics'])) ? unserialize($_COOKIE['read_topics']) : "";
			if(!empty($track_topics) && isset($track_topics[$topic['topic_id']])) {
				if($track_topics[$topic['topic_id']] < $topic['post_timestamp']) {
					$new_posts = true;
				}
			} else {
				$new_posts = true;
			}
		}
	}
		
	$blockProwNewPosts = "";
	if($new_posts) {
		$blockProwNewPosts = $tplViewForum->renderBlock("prow_new_posts_on", array(
			"IMAGE_URL" => $img_url,
			"IMAGE_TITLE_PREFIX" => $title_prefix
		));
	} else {
		$blockProwNewPosts = $tplViewForum->renderBlock("prow_new_posts_off", array(
			"IMAGE_URL" => $img_url,
			"IMAGE_TITLE_PREFIX" => $title_prefix
		));
	}	
	
	$tplViewForum->addToBlock("postrow", array(
		"TOPIC_ID" => $topic['topic_id'],
		"TOPIC_NAME" => $title_prefix . $topic['topic_title'],
		"AUTHOR" => ($topic['author_id'] < 1) ? $topic['author_username'] : "<a href=\"profile.php?id=".$topic['author_id']."\">".$topic['author_username']."</a>",
		"REPLIES" => $topic['topic_replies'],
		"VIEWS" => $topic['topic_views'],
		"LAST_POST" => create_date("D d M Y g:i a", $topic['post_timestamp'])."<br />".$topic['user']." <a href=\"view_topic.php?tid=".$topic['topic_id']."\"><b>&gt;&gt;</b></a>",
		"block_prow_new_posts" => $blockProwNewPosts
	));
}
    
$tplViewForum->setVar("FID", $fid);
	
outputPage($tplViewForum);
?>
