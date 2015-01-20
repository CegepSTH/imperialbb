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
include($root_path . "includes/common.php");

$language->add_file("mod");

if(!isset($_GET['func'])) $_GET['func'] = "";

if($_GET['func'] == "delete")
{
	// Deleting a topic
	if(isset($_GET['tid']))
	{   $ug_auth_sql = $db->query("SELECT f.`forum_id`, f.`forum_mod`, g.`ug_mod` FROM ((`".$db_prefix."topics` t LEFT JOIN `".$db_prefix."forums` f ON f.`forum_id` = t.`topic_forum_id`) LEFT JOIN `".$db_prefix."ug_auth` g ON g.`usergroup` = '".$user['user_usergroup']."' AND g.`ug_forum_id` = f.`forum_id`)  WHERE t.`topic_id` = '".$_GET['tid']."'");
	if($ug_auth = $db->fetch_array($ug_auth_sql))
	{
		if(!(($ug_auth['forum_mod'] <= $user['user_level'] && $ug_auth['ug_mod'] == 0) || $ug_auth['ug_mod'] == 1))
			{
			if($user['user_id'] > 0)
				{
					error_msg($lang['Error'], $lang['Invalid_Permissions_Mod']);
				}
				else
				{
					header("Location: login.php");
					exit;
				}
			}
			$fid = $ug_auth['forum_id'];
		}
		else
		{			error_msg($lang['Error'], $lang['Invalid_Topic_Id']);
		}
		if(!isset($_GET['confirm']) || $_GET['confirm'] != "1")
		{			$query = $db->query("SELECT `topic_title`
								FROM `".$db_prefix."topics`
								WHERE `topic_id` = '".$_GET['tid']."'
								LIMIT 1");
			$result = $db->fetch_array($query);
			confirm_msg($lang['Delete_Topic'], sprintf($lang['Delete_Topic_Confirm_Msg'], $result['topic_title']), "mod.php?func=delete&tid=".$_GET['tid']."&confirm=1", "view_topic.php?tid=".$_GET['tid']."");
		}
		else
		{
			// Delete Topid Data
			$db->query("DELETE FROM `".$db_prefix."topics` WHERE `topic_id` = '".$_GET['tid']."'");
            // Delete Post Data
			$db->query("DELETE FROM `".$db_prefix."posts` WHERE `post_topic_id` = '".$_GET['tid']."'");
            // Delete Poll Data
			$db->query("DELETE FROM `".$db_prefix."pollvotes` WHERE `poll_topic_id` = '".$_GET['tid']."'");
            // Delete Topic Subscription Data
			$db->query("DELETE FROM `".$db_prefix."topic_subscriptions` WHERE `topic_subscription_topic_id` = '".$_GET['tid']."'");

			$sql = $db->query("SELECT p.`post_id`
								FROM (`".$db_prefix."topics` t
								LEFT JOIN `".$db_prefix."posts` p ON p.`post_id` = t.`topic_last_post`)
								WHERE `topic_forum_id` = '$fid' ORDER BY `topic_time` DESC LIMIT 1");
			if($result = $db->fetch_array($sql))
			{
				$db->query("UPDATE `".$db_prefix."forums` SET `forum_last_post` = '".$result['post_id']."' WHERE `forum_id` = '$fid'");
			}
			else
			{
				$db->query("UPDATE `".$db_prefix."forums` SET `forum_last_post` = '0' WHERE `forum_id` = '$fid'");
			}
			info_box($lang['Delete_Topic'], $lang['Topic_Deleted_Msg'], "view_forum.php?fid=$fid");
		}
	}
	else if(isset($_GET['pid']))
	{
		$ug_auth_sql = $db->query("SELECT t.`topic_id`, f.`forum_id`, f.`forum_mod`, g.`ug_mod` FROM (((`".$db_prefix."posts` p LEFT JOIN `".$db_prefix."topics` t ON t.`topic_id` = p.`post_topic_id`) LEFT JOIN `".$db_prefix."forums` f ON f.`forum_id` = t.`topic_forum_id`) LEFT JOIN `".$db_prefix."ug_auth` g ON g.`usergroup` = '".$user['user_usergroup']."' AND g.`ug_forum_id` = f.`forum_id`)  WHERE p.`post_id` = '".$_GET['pid']."'");
		if($ug_auth = $db->fetch_array($ug_auth_sql))
		{
			if(!(($ug_auth['forum_mod'] <= $user['user_level'] && $ug_auth['ug_mod'] == 0) || $ug_auth['ug_mod'] == 1))
			{
				if($user['user_id'] > 0)
				{
					error_msg($lang['Error'], $lang['Invalid_Permissions_Mod']);
				}
				else
				{
					header("Location: login.php");
					exit;
				}
			}
			$tid = $ug_auth['topic_id'];
			$fid = $ug_auth['forum_id'];
		}
		else
		{
			error_msg($lang['Error'], $lang['Invalid_Post_Id']);
		}

		$sql = $db->query("SELECT count(*) FROM `".$db_prefix."posts` WHERE `post_topic_id` = '$tid'");
		if($result = $db->fetch_row($sql))
		{
			if($result[0] == 1)
			{
				error_msg($lang['Error'], sprintf($lang['Delete_Last_Post_In_Topic_Msg'], "<a href=\"view_topic.php?tid=$tid\">", "</a>"));
			}
		}
		if(!isset($_GET['confirm']) || $_GET['confirm'] != "1")
		{
			$query = $db->query("SELECT t.`topic_id`
								FROM (`".$db_prefix."posts` p
								LEFT JOIN `".$db_prefix."topics` t ON t.`topic_id` = p.`post_topic_id`)
								WHERE p.`post_id` = '".$_GET['pid']."'");
			$topic = $db->fetch_array($query);
			confirm_msg($lang['Delete_Post'], $lang['Delete_Post_Confirm_Msg'], "mod.php?func=delete&pid=".$_GET['pid']."&confirm=1", "view_topic.php?tid=".$topic['topic_id']."");
		}
		else
		{			$db->query("DELETE FROM `".$db_prefix."posts` WHERE `post_id` = '".$_GET['pid']."'");

			$sql = $db->query("SELECT `post_id`, `post_timestamp`
			FROM `".$db_prefix."posts`
			WHERE `post_topic_id` = '$tid' ORDER BY `post_timestamp` DESC LIMIT 1");

			if($result = $db->fetch_array($sql))
			{
				$db->query("UPDATE `".$db_prefix."topics` SET `topic_last_post` = '".$result['post_id']."', `topic_time` = '".$result['post_timestamp']."', `topic_replies` = (`topic_replies` - 1) WHERE `topic_id` = '$tid'");
			}
			else
			{
				error_msg($lang['Error'], $lang['Select_last_post_in_topic_error']);
			}
			$sql = $db->query("SELECT p.`post_id`
								FROM (`".$db_prefix."topics` t
								LEFT JOIN `".$db_prefix."posts` p ON p.`post_id` = t.`topic_last_post`)
								ORDER BY t.`topic_time` DESC LIMIT 1");
			if($result = $db->fetch_array($sql))
			{
				$db->query("UPDATE `".$db_prefix."forums` SET `forum_last_post` = '".$result['post_id']."' WHERE `forum_id` = '$fid'");
			}
			else
			{
				error_msg($lang['Error'], $lang['Select_last_post_in_forum_error']);
			}

			info_box($lang['Delete_Post'], $lang['Post_Deleted_Msg'], "view_topic.php?tid=$tid");
		}
	}
} else if($_GET['func'] == "move")
{
	if(!isset($_GET['tid']) || !preg_match("/^[0-9]+$/", $_GET['tid'])) error_msg("Critical Error", "Invalid topic ID specified");

	if(!isset($_POST['Submit']))
	{
		$theme->new_file("move_topic", "move_topic.tpl");

		$sql = $db->query("SELECT t.`topic_id`, t.`topic_title`, f.`forum_id`, f.`forum_name`
							FROM ((`".$db_prefix."topics` t
							LEFT JOIN `".$db_prefix."forums` f ON f.`forum_id` = t.`topic_forum_id`)
							LEFT JOIN `".$db_prefix."posts` p ON p.`post_id` = t.`topic_first_post`)
							WHERE t.`topic_id` = '".$_GET['tid']."'");

		if($result = $db->fetch_array($sql))
		{
			$theme->replace_tags("move_topic", array(
				"FORUM_ID" => $result['forum_id'],
				"FORUM_NAME" => $result['forum_name'],
				"TOPIC_ID" => $result['topic_id'],
				"TOPIC_NAME" => $result['topic_title'],
				"MOVE_TOPIC" => sprintf($lang['Move_Topic_Name'], $result['topic_title'])
			));
			$sql = $db->query("SELECT `forum_id`, `forum_name` FROM `".$db_prefix."forums`");
			while($result = $db->fetch_array($sql))
			{
				$theme->insert_nest("move_topic", "forumrow", array(
					"FID" => $result['forum_id'],
					"FNAME" => $result['forum_name']
				));
				$theme->add_nest("move_topic", "forumrow");
			}

			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("move_topic");

			//
			// Output the page footer
			//
			include($root_path . "includes/page_footer.php");
		}
		else
		{
			error_msg($lang['Error'], $lang['Invalid_Topic_Id']);
		}
	}
	else
	{
		if(!isset($_POST['fid']) || !preg_match("/^[0-9]+$/", $_POST['fid'])) error_msg($lang['Error'], $lang['Invalid_Forum_Id']);

		$sql = $db->query("SELECT t.`topic_id`, f.`forum_id`, f.`forum_name`
							FROM (`".$db_prefix."topics` t
							LEFT JOIN `".$db_prefix."forums` f ON f.`forum_id` = '".$_POST['fid']."')
							WHERE t.`topic_id` = '".$_GET['tid']."'");
		if($result = $db->fetch_array($sql))
		{			if(!$result['forum_id']) error_msg($lang['Error'], $lang['Invalid_Forum_Id']);
			$db->query("UPDATE `".$db_prefix."topics` SET `topic_forum_id` = '".$result['forum_id']."' WHERE `topic_id` = '".$result['topic_id']."'");
			info_box("Move Topic", "Topic successfully moved to '".$result['forum_name']."'", "view_topic.php?tid=".$result['topic_id']."");#
		}
		else
		{			error_msg($lang['Error'], $lang['Invalid_Topic_Id']);
		}
	}

}
else if($_GET['func'] == "lock")
{	if(!isset($_GET['tid']) || !preg_match("/^[0-9]+$/", $_GET['tid'])) error_msg("Critical Error", "Invalid topic ID specified");

	$ug_auth_sql = $db->query("SELECT t.`topic_id`, t.`topic_status`, f.`forum_mod`, g.`ug_mod`
	 							FROM ((`".$db_prefix."topics` t
								LEFT JOIN `".$db_prefix."forums` f ON f.`forum_id` = t.`topic_forum_id`)
								LEFT JOIN `".$db_prefix."ug_auth` g ON g.`usergroup` = '".$user['user_usergroup']."' AND g.`ug_forum_id` = f.`forum_id`)
								WHERE t.`topic_id` = '".$_GET['tid']."'");

	if($ug_auth = $db->fetch_array($ug_auth_sql))
	{		if(!(($ug_auth['forum_mod'] <= $user['user_level'] && $ug_auth['ug_mod'] == 0) || $ug_auth['ug_mod'] == 1))
		{			if($user['user_id'] > 0)
			{				error_msg($lang['Error'], $lang['Invalid_Permission_Mod']);
			}
			else
			{				header("Location: login.php");
				exit();
			}
		}
		$tid = $ug_auth['topic_id'];
	}

	if(empty($tid)) error_msg("Critical Error", "Invalid topic ID specified");
	$db->query("UPDATE `".$db_prefix."topics` SET `topic_status` = '1' WHERE `topic_id` = '$tid'");#

	info_box($lang['Lock_Topic'], $lang['Topic_Locked_Msg'], "view_topic.php?tid=$tid");

}
else if($_GET['func'] == "unlock")
{	if(!isset($_GET['tid']) || !preg_match("/^[0-9]+$/", $_GET['tid'])) error_msg("Critical Error", "Invalid topic ID specified");

	$ug_auth_sql = $db->query("SELECT t.`topic_id`, t.`topic_status`, f.`forum_mod`, g.`ug_mod`
								FROM ((`".$db_prefix."topics` t
								LEFT JOIN `".$db_prefix."forums` f ON f.`forum_id` = t.`topic_forum_id`)
								LEFT JOIN `".$db_prefix."ug_auth` g ON g.`usergroup` = '".$user['user_usergroup']."' AND g.`ug_forum_id` = f.`forum_id`)
								WHERE t.`topic_id` = '".$_GET['tid']."'");

	if($ug_auth = $db->fetch_array($ug_auth_sql))
	{		if(!(($ug_auth['forum_mod'] <= $user['user_level'] && $ug_auth['ug_mod'] == 0) || $ug_auth['ug_mod'] == 1))
		{			if($user['user_id'] > 0)
			{				error_msg($lang['Error'], $lang['Invalid_Permission_Mod']);
			}
			else
			{				header("Location: login.php");
				exit();
			}
		}
		$tid = $ug_auth['topic_id'];
	}

	if(empty($tid)) error_msg("Critical Error", "Invalid topic ID specified");

	$db->query("UPDATE `".$db_prefix."topics` SET `topic_status` = '0' WHERE `topic_id` = '$tid'");

	info_box($lang['Unlock_Topic'], $lang['Topic_Unlocked_Msg'], "view_topic.php?tid=$tid");

}
else if($_GET['func'] == "topic_type")
{
	if(!isset($_GET['tid']) || !preg_match("/^[0-9]+$/", $_GET['tid'])) error_msg($lang['Error'], $lang['Invalid_Topic_Id']);
	if(!isset($_GET['type']) || !preg_match("/^[0-9]+$/", $_GET['type'])) error_msg($lang['Error'], $lang['Invalid_Topic_Type']);

	$ug_auth_sql = $db->query("SELECT t.`topic_id`, t.`topic_status`, f.`forum_mod`, g.`ug_mod`
								FROM ((`".$db_prefix."topics` t
								LEFT JOIN `".$db_prefix."forums` f ON f.`forum_id` = t.`topic_forum_id`)
								LEFT JOIN `".$db_prefix."ug_auth` g ON g.`usergroup` = '".$user['user_usergroup']."' AND g.`ug_forum_id` = f.`forum_id`)
								WHERE t.`topic_id` = '".$_GET['tid']."'");

	if($ug_auth = $db->fetch_array($ug_auth_sql))
	{
		if(!(($ug_auth['forum_mod'] <= $user['user_level'] && $ug_auth['ug_mod'] == 0) || $ug_auth['ug_mod'] == 1))
		{
			if($user['user_id'] > 0)
			{
				error_msg($lang['Error'], $lang['Invalid_Permission_Mod']);
			}
			else
			{
				header("Location: login.php");
				exit();
			}
		}
		$tid = $ug_auth['topic_id'];
	}

	if(empty($tid)) error_msg($lang['Error'], $lang['Invalid_Topic_Id']);

	$db->query("UPDATE `".$db_prefix."topics` SET `topic_type` = '".$_GET['type']."' WHERE `topic_id` = '$tid'");

	info_box($lang['Change_Topic_Type'], $lang['Topic_Type_Changed_Msg'], "view_topic.php?tid=$tid");

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
