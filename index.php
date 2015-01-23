<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: index.php                                                  # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

define("IN_IBB", 1);

$root_path = "./";
include($root_path . "includes/common.php");

$language->add_file("board_home");

$theme->new_file("board_home", "board_home.tpl", "");

if($user['user_id'] > 0)
{
	// Get PM info
	$sql = $db2->query("SELECT count(u.`pm_id`) AS 'unread_count', count(r.`pm_id`) AS 'read_count'
		FROM (`".$db_prefix."pm` r LEFT JOIN `".$db_prefix."pm` u ON r.`pm_id` = u.`pm_id` AND u.`pm_unread` = '1')
		WHERE r.`pm_send_to` = :user_id",
		array(
			':user_id' => $user['user_id']
		)
	);

	$result = $sql->fetch();

	$pm_unread_count = $result['unread_count'];
	$pm_read_count = $result['read_count'];

	if($pm_unread_count > 0) {
		$pm_info = sprintf($lang['You_have_x_new_pms'], $pm_unread_count);
	}
	else {
		$pm_info = $lang['You_have_no_new_pms'];
	}

	$theme->replace_tags("board_home",  array(
		"PRIVATE_MESSAGE_INFO" => $pm_info,
		"CURRENT_TIME" => create_date("D d M Y", time()),
		"ALL_TIMES_ARE_TIMEZONE" => sprintf($lang['All_Times_Are_TZ'], $lang['tz'][$user['user_timezone']])
	));
}
else
{
	$theme->replace_tags("board_home",  array(
		"CURRENT_TIME" => create_date("D d M Y", time()),
		"ALL_TIMES_ARE_TIMEZONE" => sprintf($lang['All_Times_Are_TZ'], $lang['tz'][$config['timezone']])
	));
}

//Fetch the forum stats//
load_forum_stats();

$cat_no = "0";
$cat_sql = $db2->query("SELECT `cat_id`, `cat_name`
	 FROM `".$db_prefix."categories`
	 ORDER BY `cat_orderby`");

while ($category = $cat_sql->fetch())
{
	$theme->insert_nest("board_home", "catrow", array(
		"CAT_ID" => $category['cat_id'],
		"CAT_NAME" => $category['cat_name']
	));

	$forum_no = 0;
	if(!isset($_GET['cid']) || $_GET['cid'] == $category['cat_id'])
	{
		$forum_sql = $db2->query("SELECT f.*, g.`ug_read`
			FROM (`".$db_prefix."forums` f
			LEFT JOIN `".$db_prefix."ug_auth` g ON g.`usergroup` = '".$user['user_usergroup']."' AND g.`ug_forum_id` = f.`forum_id`)
			WHERE f.forum_cat_id = '"  . $category['cat_id'] .  "' AND f.`forum_type` = 'c'
			ORDER BY f.forum_orderby");
		while ($forum = $forum_sql->fetch())
		{
			if(($forum['forum_read'] <= $user['user_level'] && $forum['ug_read'] == 0) || $forum['ug_read'] == 1)
			{
				if($forum['forum_redirect_url'] != null)
				{
					$theme->switch_nest("board_home", "catrow/forumrow", false, array(
						"FORUM_ID" => $forum['forum_id'],
						"FORUM_NAME" => $forum['forum_name'],
						"FORUM_DESCRIPTION" => $forum['forum_description'],
						"REDIRECT_HITS" => sprintf($lang['Redirect_Hits_X'], $forum['forum_topics']),
					));
					$theme->add_nest("board_home", "catrow/forumrow");
				}
				else
				{

					$theme->switch_nest("board_home", "catrow/forumrow", true, array(
						"FORUM_ID" => $forum['forum_id'],
						"FORUM_NAME" => $forum['forum_name'],
						"FORUM_DESCRIPTION" => $forum['forum_description'],
						"TOPICS" => $forum['forum_topics'],
						"POSTS" => $forum['forum_posts']
					));
	
					// List the subforums of each forum.
					$subforums_query = $db2->query("SELECT `forum_id`, `forum_name` FROM `".$db_prefix."forums`
						WHERE `forum_cat_id` = :forum_id AND `forum_type` = 'f'
						LIMIT 5",
						array(
							':forum_id' => $forum['forum_id']
						)
					);
					
	
					$subforum_count = 0;
					$subforum_data = "";
					while($subforums_result = $subforums_query->fetch())
					{
						$subforum_data .= "<a href=\"view_forum.php?fid=" . $subforums_result['forum_id'] . "\">" . shortentext($subforums_result['forum_name'], 20, false) . "</a>, ";
						$subforum_count++;
					}
	
					if($subforum_count > 0)
					{
						$subforum_data = substr($subforum_data, 0, -2);
	
						$theme->insert_nest("board_home", "catrow/forumrow/subforums_list", array(
							"SUBFORUMS" => sprintf($lang['Subforums_List'], $subforum_data)
						));
						$theme->add_nest("board_home", "catrow/forumrow/subforums_list");
					}
	
					if($forum['forum_last_post'] == 0)
					{
						$theme->switch_nest("board_home", "catrow/forumrow/last_post", false, "", 2);
						$theme->switch_nest("board_home", "catrow/forumrow/new_posts", false, "", 2);
					}
					else
					{
						$last_post_sql = $db2->query("SELECT p.*, t.`topic_title`, u.`username`
							FROM ((`".$db_prefix."posts` p
							LEFT JOIN `".$db_prefix."topics` t ON t.`topic_id` = p.`post_topic_id`)
							LEFT JOIN `".$db_prefix."users` u ON u.`user_id` = p.`post_user_id`)
							WHERE p.`post_id` = '" . $forum['forum_last_post'] . "'",
							array(
								':post_id' => $forum['forum_last_post']
							)
						);
						if($last_post = $last_post_sql->fetch())
						{
							$new_posts = false;
							if($user['user_id'] > 0)
							{
								$track_topics = (isset($_COOKIE['read_topics'])) ? unserialize($_COOKIE['read_topics']) : "";
								$marked_read = (isset($_COOKIE['marked_read'])) ? unserialize($_COOKIE['marked_read']) : 0;
	
								if($user['user_lastvisit'] < $last_post['post_timestamp'] && ((!isset($marked_read['0']) || $marked_read['0'] < $last_post['post_timestamp']) && (!isset($marked_read[$forum['forum_id']]) || $marked_read[$forum['forum_id']] < $last_post['post_timestamp'])))
								{
									if(!empty($track_topics) && isset($track_topics[$last_post['post_topic_id']]))
									{
										if($track_topics[$last_post['post_topic_id']] < $last_post['post_timestamp'])
										{
											$new_posts = true;
										}
									}
									else
									{
										$new_posts = true;
									}
								}
							}
	
							if($new_posts)
							{
								$theme->switch_nest("board_home", "catrow/forumrow/new_posts", true);
							}
							else
							{
								$theme->switch_nest("board_home", "catrow/forumrow/new_posts", false);
							}
	
							$theme->switch_nest("board_home", "catrow/forumrow/last_post", true, array(
								"LAST_POST_ID" => $last_post['post_topic_id'],
								"LAST_POST_TITLE" => shortentext($last_post['topic_title'], 20, false),
								"LAST_POST_DATE" => create_date("D d M Y ", $last_post['post_timestamp']) . " at " . create_date("h:i a", $last_post['post_timestamp']),
								"LAST_POST_AUTHOR" => ( $last_post['post_user_id'] == -1 ) ? "<b>".$last_post['username']."</b>" : "<a href=\"profile.php?id=" . $last_post['post_user_id'] . "\">".$last_post['username']."</a>"), 2);
						}
						else
						{
							$db2->query("UPDATE `".$db_prefix."forums`
								SET `forum_last_post` = '0'
								WHERE `forum_id` = :forum_id",
								array(
									':forum_id' => $forum['forum_id']
								)
							);
							$theme->switch_nest("board_home", "catrow/forumrow/last_post", false, "", 2);
							$theme->switch_nest("board_home", "catrow/forumrow/new_posts", false, "", 2);
						}
					}
	                $theme->add_nest("board_home", "catrow/forumrow");
				}
				$forum_no++;
			}
		}

		if($forum_no == 0)
		{
			$theme->switch_nest("board_home", "catrow/forum_titles", false, "");
		}
		else
		{
			$theme->switch_nest("board_home", "catrow/forum_titles", true, "");
		}

		if($cat_no > 0)
		{
			$theme->insert_nest("board_home", "catrow/break_line");
			$theme->add_nest("board_home", "catrow/break_line");
		}

		$cat_no++;

		if($forum_no != 0)
		{
			$theme->add_nest("board_home", "catrow");
		}
	}
}

include($root_path . "includes/page_header.php");
$theme->output("board_home");
include($root_path . "includes/page_footer.php");

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
