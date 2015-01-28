<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: search.php                                                 # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

define("IN_IBB", 1);

$root_path = "./";
require_once($root_path . "includes/common.php");

$language->add_file("search");

if(!isset($_GET['func'])) $_GET['func'] = "";

if(isset($_POST['submit']) || $_GET['func'] == "unanswered" || $_GET['func'] == "new") {
	$theme->new_file("search_results", "search_results.tpl");

	if(isset($_POST['submit'])) {
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
		
		$theme->insert_nest("search_results", "searchrow", array(
			"TOPIC_ID" => $result['topic_id'],
			"TOPIC_NAME" => $result['topic_title'],
			"AUTHOR" => ($result['user_id'] < 0) ? $result['username'] : "<a href=\"?act=profile&id=".$result['user_id']."\">".$result['username']."</a>",
			"REPLIES" => $result['topic_replies'],
			"VIEWS" => $result['topic_views'],
			"LAST_POST" => create_date("D d M Y g:i a", $result['post_timestamp'])."<br />".$result['user']." <a href=\"?act=viewtopic&tid=".$result['topic_id']."\"><b>&gt;&gt;</b></a>"
		));
		
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

		if($new_posts) {
			$theme->switch_nest("search_results", "searchrow/new_posts", true);
		} else {
			$theme->switch_nest("search_results", "searchrow/new_posts", false);
		}

		$theme->add_nest("search_results", "searchrow");
	}

	$page_title = $config['site_name'] . " » " . $lang['Search_Results'];

	//
	// Output the page header
	//
	include_once($root_path . "includes/page_header.php");

	//
	// Output the main page
	//
	$theme->output("search_results");

	//
	// Output the page footer
	//
	include_once($root_path . "includes/page_footer.php");

}
else
{
	$theme->new_file("search", "search.tpl");

	$page_title = $config['site_name'] . " » " . $lang['Search'];

	$db_cat = $db2->query("SELECT `cat_id`, `cat_name` FROM `_PREFIX_categories` ORDER BY `cat_orderby`");
	while($cat_result = $db_cat->fetch())
	{
		$theme->insert_nest("search", "catrow", array(
			"CAT_ID" => $cat_result['cat_id'],
			"CAT_NAME" => $cat_result['cat_name']
		));

		$forum_count = 0;
		$db2->query("SELECT f.`forum_id`, f.`forum_name`, f.`forum_read`, g.`ug_read`
			FROM (`_PREFIX_forums` f
				LEFT JOIN `_PREFIX_ug_auth` g ON g.`usergroup`=:ugroup)
			WHERE `forum_cat_id`=:cid
			ORDER BY `forum_orderby`", array(":ugroup" => $user['user_usergroup'], ":cid" => $cat_result['cat_id']));

		while($forum_result = $db2->fetch()) {
			if(($forum_result['forum_read'] <= $user['user_level'] && $forum_result['ug_read'] == 0) || $forum_result['ug_read'] == 1)
			{				
				$theme->insert_nest("search", "catrow/forumrow", array(
					"FORUM_ID" => $forum_result['forum_id'],
					"PREFIX" => "+-+",
					"FORUM_NAME" => $forum_result['forum_name']
				));
				
				$theme->add_nest("search", "catrow/forumrow");
                _generate_category_dropdown($forum_result['forum_id'], "+-+-+");
				$forum_count++;
			}
		}

		if($forum_count > 0) {			
			$theme->add_nest("search", "catrow");
		}
	}
	//
	// Output the page header
	//
	include_once($root_path . "includes/page_header.php");

	//
	// Output the main page
	//
	$theme->output("search");

	//
	// Output the page footer
	//
	include_once($root_path . "includes/page_footer.php");
}

function _generate_category_dropdown($forum_id, $prefix)
{
	global $db2, $theme;

	$db2->query("SELECT `forum_id`, `forum_name` FROM `_PREFIX_forums`
		WHERE `forum_cat_id`=:fid AND `forum_type` = 'f'
		ORDER BY `forum_orderby` DESC", array(":fid" => $forum_id));

	while($forum_result = $db2->fetch()) {
		$theme->insert_nest("search", "catrow/forumrow", array(
			"FORUM_ID" => $forum_result['forum_id'],
			"PREFIX" => $prefix,
			"FORUM_NAME" => $forum_result['forum_name']
		));
		
		$theme->add_nest("search", "catrow/forumrow");

		_generate_category_dropdown($forum_result['forum_id'], $prefix . "-+");
	}

	return true;
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
