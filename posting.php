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
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

define("IN_IBB", 1);

$root_path = "./";
include($root_path . "includes/common.php");

$language->add_file("posting");


if(!isset($_GET['func'])) $_GET['func'] = "";
if($_GET['func'] == "newtopic")
{

	if(!isset($_GET['fid'])) error_msg($lang['Error'], $lang['Invalid_Forum_Id']);
	$forum_sql = $db->query("SELECT f.`forum_name`, f.`forum_reply`, f.`forum_post`, g.`ug_post` FROM (`".$db_prefix."forums` f LEFT JOIN `".$db_prefix."ug_auth` g ON g.`ug_forum_id` = f.`forum_id` AND g.`usergroup` = '".$user['user_usergroup']."') WHERE `forum_id` = '".$_GET['fid']."'");
	if($forum_result = $db->fetch_array($forum_sql))
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
			$theme->new_file("newtopic", "post.tpl", "");

			if(!isset($_GET['poll_choices'])) $_GET['poll_choices'] = 5;
			$theme->replace_tags("newtopic", array(
				"FORUM_ID" => $_GET['fid'],
				"FORUM_NAME" => $forum_result['forum_name'],
				"ACTION" => $lang['New_Topic'],
				"BODY" => stripcslashes($_POST['body']),
				"HTML_ENABLED_MSG" => ($config['html_enabled'] == true) ? sprintf($lang['HTML_is_x'], $lang['enabled']) : sprintf($lang['HTML_is_x'], $lang['disabled']),
				"BBCODE_ENABLED_MSG" => ($config['bbcode_enabled'] == true) ? sprintf($lang['BBCode_is_x'], $lang['enabled']) : sprintf($lang['BBCode_is_x'], $lang['disabled']),
				"SMILIES_ENABLED_MSG" => ($config['smilies_enabled'] == true) ? sprintf($lang['Smilies_are_x'], $lang['enabled']) : sprintf($lang['Smilies_are_x'], $lang['disabled'])
			));

			$page_title = $config['site_name'] . " &raquo; " . $forum_result['forum_name'] . " &raquo; " . $lang['New_Topic'];

			$theme->insert_nest("newtopic", "title", array(
				"TITLE" => $_POST['title']
			));
			$theme->add_nest("newtopic", "title");

    		$theme->switch_nest("newtopic", "navbar", true);
    		$theme->add_nest("newtopic", "navbar");

			$theme->insert_nest("newtopic", "error", array(
				"ERRORS" => $error
			));
			$theme->add_nest("newtopic", "error");

			$forum_sql = $db->query("SELECT f.`forum_name`, f.`forum_poll`, g.`ug_poll` FROM (`".$db_prefix."forums` f LEFT JOIN `".$db_prefix."ug_auth` g ON g.`ug_forum_id` = f.`forum_id` AND g.`usergroup` = '".$user['user_usergroup']."') WHERE `forum_id` = '".$_GET['fid']."'");
			if($forum_result = $db->fetch_array($forum_sql))
			{
				if((($forum_result['forum_poll'] <= $user['user_level'] && $forum_result['ug_poll'] == 0) || $forum_result['ug_poll'] == 1))
				{
					$theme->insert_nest("newtopic", "poll", array(
						"POLL_TITLE" => $_POST['poll_title'],
						"POLL_ADD_CHOICE_URL" => "posting.php?func=newtopic&fid=" . $_GET['fid'] . "&poll_choices=" . ($_GET['poll_choices'] + 1 ) . "",
					));

	     	   		for($i=1; $i<=$_GET['poll_choices']; $i++)
	     	   		{
	        			$theme->insert_nest("newtopic", "poll/pollchoice_row", array(
	     	   				"POLL_CHOICE_DESC" => sprintf($lang['Poll_Choice_X'], intval($i)),
	        				"POLL_CHOICE_NUMBER" => strval($i),
	        				"POLL_CHOICE_VALUE" => (isset($_POST['pollchoice'][$i])) ? $_POST['pollchoice'][$i] : ""
	        			));
	        			$theme->add_nest("newtopic", "poll/pollchoice_row");
	        		}
	        		$theme->add_nest("newtopic", "poll");
				}
			}

			if($config['html_enabled'] == true) {
				$theme->insert_nest("newtopic", "disable_html");
				$theme->add_nest("newtopic", "disable_html");
			}
			if($config['bbcode_enabled'] == true) {
				$theme->insert_nest("newtopic", "disable_bbcode");
				$theme->add_nest("newtopic", "disable_bbcode");

				// Add the BBCode chooser to the page
				$theme->insert_nest("newtopic", "bbcode");
				$theme->add_nest("newtopic", "bbcode");
			}
			if($config['smilies_enabled'] == true) {
				$theme->insert_nest("newtopic", "disable_smilies");
				$theme->add_nest("newtopic", "disable_smilies");

				// Add the emoticon chooser to the page
				$theme->insert_nest("newtopic", "smilies");
 	 	      	$emotion_query = $db->query("SELECT `smilie_code`, `smilie_url`, `smilie_name` FROM `".$db_prefix."smilies`");
 	 	      	$smilie_no = 1;
				$smilie_count = 1;
				$smilie_url = array();
				while($emotion = $db->fetch_array($emotion_query))
				{
					// Check if the smilie has already been displayed
        			if(!in_array($emotion['smilie_url'], $smilie_url))
       	 			{
        			// Add smilie to the array
        				$smilie_url[] = $emotion['smilie_url'];

        				if($smilie_no == 1)
        				{
        					$theme->insert_nest("newtopic", "smilies/emoticon_row");
        				}

        				$theme->insert_nest("newtopic", "smilies/emoticon_row/emoticon_cell", array(
        					"EMOTICON_CODE" => $emotion['smilie_code'],
        					"EMOTICON_URL" => $root_path . $config['smilies_url'] . "/" . $emotion['smilie_url'],
        					"EMOTICON_TITLE" => $emotion['smilie_name']
        				));
        				$theme->add_nest("newtopic", "smilies/emoticon_row/emoticon_cell");
        				if($smilie_no >= 5)
        				{
        					$theme->add_nest("newtopic", "smilies/emoticon_row");
        					$smilie_no = 1;
        				}
        				else
        				{
        					$smilie_no++;
        				}
        				$smilie_count++;
        				if($smilie_count > 20)
        				{
        					break;
        				}
        			}
       			}
       			$theme->add_nest("newtopic", "smilies");
 			}


			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("newtopic");

			//
			// Output the page footer
			//
			include($inclues_path . "/page_footer.php");
		}
		else
		{			// Disable checkboxes && attach signature
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
				$forum_sql = $db->query("SELECT f.`forum_name`, f.`forum_poll`, g.`ug_poll` FROM (`".$db_prefix."forums` f LEFT JOIN `".$db_prefix."ug_auth` g ON g.`ug_forum_id` = f.`forum_id` AND g.`usergroup` = '".$user['user_usergroup']."') WHERE `forum_id` = '".$_GET['fid']."'");
				if(!$forum_result = $db->fetch_array($forum_sql))
				{					$_POST['poll_title'] == "";
					$auth_poll = false;
				}
			}
			// Insert topic info
			$db->query("INSERT INTO `".$db_prefix."topics`
						(`topic_forum_id`, `topic_title`, `topic_poll_title`, `topic_user_id`, `topic_time`)
						VALUES ('".$_GET['fid']."', '".$_POST['title']."', '".$_POST['poll_title']."', '".$user['user_id']."', '".time()."')");

			$tid = $db->insert_id();
			// Insert post info
			$db->query("INSERT INTO `".$db_prefix."posts`
						(`post_topic_id`, `post_user_id`, `post_text`, `post_timestamp`, `post_disable_html`, `post_disable_bbcode`, `post_disable_smilies`, `post_attach_signature`)
						VALUES ('$tid', '".$_SESSION['user_id']."', '".$_POST['body']."', '".time()."', '".$_POST['disable_html']."', '".$_POST['disable_bbcode']."', '".$_POST['disable_smilies']."', '".$_POST['attach_signature']."')");
			$pid = $db->insert_id();

   			if($auth_poll == true) {   				$poll_choice_id = 1;
				foreach($_POST['pollchoice'] as $poll_choice) {					if(!empty($poll_choice)) {						$db->query("INSERT INTO `".$db_prefix."pollvotes` (`poll_topic_id`, `poll_choice_id`, `poll_choice_name`) VALUES ('".$tid."', '".$poll_choice_id."', '".$poll_choice."')");
						$poll_choice_id++;
					}
				}
			}
			// Update forum info
			$sql = $db->query("SELECT * FROM `".$db_prefix."forums` WHERE `forum_id` = '".$_GET['fid']."' LIMIT 1");
			if($row = $db->fetch_array($sql))
			{
				$new_topics = $row['forum_topics'] + 1;
				$new_posts = $row['forum_posts'] + 1;
				$db->query("UPDATE `".$db_prefix."forums` SET `forum_topics` = '$new_topics',
						`forum_posts` = '$new_posts',
						`forum_last_post` = '$pid'
						WHERE `forum_id` = '".$_GET['fid']."'");
			}

			$db->query("UPDATE `".$db_prefix."topics` SET `topic_first_post` = '$pid', `topic_last_post` = '$pid'
						WHERE `topic_id` = '$tid'");

			if($user['user_id'] > 0)
			{
				$db->query("UPDATE `".$db_prefix."users` SET `user_posts` = '" . ($user['user_posts'] + 1) . "' WHERE `user_id` = '" . $user['user_id'] . "'");
				if(isset($_POST['subscribe_to_topic'])) {					$db->query("INSERT INTO `".$db_prefix."topic_subscriptions` (`topic_subscription_user_id`, `topic_subscription_topic_id`) VALUES ('".$user['user_id']."', '".$tid."')");
				}
			}
			info_box($lang['New_Topic'], $lang['New_Post_Msg'], "view_topic.php?tid=$tid");
		}

	}
	else
	{
		$theme->new_file("newtopic", "post.tpl", "");
		$theme->replace_tags("newtopic", array(
			"FORUM_ID" => $_GET['fid'],
			"FORUM_NAME" => $forum_result['forum_name'],
			"ACTION" => $lang['New_Topic'],
			"BODY" => (isset($_POST['body'])) ? $_POST['body'] : "",
			"HTML_ENABLED_MSG" => ($config['html_enabled'] == true) ? sprintf($lang['HTML_is_x'], $lang['enabled']) : sprintf($lang['HTML_is_x'], $lang['disabled']),
			"BBCODE_ENABLED_MSG" => ($config['bbcode_enabled'] == true) ? sprintf($lang['BBCode_is_x'], $lang['enabled']) : sprintf($lang['BBCode_is_x'], $lang['disabled']),
			"SMILIES_ENABLED_MSG" => ($config['smilies_enabled'] == true) ? sprintf($lang['Smilies_are_x'], $lang['enabled']) : sprintf($lang['Smilies_are_x'], $lang['disabled'])
		));

		$page_title = $config['site_name'] . " &raquo; " . $forum_result['forum_name'] . " &raquo; " . $lang['New_Topic'];

		$theme->insert_nest("newtopic", "title", array(
			"TITLE" => (isset($_POST['title'])) ? $_POST['title'] : ""
		));
    	$theme->add_nest("newtopic", "title");

    	$theme->switch_nest("newtopic", "navbar", true);
    	$theme->add_nest("newtopic", "navbar");

		if($config['html_enabled'] == true) {			$theme->insert_nest("newtopic", "disable_html");
			$theme->add_nest("newtopic", "disable_html");
		}
		if($config['bbcode_enabled'] == true) {
			$theme->insert_nest("newtopic", "disable_bbcode");
			$theme->add_nest("newtopic", "disable_bbcode");

			// Add the BBCode chooser to the page
			$theme->insert_nest("newtopic", "bbcode");
			$theme->add_nest("newtopic", "bbcode");
		}
		if($config['smilies_enabled'] == true) {
			$theme->insert_nest("newtopic", "disable_smilies");
			$theme->add_nest("newtopic", "disable_smilies");

			// Add the emoticon chooser to the page
			$theme->insert_nest("newtopic", "smilies");
        	$emotion_query = $db->query("SELECT `smilie_code`, `smilie_url`, `smilie_name` FROM `".$db_prefix."smilies`");
        	$smilie_no = 1;
        	$smilie_count = 1;
        	$smilie_url = array();
        	while($emotion = $db->fetch_array($emotion_query))
        	{       		 	// Check if the smilie has already been displayed
        		if(!in_array($emotion['smilie_url'], $smilie_url))
       	 		{        			// Add smilie to the array        			$smilie_url[] = $emotion['smilie_url'];
        			if($smilie_no == 1)
        			{
        				$theme->insert_nest("newtopic", "smilies/emoticon_row");
        			}

        			$theme->insert_nest("newtopic", "smilies/emoticon_row/emoticon_cell", array(
        				"EMOTICON_CODE" => $emotion['smilie_code'],
        				"EMOTICON_URL" => $root_path . $config['smilies_url'] . "/" . $emotion['smilie_url'],
        				"EMOTICON_TITLE" => $emotion['smilie_name']
        			));
        			$theme->add_nest("newtopic", "smilies/emoticon_row/emoticon_cell");
        			if($smilie_no >= 5)
        			{        				$theme->add_nest("newtopic", "smilies/emoticon_row");
        				$smilie_no = 1;
        			}
        			else
        			{        				$smilie_no++;
        			}
        			$smilie_count++;
        			if($smilie_count > 20)
        			{        				break;
        			}
        		}
       		}
       		$theme->add_nest("newtopic", "smilies");
 		}
		$forum_sql = $db->query("SELECT f.`forum_name`, f.`forum_poll`, g.`ug_poll` FROM (`".$db_prefix."forums` f LEFT JOIN `".$db_prefix."ug_auth` g ON g.`ug_forum_id` = f.`forum_id` AND g.`usergroup` = '".$user['user_usergroup']."') WHERE `forum_id` = '".$_GET['fid']."'");
		if($forum_result = $db->fetch_array($forum_sql))
		{
			if((($forum_result['forum_poll'] <= $user['user_level'] && $forum_result['ug_poll'] == 0) || $forum_result['ug_poll'] == 1))
			{				if(!isset($_GET['poll_choices'])) $_GET['poll_choices'] = 5;
				$theme->insert_nest("newtopic", "poll", array(
					"POLL_TITLE" => (isset($_POST['poll_title'])) ? $_POST['poll_title'] : "",
					"POLL_ADD_CHOICE_URL" => "posting.php?func=newtopic&fid=" . $_GET['fid'] . "&poll_choices=" . ($_GET['poll_choices'] + 1 ) . ""
				));
	        	for($i=1; $i<=$_GET['poll_choices']; $i++)
	        	{
	        		$theme->insert_nest("newtopic", "poll/pollchoice_row", array(
	        			"POLL_CHOICE_DESC" => sprintf($lang['Poll_Choice_X'], intval($i)),
	        			"POLL_CHOICE_NUMBER" => strval($i),
	        			"POLL_CHOICE_VALUE" => (isset($_POST['pollchoice'][$i])) ? $_POST['pollchoice'][$i] : ""
	        		));
	        		$theme->add_nest("newtopic", "poll/pollchoice_row");
	        	}
	        	$theme->add_nest("newtopic", "poll");
			}
		}

		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("newtopic");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");
	}
}
else if($_GET['func'] == "reply")
{
	if(!isset($_GET['tid'])) error_msg($lang['Error'], $lang['Invalid_Topic_Id']);

	$forum_sql = $db->query("SELECT f.`forum_id`, f.`forum_name`, f.`forum_reply`, f.`forum_mod`, t.`topic_id`, t.`topic_title`, t.`topic_status`, g.`ug_reply`, g.`ug_mod`
				   FROM (((`".$db_prefix."forums` f
				   LEFT JOIN `".$db_prefix."topics` t ON t.`topic_id` = '".$_GET['tid']."')
				   LEFT JOIN `".$db_prefix."posts` p ON p.`post_id` = t.`topic_first_post`)
				   LEFT JOIN `".$db_prefix."ug_auth` g ON g.`ug_forum_id` = f.`forum_id` AND g.`usergroup` = '".$user['user_usergroup']."')
				   WHERE f.`forum_id` = t.`topic_forum_id`");
	if($forum_result = $db->fetch_array($forum_sql))
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
		if(strlen($error) > 0)
		{
		$theme->new_file("reply", "post.tpl", "");

		//
		// Set Up Quote
		//
		if(isset($_GET['quote']) && is_numeric($_GET['quote']))
		{

			$quote_query = $db->query("SELECT p.`post_text`, u.`username`
								FROM `".$db_prefix."posts` p, `".$db_prefix."users` u
								WHERE p.`post_id` = '".$_GET['quote']."' AND u.`user_id` = p.`post_user_id`
								LIMIT 1");

			if($quote = $db->fetch_array($quote_query))
			{
				$body = "[quote=".$quote['username']."]".$quote['post_text']."[/quote]\n\n";
			}
			else
			{
				$body = "";
			}
		}
		else
		{
			$body = "";
		}

		$theme->replace_tags("reply", array(
			"FORUM_ID" => $forum_result['forum_id'],
			"FORUM_NAME" => $forum_result['forum_name'],
			"TOPIC_ID" => $_GET['tid'],
			"TOPIC_NAME" => $forum_result['topic_title'],
			"ACTION" => $lang['Reply'],
			"BODY" => $body,
			"HTML_ENABLED_MSG" => ($config['html_enabled'] == true) ? sprintf($lang['HTML_is_x'], $lang['enabled']) : sprintf($lang['HTML_is_x'], $lang['disabled']),
			"BBCODE_ENABLED_MSG" => ($config['bbcode_enabled'] == true) ? sprintf($lang['BBCode_is_x'], $lang['enabled']) : sprintf($lang['BBCode_is_x'], $lang['disabled']),
			"SMILIES_ENABLED_MSG" => ($config['smilies_enabled'] == true) ? sprintf($lang['Smilies_are_x'], $lang['enabled']) : sprintf($lang['Smilies_are_x'], $lang['disabled'])
		));

			$page_title = $config['site_name'] . " &raquo; " . $forum_result['forum_name'] . " &raquo; " . $forum_result['topic_title'] . " &raquo; " . $lang['Reply'];

			$theme->switch_nest("reply", "navbar", false);
			$theme->add_nest("reply", "navbar");

			$theme->insert_nest("reply", "error", array(
				"ERRORS" => $error
			));
			$theme->add_nest("reply", "error");

			if($config['html_enabled'] == true)
			{
				$theme->insert_nest("reply", "disable_html");
				$theme->add_nest("reply", "disable_html");
			}
			if($config['bbcode_enabled'] == true)
			{
				$theme->insert_nest("reply", "disable_bbcode");
				$theme->add_nest("reply", "disable_bbcode");

				// Add the BBCode chooser to the page
				$theme->insert_nest("reply", "bbcode");
				$theme->add_nest("reply", "bbcode");
			}
			if($config['smilies_enabled'] == true)
			{
				$theme->insert_nest("reply", "disable_smilies");
				$theme->add_nest("reply", "disable_smilies");

				// Add the emoticon chooser to the page
				$theme->insert_nest("reply", "smilies");
        		$emotion_query = $db->query("SELECT `smilie_code`, `smilie_url`, `smilie_name` FROM `".$db_prefix."smilies`");
        		$smilie_no = 1;
        		$smilie_count = 1;
        		$smilie_url = array();
        		while($emotion = $db->fetch_array($emotion_query))
        		{
       		 		// Check if the smilie has already been displayed
        			if(!in_array($emotion['smilie_url'], $smilie_url))
       	 			{
        				// Add smilie to the array
        				$smilie_url[] = $emotion['smilie_url'];

        				if($smilie_no == 1)
        				{
        					$theme->insert_nest("reply", "smilies/emoticon_row");
        				}

        				$theme->insert_nest("reply", "smilies/emoticon_row/emoticon_cell", array(
        					"EMOTICON_CODE" => $emotion['smilie_code'],
        					"EMOTICON_URL" => $root_path . $config['smilies_url'] . "/" . $emotion['smilie_url'],
        					"EMOTICON_TITLE" => $emotion['smilie_name']
        				));
        				$theme->add_nest("reply", "smilies/emoticon_row/emoticon_cell");
        				if($smilie_no >= 5)
        				{
        					$theme->add_nest("reply", "smilies/emoticon_row");
        					$smilie_no = 1;
        				}
        				else
        				{
        					$smilie_no++;
        				}
        				$smilie_count++;
        				if($smilie_count > 20)
        				{
        					break;
        				}
        			}
       			}
       			$theme->add_nest("reply", "smilies");
 			}
			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("reply");

			//
			// Output the page footer
			//
			include($root_path . "includes/page_footer.php");
		}
		else
		{
			 if(!isset($_POST['title'])) $_POST['title'] = "";
			 // Insert topic info
			 $topic_sql = $db->query("SELECT * FROM `".$db_prefix."topics` WHERE `topic_id` = '".$_GET['tid']."' LIMIT 1");
			 $topic_info = $db->fetch_array($topic_sql);
			 $new_replies = $topic_info['topic_replies'] + 1;

			 if($user['user_id'] > 0)
			 {
				  $set_new_posts = false;
				  $track_topics = (isset($_COOKIE['read_topics'])) ? unserialize($_COOKIE['read_topics']) : "";
				  if(!empty($track_topics[$_GET['tid']]))
				  {
					   $set_new_posts = true;
				  } else if(count($track_topics) <  200)
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
			 $db->query("INSERT INTO `".$db_prefix."posts` (`post_topic_id`, `post_user_id`, `post_text`, `post_timestamp`)
						VALUES('".$_GET['tid']."', '".$user['user_id']."', '".$_POST['body']."', '".time()."')");

			 $post_id = $db->insert_id();

			 $db->query("UPDATE `".$db_prefix."topics` SET `topic_replies` = '$new_replies',
					`topic_time` = '".time()."',
					`topic_last_post` = '$post_id'
					WHERE `topic_id` = '".$_GET['tid']."'");

			 // Update forum info
			 $sql = $db->query("SELECT * FROM `".$db_prefix."forums` WHERE `forum_id` = '".$topic_info['topic_forum_id']."' LIMIT 1");
			 if($row = $db->fetch_array($sql))
			 {
				  $new_posts = $row['forum_posts'] + 1;
				  $db->query("UPDATE `".$db_prefix."forums` SET
						`forum_posts` = '$new_posts',
						`forum_last_post` = '$post_id'
						WHERE `forum_id` = '".$topic_info['topic_forum_id']."'");
			 }

			  if($user['user_id'] > 0)
			  {
				   $db->query("UPDATE `".$db_prefix."users` SET `user_posts` = '" . ($user['user_posts'] + 1) . "' WHERE `user_id` = '" . $user['user_id'] . "'");
			  }
			  info_box($lang['Reply'], $lang['New_Post_Msg'], "view_topic.php?tid=".$_GET['tid']."");
		}
	}
	else
	{
		$theme->new_file("reply", "post.tpl", "");
		$theme->replace_tags("reply", array(
			"FORUM_ID" => $forum_result['forum_id'],
			"FORUM_NAME" => $forum_result['forum_name'],
			"TOPIC_ID" => $_GET['tid'],
			"TOPIC_NAME" => $forum_result['topic_title'],
			"ACTION" => $lang['Reply'],
			"BODY" => "",
			"HTML_ENABLED_MSG" => ($config['html_enabled'] == true) ? sprintf($lang['HTML_is_x'], $lang['enabled']) : sprintf($lang['HTML_is_x'], $lang['disabled']),
			"BBCODE_ENABLED_MSG" => ($config['bbcode_enabled'] == true) ? sprintf($lang['BBCode_is_x'], $lang['enabled']) : sprintf($lang['BBCode_is_x'], $lang['disabled']),
			"SMILIES_ENABLED_MSG" => ($config['smilies_enabled'] == true) ? sprintf($lang['Smilies_are_x'], $lang['enabled']) : sprintf($lang['Smilies_are_x'], $lang['disabled'])
		));

		$page_title = $config['site_name'] . " &raquo; " . $forum_result['forum_name'] . " &raquo; " . $forum_result['topic_title'] . " &raquo; " . $lang['Reply'];

		$theme->switch_nest("reply", "navbar", false);
		$theme->add_nest("reply", "navbar");

		if($config['html_enabled'] == true) {
			$theme->insert_nest("reply", "disable_html");
			$theme->add_nest("reply", "disable_html");
		}
		if($config['bbcode_enabled'] == true) {
			$theme->insert_nest("reply", "disable_bbcode");
			$theme->add_nest("reply", "disable_bbcode");

			// Add the BBCode chooser to the page
			$theme->insert_nest("reply", "bbcode");
			$theme->add_nest("reply", "bbcode");
		}
		if($config['smilies_enabled'] == true) {
			$theme->insert_nest("reply", "disable_smilies");
			$theme->add_nest("reply", "disable_smilies");

			// Add the emoticon chooser to the page
			$theme->insert_nest("reply", "smilies");
       		$emotion_query = $db->query("SELECT `smilie_code`, `smilie_url`, `smilie_name` FROM `".$db_prefix."smilies`");
       		$smilie_no = 1;
       		$smilie_count = 1;
       		$smilie_url = array();
       		while($emotion = $db->fetch_array($emotion_query))
       		{
      		 		// Check if the smilie has already been displayed
       			if(!in_array($emotion['smilie_url'], $smilie_url))
      	 			{
       				// Add smilie to the array
       				$smilie_url[] = $emotion['smilie_url'];

       				if($smilie_no == 1)
       				{
       					$theme->insert_nest("reply", "smilies/emoticon_row");
       				}

       				$theme->insert_nest("reply", "smilies/emoticon_row/emoticon_cell", array(
       					"EMOTICON_CODE" => $emotion['smilie_code'],
       					"EMOTICON_URL" => $root_path . $config['smilies_url'] . "/" . $emotion['smilie_url'],
       					"EMOTICON_TITLE" => $emotion['smilie_name']
       				));
       				$theme->add_nest("reply", "smilies/emoticon_row/emoticon_cell");
       				if($smilie_no >= 5)
       				{
       					$theme->add_nest("reply", "smilies/emoticon_row");
       					$smilie_no = 1;
       				}
       				else
       				{
       					$smilie_no++;
       				}
       				$smilie_count++;
       				if($smilie_count > 20)
       				{
       					break;
       				}
       			}
   			}
      		$theme->add_nest("reply", "smilies");
		}

		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("reply");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");
	}
}
else if($_GET['func'] == "edit")
{

	if(!isset($_GET['pid']) || !is_numeric($_GET['pid'])) error_msg($lang['Error'], $lang['Invalid_Post_Id']);
	$query = $db->query("SELECT p.`post_user_id`, p.`post_text`, t.`topic_id`, t.`topic_title`, f.`forum_id`, f.`forum_name`, f.`forum_mod`, g.`ug_mod`
							FROM (((`".$db_prefix."posts` p
							LEFT JOIN `".$db_prefix."topics` t ON t.`topic_id` = p.`post_topic_id`)
							LEFT JOIN `".$db_prefix."forums` f ON f.`forum_id` = t.`topic_forum_id`)
							LEFT JOIN `".$db_prefix."ug_auth` g ON g.`usergroup` = '".$user['user_usergroup']."' AND g.`ug_forum_id` = f.`forum_id`)
							WHERE p.`post_id` = '".$_GET['pid']."'");

	if($result = $db->fetch_array($query))
	{
		if(!((($result['forum_mod'] <= $user['user_level'] && $result['ug_mod'] == 0) || $result['ug_mod'] == 1) || ($result['post_user_id'] == $user['user_id'] && $user['user_id'] > 0)))
		{			if($user['user_id'] > 0) {
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
      		$error = "";
      		if(strlen($_POST['body']) < 1)
      		{
      			$error .= $lang['No_Post_Content'] . "<br />";
      		}
      		if(strlen($error) > 0)
      		{
      			$theme->new_file("edit", "post.tpl", "");
      			$theme->replace_tags("edit", array(
      				"ACTION" => $lang['Edit'],
      				"TOPIC_ID" => $result['topic_id'],
      				"TOPIC_NAME" => $result['topic_title'],
      				"FORUM_ID" => $result['forum_id'],
      				"FORUM_NAME" => $result['forum_name'],
      				"BODY" => $_POST['body'],
      				"HTML_ENABLED_MSG" => ($config['html_enabled'] == true) ? sprintf($lang['HTML_is_x'], $lang['enabled']) : sprintf($lang['HTML_is_x'], $lang['disabled']),
      				"BBCODE_ENABLED_MSG" => ($config['bbcode_enabled'] == true) ? sprintf($lang['BBCode_is_x'], $lang['enabled']) : sprintf($lang['BBCode_is_x'], $lang['disabled']),
      				"SMILIES_ENABLED_MSG" => ($config['smilies_enabled'] == true) ? sprintf($lang['Smilies_are_x'], $lang['enabled']) : sprintf($lang['Smilies_are_x'], $lang['disabled'])
      			));

      			$page_title = $config['site_name'] . " &raquo; " . $result['forum_name'] . " &raquo; " . $result['topic_title'] . " &raquo; " . $lang['Edit'];

          		$theme->switch_nest("edit", "navbar", false);
          		$theme->add_nest("edit", "navbar");

      			$theme->insert_nest("edit", "error", array(
      				"ERRORS" => $error
      			));
      			$theme->add_nest("edit", "error");

      			if($config['html_enabled'] == true) {
      				$theme->insert_nest("edit", "disable_html");
      				$theme->add_nest("edit", "disable_html");
      			}
      			if($config['bbcode_enabled'] == true) {
      				$theme->insert_nest("edit", "disable_bbcode");
      				$theme->add_nest("edit", "disable_bbcode");

      				// Add the BBCode chooser to the page
      				$theme->insert_nest("edit", "bbcode");
      				$theme->add_nest("edit", "bbcode");
      			}
      			if($config['smilies_enabled'] == true) {
      				$theme->insert_nest("edit", "disable_smilies");
      				$theme->add_nest("edit", "disable_smilies");

      				// Add the emoticon chooser to the page
      				$theme->insert_nest("edit", "smilies");
             			$emotion_query = $db->query("SELECT `smilie_code`, `smilie_url`, `smilie_name` FROM `".$db_prefix."smilies`");
             			$smilie_no = 1;
             			$smilie_count = 1;
             			$smilie_url = array();
             			while($emotion = $db->fetch_array($emotion_query))
             			{
            		 		// Check if the smilie has already been displayed
             				if(!in_array($emotion['smilie_url'], $smilie_url))
            	 				{
             					// Add smilie to the array
             					$smilie_url[] = $emotion['smilie_url'];

             					if($smilie_no == 1)
             					{
             						$theme->insert_nest("edit", "smilies/emoticon_row");
             					}

             					$theme->insert_nest("edit", "smilies/emoticon_row/emoticon_cell", array(
             						"EMOTICON_CODE" => $emotion['smilie_code'],
             						"EMOTICON_URL" => $root_path . $config['smilies_url'] . "/" . $emotion['smilie_url'],
             						"EMOTICON_TITLE" => $emotion['smilie_name']
             					));
             					$theme->add_nest("edit", "smilies/emoticon_row/emoticon_cell");
             					if($smilie_no >= 5)
             					{
             						$theme->add_nest("edit", "smilies/emoticon_row");
             						$smilie_no = 1;
             					}
             					else
             					{
             						$smilie_no++;
             					}
             					$smilie_count++;
             					if($smilie_count > 20)
             					{
             						break;
             					}
             				}
         				}
            			$theme->add_nest("edit", "smilies");
      			}
      			//
      			// Output the page header
      			//
      			include($root_path . "includes/page_header.php");

      			//
      			// Output the main page
      			//
      			$theme->output("edit");

      			//
      			// Output the page footer
      			//
      			include($root_path . "includes/page_footer.php");
      		}
      		else
      		{
      			if(!isset($_POST['title'])) $_POST['title'] = "";

      			// Insert post info
      			$db->query("UPDATE `".$db_prefix."posts` SET
      						`post_text` = '".$_POST['body']."'
      						WHERE `post_id` = '".$_GET['pid']."'");

      			$query = $db->query("SELECT `post_topic_id` FROM `".$db_prefix."posts` WHERE `post_id` = '".$_GET['pid']."'");
      			$result = $db->fetch_array($query);

      			$db->query("UPDATE `".$db_prefix."topics` SET `topic_time` = '".time()."'
      						WHERE `topic_id` = '".$result['post_topic_id']."'");

      			info_box($lang['Edit_Post'], $lang['Post_Edited_Msg'], "view_topic.php?tid=".$result['post_topic_id']."");
      		}
      	}
		else
    	{
   			$theme->new_file("edit", "post.tpl", "");
   			$theme->replace_tags("edit", array(
				"ACTION" => $lang['Edit'],
				"TOPIC_ID" => $result['topic_id'],
				"TOPIC_NAME" => $result['topic_title'],
				"FORUM_ID" => $result['forum_id'],
				"FORUM_NAME" => $result['forum_name'],
   				"BODY" => $result['post_text'],
   				"HTML_ENABLED_MSG" => ($config['html_enabled'] == true) ? sprintf($lang['HTML_is_x'], $lang['enabled']) : sprintf($lang['HTML_is_x'], $lang['disabled']),
   				"BBCODE_ENABLED_MSG" => ($config['bbcode_enabled'] == true) ? sprintf($lang['BBCode_is_x'], $lang['enabled']) : sprintf($lang['BBCode_is_x'], $lang['disabled']),
   				"SMILIES_ENABLED_MSG" => ($config['smilies_enabled'] == true) ? sprintf($lang['Smilies_are_x'], $lang['enabled']) : sprintf($lang['Smilies_are_x'], $lang['disabled'])
   			));
   			$page_title = $config['site_name'] . " &raquo; " . $result['forum_name'] . " &raquo; " . $result['topic_title'] . " &raquo; " . $lang['Edit'];

       		$theme->switch_nest("edit", "navbar", false);
       		$theme->add_nest("edit", "navbar");

   			if($config['html_enabled'] == true) {
   				$theme->insert_nest("edit", "disable_html");
   				$theme->add_nest("edit", "disable_html");
   			}
   			if($config['bbcode_enabled'] == true) {
   				$theme->insert_nest("edit", "disable_bbcode");
   				$theme->add_nest("edit", "disable_bbcode");

   				// Add the BBCode chooser to the page
   				$theme->insert_nest("edit", "bbcode");
   				$theme->add_nest("edit", "bbcode");
   			}
   			if($config['smilies_enabled'] == true) {
   				$theme->insert_nest("edit", "disable_smilies");
   				$theme->add_nest("edit", "disable_smilies");

   				// Add the emoticon chooser to the page
   				$theme->insert_nest("edit", "smilies");
          			$emotion_query = $db->query("SELECT `smilie_code`, `smilie_url`, `smilie_name` FROM `".$db_prefix."smilies`");
          			$smilie_no = 1;
          			$smilie_count = 1;
          			$smilie_url = array();
          			while($emotion = $db->fetch_array($emotion_query))
          			{
         		 		// Check if the smilie has already been displayed
          				if(!in_array($emotion['smilie_url'], $smilie_url))
         	 				{
          					// Add smilie to the array
          					$smilie_url[] = $emotion['smilie_url'];

          					if($smilie_no == 1)
          					{
          						$theme->insert_nest("edit", "smilies/emoticon_row");
          					}

          					$theme->insert_nest("edit", "smilies/emoticon_row/emoticon_cell", array(
          						"EMOTICON_CODE" => $emotion['smilie_code'],
          						"EMOTICON_URL" => $root_path . $config['smilies_url'] . "/" . $emotion['smilie_url'],
          						"EMOTICON_TITLE" => $emotion['smilie_name']
          					));
          					$theme->add_nest("edit", "smilies/emoticon_row/emoticon_cell");
          					if($smilie_no >= 5)
          					{
          						$theme->add_nest("edit", "smilies/emoticon_row");
          						$smilie_no = 1;
          					}
          					else
          					{
          						$smilie_no++;
          					}
          					$smilie_count++;
          					if($smilie_count > 20)
          					{
          						break;
          					}
          				}
      				}
         			$theme->add_nest("edit", "smilies");
   			}

   			//
   			// Output the page header
   			//
   			include($root_path . "includes/page_header.php");

   			//
   			// Output the main page
   			//
   			$theme->output("edit");

   			//
   			// Output the page footer
   			//
   			include($root_path . "includes/page_footer.php");
    	}
	}
	else
	{		error_msg($lang['Error'], $lang['Invalid_Post_Id']);
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
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
