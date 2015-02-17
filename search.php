<?php
define("IN_IBB", 1);

$root_path = "./";
require_once($root_path . "includes/common.php");
$language->add_file("search");
Template::addNamespace("L", $lang);

if(!isset($_GET['func'])) $_GET['func'] = "";

if(isset($_POST['submit']) || $_GET['func'] == "unanswered" || $_GET['func'] == "new") {
	$tplSearchResults = new Template("search_results.tpl");

	if(isset($_POST['submit'])) {
		CSRF::validate();

		$sql = "SELECT p.`post_timestamp`, t.`topic_id`, t.`topic_title`, t.`topic_replies`, t.`topic_views`, f.`forum_id`, u.`user_id`, u.`username`
			FROM `_PREFIX_topics` t 
			JOIN `_PREFIX_forums` f ON f.`forum_id` = t.`topic_forum_id`
			JOIN `_PREFIX_posts` p ON p.`post_topic_id` = t.`topic_id`
			JOIN `_PREFIX_users` u ON u.`user_id` = p.`post_user_id`
			WHERE";
		$values = array();
		
		if($_POST['search_in'] != "all") {
			if(substr($_POST['search_in'], 0, 3) == "cat") {
				$_POST['search_in'] = substr($_POST['search_in'], 4);
				$values[":sein"] = $_POST['search_in'];
				

			} else if(substr($_POST['search_in'], 0, 5) == "forum") {
				$_POST['search_in'] = substr($_POST['search_in'], 6);
				$values[":sein"] = $_POST['search_in'];

			}
		} else {			
		}

		if(!empty($_POST['search_query'])) {
			// Create the search string
			$_POST['search_query'] = "%" . implode("%", explode(" ", $_POST['search_query'])) . "%";
			$values[":squery"] = $_POST['search_query'];
			
				
			if(isset($_POST['search_topic_title']) && isset($_POST['search_post_text'])) {
				$values[":squeryy"] = $_POST['search_query'];
				// Search both topic title and post text
				$sql .= " (t.`topic_title` LIKE :squery OR p.`post_text` LIKE :squeryy)";
			} else if(isset($_POST['search_topic_title'])) {		
				// Search only topic title				
				$sql .= " t.`topic_title` LIKE :squery";
			} else if(isset($_POST['search_post_text'])) {				// Search only post text
				$sql .= " p.`post_text` LIKE :squery";
			} else {				
				$_POST['search_query'] = "";
			}
		}

		if(!empty($_POST['search_author'])) {
						
			if(!empty($_POST['search_query'])) $sql .= " AND";
			
			$values[":sauthor"] = $_POST['search_author'];
			$sql .= " u.`username`=:sauthor";
		}

		$allowed_post_age = array("1", "7", "14", "30", "90", "180", "365");

		if($_POST['post_age'] > 0 && in_array($_POST['post_age'], $allowed_post_age)) {
			// Work out the age in seconds
			$_POST['post_age'] = time() - ($_POST['post_age'] * 24 * 60 * 60);
			if(!empty($_POST['search_query']) || !empty($_POST['search_query'])) $sql .= " AND";
			
			$values[":page"] = intval($_POST['post_age']);
			if($_POST['post_age_type'] == "newer") {				
				$sql .= " p.`post_timestamp` > :page";
			} else if($_POST['post_age_type'] == "older") {		
				$sql .= " p.`post_timestamp` < :page";
			}
		}

		$db2->query($sql, $values);
	}
	else if($_GET['func'] == "unanswered")
	{
		$db2->query("SELECT p.`post_timestamp`, t.`topic_id`, t.`topic_title`, t.`topic_replies`, t.`topic_views`, f.`forum_id`, u.`user_id`, u.`username`
			FROM `_PREFIX_topics` t 
			JOIN `_PREFIX_forums` f ON f.`forum_id` = t.`topic_forum_id`
			JOIN `_PREFIX_posts` p ON p.`post_topic_id` = t.`topic_id`
			JOIN `_PREFIX_users` u ON u.`user_id` = p.`post_user_id`
			WHERE t.`topic_first_post` = t.`topic_last_post`", $values);
	}
	else if($_GET['func'] == "new")
	{
		$db2->query("SELECT p.`post_timestamp`, t.`topic_id`, t.`topic_title`, t.`topic_replies`, t.`topic_views`, f.`forum_id`, u.`user_id`, u.`username`
			FROM `_PREFIX_topics` t 
			JOIN `_PREFIX_forums` f ON f.`forum_id` = t.`topic_forum_id`
			JOIN `_PREFIX_posts` p ON p.`post_topic_id` = t.`topic_id`
			JOIN `_PREFIX_users` u ON u.`user_id` = p.`post_user_id`
			WHERE p.`post_timestamp` > u.`user_lastvisit`", $values);
	}

	while($result = $db2->fetch()) {		
		$result['user'] = ($result['user_id'] > 0) ? "<a href=\"?act=profile&id=".$result['user_id']."\"><b>".$result['username']."</b></a>" : "<b>".$result['username']."</b>";

		$new_posts = false;
		
		if($user['user_id'] > 0) {
			$marked_read = (isset($_COOKIE['marked_read'])) ? unserialize($_COOKIE['marked_read']) : array();
			
			if($user['user_lastvisit'] < $result['post_timestamp'] && ((!isset($marked_read['0']) 
			|| $marked_read['0'] < $result['post_timestamp']) && (!isset($marked_read[$result['forum_id']]) 
			|| $marked_read[$result['forum_id']] < $result['post_timestamp'])))
			{
				$track_topics = (isset($_COOKIE['read_topics'])) ? unserialize($_COOKIE['read_topics']) : "";
				
				if(!empty($track_topics) && isset($track_topics[$result['topic_id']])) {
					if($track_topics[$result['topic_id']] < $result['post_timestamp']) {
						$new_posts = true;
					}
				} else {
					$new_posts = true;
				}
			}
		}

		$blockNewPosts = "";
		if($new_posts) {
			$blockNewPosts = $tplSearchResults->renderBlock("new_posts_on", array());
		} else {
			$blockNewPosts = $tplSearchResults->renderBlock("new_posts_off", array());
		}
		
		$tplSearchResults->addToBlock("searchrow", array(
			"TOPIC_ID" => $result['topic_id'],
			"TOPIC_NAME" => $result['topic_title'],
			"AUTHOR" => ($result['user_id'] < 0) ? $result['username'] : "<a href=\"?act=profile&id=".$result['user_id']."\">".$result['username']."</a>",
			"REPLIES" => $result['topic_replies'],
			"VIEWS" => $result['topic_views'],
			"LAST_POST" => create_date("D d M Y g:i a", $result['post_timestamp'])."<br />".$result['user']." <a href=\"?act=viewtopic&tid=".$result['topic_id']."\"><b>&gt;&gt;</b></a>",
			"block_new_posts" => $blockNewPosts
		));
	}

	$page_title = $config['site_name'] . " » " . $lang['Search_Results'];

	outputPage($tplSearchResults);
	exit();
} else {
	$tplSearch = new Template("search.tpl");
	$tplSearch->setVar("CSRF_TOKEN", CSRF::getHTML());

	$page_title = $config['site_name'] . " » " . $lang['Search'];

	$db_cat = $db2->query("SELECT `cat_id`, `cat_name` 
		FROM `_PREFIX_categories` 
		ORDER BY `cat_orderby`");
		
	while($cat_result = $db_cat->fetch()) {
		$result = $db2->query("SELECT f.`forum_id`, f.`forum_name`, f.`forum_read`, g.`ug_read`
			FROM (`_PREFIX_forums` f
				LEFT JOIN `_PREFIX_ug_auth` g ON g.`usergroup`=:ugroup)
			WHERE `forum_cat_id`=:cid
			ORDER BY `forum_orderby`", array(":ugroup" => $user['user_usergroup'], ":cid" => $cat_result['cat_id']));

		while($forum_result = $result->fetch()) {
			if(($forum_result['forum_read'] <= $user['user_level'] 
				&& $forum_result['ug_read'] == 0) || $forum_result['ug_read'] == 1)
			{
				$blockForumRow .= $tplSearch->renderBlock("forumrow", array(
					"FORUM_ID" => $forum_result['forum_id'],
					"PREFIX" => "+-+",
					"FORUM_NAME" => $forum_result['forum_name']
				));
				
                $blockForumRow .= _generate_category_dropdown($forum_result['forum_id'], "+-+-+");
			}
		}
		
		$tplSearch->addToBlock("catrow", array(
			"CAT_ID" => $cat_result['cat_id'],
			"CAT_NAME" => $cat_result['cat_name'],
			"block_forumrow" => $blockForumRow));
	}
	
	//output
	outputPage($tplSearch);
	exit();
}

function _generate_category_dropdown($forum_id, $prefix, $rows = "")
{
	global $db2, $tplSearch;
	
	$result = $db2->query("SELECT `forum_id`, `forum_name` FROM `_PREFIX_forums`
		WHERE `forum_cat_id`=:fid AND `forum_type` = 'f'
		ORDER BY `forum_orderby` DESC", array(":fid" => $forum_id));

	while($forum_result = $result->fetch()) {
		$rows .= $tplSearch->renderBlock("forumrow", array(
			"FORUM_ID" => $forum_result['forum_id'],
			"PREFIX" => $prefix,
			"FORUM_NAME" => $forum_result['forum_name']
		));

		_generate_category_dropdown($forum_result['forum_id'], $prefix . "-+", $rows);
	}

	return $rows;
}

?>
