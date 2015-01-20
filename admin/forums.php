<?php

/**********************************************************
*
*			admin/forums.php
*
*		ImperialBB 2.X.X - By Nate and James
*
*		     (C) The IBB Group
*
***********************************************************/

define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
include($root_path . "includes/common.php");
$language->add_file("admin/forums");

if(!isset($_GET['func'])) $_GET['func'] = "";
if($_GET['func'] == "add_forum") {
	if(isset($_POST['Submit'])) {
		$error = "";
		if(empty($_POST['name'])) {
			$error .= sprintf($lang['No_x_content'], strtolower($lang['Forum_Name'])) . "<br />";
		}
		if(!isset($_POST['cid']) || empty($_POST['cid'])) {
			$error .= sprintf($lang['No_x_content'], strtolower($lang['Category'])) . "<br />";
		}
		if(!empty($error)) {
			$theme->new_file("add_forum", "add_forum.tpl");
			$theme->replace_tags("add_forum", array(
				"NAME" => $_POST['name'],
				"DESCRIPTION" => $_POST['description'],
				"REDIRECT_URL" => $_POST['redirect_url']
			));

			$theme->insert_nest("add_forum", "error", array(
				"ERROR" => $error
			));
			$theme->add_nest("add_forum", "error");

			$cat_sql = $db->query("SELECT * FROM `".$db_prefix."categories` ORDER BY `cat_orderby` ASC");
			while($cat_result = $db->fetch_array($cat_sql)) {
				if($cat_result['cat_id'] == $_GET['cid']) {
					$selected = " selected=\"selected\"";
				} else {
					$selected = "";
				}

				$theme->insert_nest("add_forum", "category_select", array(
					"CAT_ID" => "c" . $cat_result['cat_id'],
					"CAT_STYLE" => "font-weight:bold;",
					"CAT_PREFIX" => "+",
					"CAT_NAME" => $cat_result['cat_name'],
					"SELECTED" => $selected
				));
				$theme->add_nest("add_forum", "category_select");

				$forum_query = $db->query("SELECT `forum_id`, `forum_name` FROM `".$db_prefix."forums`
										WHERE `forum_cat_id` = '" . $cat_result['cat_id'] . "' AND `forum_type` = 'c'
										ORDER BY `forum_orderby` ASC");

				while($forum_result = $db->fetch_array($forum_query))
				{

					$theme->insert_nest("add_forum", "category_select", array(
						"CAT_ID" => "f" . $forum_result['forum_id'],
						"CAT_STYLE" => "font-weight:normal;",
						"CAT_PREFIX" => "+-+",
						"CAT_NAME" => $forum_result['forum_name'],
						"SELECTED" => ""
					));
					$theme->add_nest("add_forum", "category_select");

					_generate_category_dropdown($forum_result['forum_id'], "add_forum", "+-+-+");
				}
			}
			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("add_forum");

			//
			// Output the page footer
			//
			include($root_path . "includes/page_footer.php");

		} else {
			$sql = $db->query("SELECT `forum_orderby` FROM `".$db_prefix."forums` WHERE `forum_cat_id` = '".substr($_POST['cid'], 1)."' AND `forum_type` = '".substr($_POST['cid'], 0, 1)."' ORDER BY `forum_orderby` DESC LIMIT 1");

			if($result = $db->fetch_array($sql)) {
				$orderby = ($result['forum_orderby'] + 1);
			} else {
				$orderby = 1;
			}

			//====================================
			// Set redirect URL to null if empty
			//====================================
			if(!empty($_POST['redirect_url']))
			{
				$redirect_url = "'".trim($_POST['redirect_url'])."'";
			}
			else
			{
				$redirect_url = "NULL";
			}

			$sql = "INSERT INTO `".$db_prefix."forums`
					(`forum_cat_id`, `forum_type`, `forum_name`, `forum_description`, `forum_redirect_url`, `forum_read`, `forum_post`, `forum_reply`, `forum_poll`, `forum_create_poll`, `forum_mod`, `forum_orderby`)
					VALUES ('".substr($_POST['cid'], 1)."', '".substr($_POST['cid'], 0, 1)."', '".$_POST['name']."', '".$_POST['description']."', ".$redirect_url.", \n";


			if(!isset($_POST['advanced_permissions'])) {

				if(substr($_POST['simple_select'], -1) == "H") {
					$forum_read = str_replace("H", "", $_POST['simple_select']);
				} else {
					$forum_read = "1";
				}

				$sql .= "'".$forum_read."', '".$_POST['simple_select']."', '".$_POST['simple_select']."', '".$_POST['simple_select']."', '".$_POST['simple_select']."', ";
				if($_POST['simple_select'] == "5") {
					$sql .= "'5'";
				} else {
					$sql .= "'4'";
				}

			} else {
				$sql .= "'".$_POST['Read']."', '".$_POST['Post']."', '".$_POST['Reply']."', '".$_POST['Poll']."', '".$_POST['Create_Poll']."', '".$_POST['Mod']."'";
			}

			$sql .= ", '$orderby')";
			$db->query($sql);
			info_box($lang['Create_Forum'], $lang['Forum_Created_Msg'], "forums.php");
		}

	} else {
		if(!isset($_POST['name'])) $_POST['name'] = "";
		if(!isset($_GET['cid'])) $_GET['cid'] = "";
		$theme->new_file("add_forum", "add_forum.tpl");
		$theme->replace_tags("add_forum", array(
			"NAME" => $_POST['name'],
			"DESCRIPTION" => "",
			"REDIRECT_URL" => ""
		));

		$cat_sql = $db->query("SELECT * FROM `".$db_prefix."categories` ORDER BY `cat_orderby` ASC");
		while($cat_result = $db->fetch_array($cat_sql)) {
			if($cat_result['cat_id'] == $_GET['cid']) {
				$selected = " selected=\"selected\"";
			} else {
				$selected = "";
			}

			$theme->insert_nest("add_forum", "category_select", array(
				"CAT_ID" => "c" . $cat_result['cat_id'],
				"CAT_STYLE" => "font-weight:bold;",
				"CAT_PREFIX" => "+",
				"CAT_NAME" => $cat_result['cat_name'],
				"SELECTED" => $selected
			));
			$theme->add_nest("add_forum", "category_select");

			$forum_query = $db->query("SELECT `forum_id`, `forum_name` FROM `".$db_prefix."forums`
									WHERE `forum_cat_id` = '" . $cat_result['cat_id'] . "' AND `forum_type` = 'c'
									ORDER BY `forum_orderby` ASC");

			while($forum_result = $db->fetch_array($forum_query))
			{

				$theme->insert_nest("add_forum", "category_select", array(
					"CAT_ID" => "f" . $forum_result['forum_id'],
					"CAT_STYLE" => "font-weight:normal;",
					"CAT_PREFIX" => "+-+",
					"CAT_NAME" => $forum_result['forum_name'],
					"SELECTED" => ""
				));
				$theme->add_nest("add_forum", "category_select");

				_generate_category_dropdown($forum_result['forum_id'], "add_forum", "+-+-+");
			}
		}
		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("add_forum");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");
	}

} else if($_GET['func'] == "add_category") {

	$sql = $db->query("SELECT `cat_orderby` FROM `".$db_prefix."categories` ORDER BY `cat_orderby` DESC LIMIT 1");
	if($result = $db->fetch_array($sql)) {
		$orderby = ($result['cat_orderby'] + 1);
	} else {
		$orderby = 1;
	}

	if(strlen($_POST['name']) < 1) {
		error_msg($lang['Error'], sprintf($lang['No_x_content'], strtolower($lang['Category_Name'])));
	}

	$db->query("INSERT INTO `".$db_prefix."categories` (`cat_name`, `cat_orderby`) VALUES('".$_POST['name']."', '".$orderby."')");

	info_box($lang['Create_Category'], $lang['Category_Created_Msg'], "forums.php");

} else if($_GET['func'] == "edit_category") {
	$query = $db->query("SELECT `cat_name` FROM `".$db_prefix."categories` WHERE `cat_id` = '".$_GET['cid']."'");
	if($result = $db->fetch_array($query)) {
		if(isset($_POST['Submit'])) {
			if(strlen($_POST['name']) < 1) {
				error_msg($lang['Error'], sprintf($lang['No_x_content'], strtolower($lang['Category_Name'])));
			}

			$db->query("UPDATE `".$db_prefix."categories` SET `cat_name` = '".$_POST['name']."' WHERE `cat_id` = '".$_GET['cid']."'");

			info_box($lang['Edit_Category'], $lang['Category_Updated_Msg'], "forums.php");
		} else {
			$theme->new_file("edit_category", "edit_category.tpl");

			$theme->replace_tags("edit_category", array(
				"NAME" => $result['cat_name']
			));

			//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("edit_category");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");
		}
	} else {
		error_msg($lang['Error'], $lang['Invalid_Category_Id']);
	}

} else if($_GET['func'] == "edit_forum") {
	if(isset($_POST['Submit'])) {
		if(empty($_POST['name'])) {
			error_msg($lang['Error'], sprintf($lang['No_x_content'], strtolower($lang['Forum_Name'])));
		}
		if(!isset($_POST['cid'])) {
			error_msg($lang['Error'], sprintf($lang['No_x_content'], strtolower($lang['Category'])));
		}

		//====================================
		// Set redirect URL to null if empty
		//====================================
		if(!empty($_POST['redirect_url']))
		{
			$redirect_url = "'".trim($_POST['redirect_url'])."'";
		}
		else
		{
			$redirect_url = "NULL";
		}

		$sql = "UPDATE `".$db_prefix."forums` SET `forum_cat_id` = '".substr($_POST['cid'], 1)."', `forum_type` = '".substr($_POST['cid'], 0, 1)."', `forum_name` = '".$_POST['name']."', `forum_description` = '".$_POST['description']."', `forum_redirect_url` = ".$redirect_url.",  ";
		if(!isset($_POST['advanced_permissions'])) {

			if(substr($_POST['simple_select'], -1) == "H") {
				$forum_read = str_replace("H", "", $_POST['simple_select']);
			} else {
				$forum_read = "1";
			}

			$sql .= "`forum_read` = '".$forum_read."', `forum_post` = '".$_POST['simple_select']."', `forum_reply` = '".$_POST['simple_select']."', `forum_poll` = '".$_POST['simple_select']."', `forum_create_poll` = '".$_POST['simple_select']."', ";
			if($_POST['simple_select'] == "5") {
				$sql .= "`forum_mod` = '5'";
			} else {
				$sql .= "`forum_mod` = '4'";
			}
		} else {
			$sql .= " `forum_read` = '".$_POST['Read']."', `forum_post` = '".$_POST['Post']."', `forum_reply` = '".$_POST['Reply']."', `forum_poll` = '".$_POST['Poll']."', `forum_create_poll` = '".$_POST['Create_Poll']."', `forum_mod` = '".$_POST['Mod']."'";
		}

		$sql .= " WHERE `forum_id` = '".$_GET['fid']."'";

		$db->query($sql);

		info_box($lang['Edit_Forum'], $lang['Forum_Updated_Msg'], "forums.php");


	} else {
		if(!isset($_GET['fid']) || !is_numeric($_GET['fid'])) error_msg($lang['Error'], $lang['Invalid_Forum_Id']);
		$theme->new_file("edit_forum", "edit_forum.tpl");

		$sql = $db->query("SELECT * FROM `".$db_prefix."forums` WHERE `forum_id` = '".$_GET['fid']."'");
		if($result = $db->fetch_array($sql)) {
			if(($result['forum_read'] == $result['forum_post'] || $result['forum_read'] == 1) && $result['forum_post'] == $result['forum_reply'] && $result['forum_reply'] == $result['forum_poll'] && $result['forum_poll'] == $result['forum_create_poll'] && ($result['forum_mod'] == 4 || ($result['forum_read'] == 5 && $result['forum_mod'] == 5))) {
				if($result['forum_read'] != $result['forum_post'] || $result['forum_post'] == 1)
				{
					$hidden = false;
				}
				else
				{
					$hidden = true;
				}
				$adv_checked = "";
			} else {
				$hidden = false;
				$adv_checked = " checked=\"checked\"";
			}
			$select_array = array("S" => "post", "E" => "read", "P" => "post", "R" => "reply", "V" => "poll", "CP" => "create_poll", "M" => "mod");
			foreach($select_array as $select => $column) {
				for($i = 0; $i < 6; $i++) {
					if($i == $result['forum_'.$column]) {
						if($select == "S" && $hidden)
						{
							$selected_array[$select.$i.'H'] = " selected=\"selected\"";
							$selected_array[$select.$i] = "";
						}
						else
						{
							$selected_array[$select.$i.'H'] = "";
							$selected_array[$select.$i] = " selected=\"selected\"";
						}
					} else {
						$selected_array[$select.$i.'H'] = "";
						$selected_array[$select.$i] = "";
					}
				}
			}

			$theme->replace_tags("edit_forum", $selected_array);

			$theme->replace_tags("edit_forum", array(
				"NAME" => $result['forum_name'],
				"DESCRIPTION" => $result['forum_description'],
				"REDIRECT_URL" => $result['forum_redirect_url'],
				"ADV_CHECKED" => $adv_checked,
			));

			$current_cat_id = $result['forum_cat_id'];
			$current_cat_type = $result['forum_type'];

			$cat_sql = $db->query("SELECT * FROM `".$db_prefix."categories` ORDER BY `cat_orderby` ASC");
			while($cat_result = $db->fetch_array($cat_sql)) {
				if($cat_result['cat_id'] == $result['forum_cat_id'] && $result['forum_type'] == "c") {
					$selected = " selected=\"selected\"";
				} else {
					$selected = "";
				}

				$theme->insert_nest("edit_forum", "category_select", array(
					"CAT_ID" => "c" . $cat_result['cat_id'],
					"CAT_STYLE" => "font-weight:bold;",
					"CAT_PREFIX" => "+",
					"CAT_NAME" => $cat_result['cat_name'],
					"SELECTED" => $selected
				));
				$theme->add_nest("edit_forum", "category_select");

				$forum_query = $db->query("SELECT `forum_id`, `forum_name` FROM `".$db_prefix."forums`
										WHERE `forum_cat_id` = '" . $cat_result['cat_id'] . "' AND `forum_type` = 'c'
										ORDER BY `forum_orderby` ASC");

				while($forum_result = $db->fetch_array($forum_query))
				{
					if($forum_result['forum_id'] == $result['forum_cat_id'] && $result['forum_type'] == "f") {
						$selected = " selected=\"selected\"";
					} else {
						$selected = "";
					}

					$theme->insert_nest("edit_forum", "category_select", array(
						"CAT_ID" => "f" . $forum_result['forum_id'],
						"CAT_STYLE" => "font-weight:normal;",
						"CAT_PREFIX" => "+-+",
						"CAT_NAME" => $forum_result['forum_name'],
						"SELECTED" => $selected
					));
					$theme->add_nest("edit_forum", "category_select");

					_generate_category_dropdown($forum_result['forum_id'], "edit_forum", "+-+-+");
				}
			}
			//
			// Output the page header
			//
			include($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("edit_forum");

			//
			// Output the page footer
			//
			include($root_path . "includes/page_footer.php");
		} else {
			error_msg($lang['Error'], $lang['Invalid_Forum_Id']);
		}
	}
} else if($_GET['func'] == "delete_forum") {
	if(!isset($_GET['fid'])) {
		info_box($lang['Error'], $lang['Invalid_Forum_Id'], "forums.php");
	}
	if(!isset($_GET['confirm'])) {
		confirm_msg($lang['Delete_Forum'], $lang['Delete_Forum_Msg'], "forums.php?func=delete_forum&fid=".$_GET['fid']."&confirm=1", "forums.php");
	} else {
		_delete_subforums($_GET['fid']);

		$topic_query = $db->query("SELECT `topic_id` FROM `".$db_prefix."topics` WHERE `topic_forum_id` = '".$_GET['fid']."'");
		while($topic_result = $db->fetch_array($topic_query))
		{
			$db->query("DELETE FROM `".$db_prefix."posts` WHERE `post_topic_id` = '".$topic_result['topic_id']."'");
			$db->query("DELETE FROM `".$db_prefix."topics` WHERE `topic_id` = '".$topic_result['topic_id']."'");
		}
		$db->query("DELETE FROM `".$db_prefix."forums` WHERE `forum_id` = '".$_GET['fid']."'");
		info_box($lang['Delete_Forum'], $lang['Forum_Deleted_Msg'], "forums.php");
	}

} else if($_GET['func'] == "delete_category") {
	if(isset($_POST['Submit'])) {
		if(!isset($_GET['cid'])) {
			info_box($lang['Error'], $lang['Invalid_Category_Id'], "forums.php");
		}

		if(!isset($_GET['move_to'])) $_GET['move_to'] = "0";

		if($_GET['move_to'] == "0") {
			$query = $db->query("SELECT `forum_id` FROM `".$db_prefix."forums` WHERE `forum_cat_id` = '".$_GET['cid']."' AND `forum_type` = 'c'");
			while($result = $db->fetch_array($query))
			{
				_delete_subforums($result['forum_id']);

				$topic_query = $db->query("SELECT `topic_id` FROM `".$db_prefix."topics` WHERE `topic_forum_id` = '".$result['forum_id']."'");
				while($topic_result = $db->fetch_array($topic_query))
				{
					$db->query("DELETE FROM `".$db_prefix."posts` WHERE `post_topic_id` = '".$topic_result['topic_id']."'");
					$db->query("DELETE FROM `".$db_prefix."topics` WHERE `topic_id` = '".$topic_result['topic_id']."'");
				}
				$db->query("DELETE FROM `".$db_prefix."forums` WHERE `forum_id` = '".$result['forum_id']."'");
			}
			$db->query("DELETE FROM `".$db_prefix."categories` WHERE `cat_id` = '".$_GET['cid']."'");
			info_box($lang['Delete_Category'], $lang['Category_Deleted_Msg'], "forums.php");
		} else {
			$db->query("UPDATE `".$db_prefix."forums` SET `forum_cat_id` = '".$_GET['move_to']."' WHERE `cat_id` = '".$_GET['cid']."'");
			$db->query("DELETE FROM `".$db_prefix."categories` WHERE `cat_id` = '".$_GET['cid']."'");
			info_box($lang['Delete_Category'], $lang['Category_Deleted_Msg'], "forums.php");
		}

	} else {
		$theme->new_file("delete_category", "delete_category.tpl");
		$sql = $db->query("SELECT * FROM `".$db_prefix."categories` WHERE `cat_id` != '".$_GET['cid']."' ORDER BY `cat_id`");
		while($result = $db->fetch_array($sql)) {
			$theme->insert_nest("delete_category", "move_to_options", array(
				"CAT_ID" => $result['cat_id'],
				"CAT_NAME" => $result['cat_name']
			));

			$theme->add_nest("delete_category", "move_to_options");
		}
		//
		// Output the page header
		//
		include($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("delete_category");

		//
		// Output the page footer
		//
		include($root_path . "includes/page_footer.php");
	}
}
else
{
	if(isset($_GET['move']))
	{
		if(isset($_GET['cid']))
		{
			$old_sign = ($_GET['move'] == "up") ? "+" : "-";
			$new_sign = ($_GET['move'] == "up") ? "-" : "+";
			$query = $db->query("SELECT c.`cat_id`, p.`cat_id`
                                 AS 'old_cat_id'
								 FROM (`".$db_prefix."categories` c
								 LEFT JOIN `".$db_prefix."categories` p
                                 ON p.`cat_orderby` = (c.`cat_orderby` ".$new_sign." 1))
								 WHERE c.`cat_id` = '".$_GET['cid']."'"
            );

			if($result = $db->fetch_array($query))
			{
				if(!(empty($result['cat_id']) || empty($result['old_cat_id'])))
				{
					$db->query("UPDATE `".$db_prefix."categories` SET `cat_orderby` = (`cat_orderby` ".$new_sign." 1) WHERE `cat_id` = '".$result['cat_id']."'");
					$db->query("UPDATE `".$db_prefix."categories` SET `cat_orderby` = (`cat_orderby` ".$old_sign." 1) WHERE `cat_id` = '".$result['old_cat_id']."'");
				}
			}
		}
        else if(isset($_GET['fid']))
        {
			$old_sign = ($_GET['move'] == "up") ? "+" : "-";
			$new_sign = ($_GET['move'] == "up") ? "-" : "+";
			$query = $db->query("SELECT f.`forum_id`, p.`forum_id`
                                 AS 'old_forum_id'
								 FROM (`".$db_prefix."forums` f
								 LEFT JOIN `".$db_prefix."forums` p
                                 ON p.`forum_orderby` = (f.`forum_orderby` ".$new_sign." 1)
                                 AND p.`forum_cat_id` = f.`forum_cat_id`
                                 AND p.`forum_cat_id` = f.`forum_cat_id`)
								 WHERE f.`forum_id` = '".$_GET['fid']."'"
            );

			if($result = $db->fetch_array($query))
			{
				if(!(empty($result['forum_id']) || empty($result['old_forum_id'])))
				{
					$db->query("UPDATE `".$db_prefix."forums` SET `forum_orderby` = (`forum_orderby` ".$new_sign." 1) WHERE `forum_id` = '".$result['forum_id']."'");
					$db->query("UPDATE `".$db_prefix."forums` SET `forum_orderby` = (`forum_orderby` ".$old_sign." 1) WHERE `forum_id` = '".$result['old_forum_id']."'");
				}
			}
		}
	}
	$theme->new_file("manage_forums", "manage_forums.tpl");
	$cat_sql = $db->query("SELECT * FROM `".$db_prefix."categories` ORDER BY `cat_orderby`");
	while ($catagory = $db->fetch_array($cat_sql))
	{
		$theme->insert_nest("manage_forums", "catrow", array(
			"CAT_ID" => $catagory['cat_id'],
			"CAT_NAME" => $catagory['cat_name']
		));
		$forum_no = 0;
        /////////////// BUG FIX - Fixed the subforums listing twice.. //////////////////
		$forum_sql = $db->query("SELECT * FROM `".$db_prefix."forums`
                                 WHERE `forum_cat_id` = '"  . $catagory['cat_id'] .  "'
                                 AND `forum_type` = 'c'
                                 ORDER BY `forum_orderby`"
        );
		while ($forum = $db->fetch_array($forum_sql))
		{
			if($forum['forum_redirect_url'] != null)
			{
				$theme->switch_nest("manage_forums", "catrow/forumrow", false, array(
					"FORUM_ID" => $forum['forum_id'],
					"FORUM_NAME" => $forum['forum_name'],
					"FORUM_DESCRIPTION" => $forum['forum_description'],
					"REDIRECTS" => sprintf($lang['X_Hits'], $forum['forum_topics'])
				));
	
	            $theme->add_nest("manage_forums", "catrow/forumrow");
			}
			else
			{
				$theme->switch_nest("manage_forums", "catrow/forumrow", true, array(
					"FORUM_ID" => $forum['forum_id'],
					"FORUM_NAME" => $forum['forum_name'],
					"FORUM_DESCRIPTION" => $forum['forum_description'],
					"TOPICS" => $forum['forum_topics'],
					"POSTS" => $forum['forum_posts']
				));
	
	            $theme->add_nest("manage_forums", "catrow/forumrow");
			}

			//
			// Generate Sub-forums
			//
            $forum_route[0]['id'] = $forum['forum_id'];
            $forum_route[0]['name'] = $forum['forum_name'];
			_generate_subforums($forum['forum_id'], $forum_route);

			$forum_no++;
		}

		if($forum_no == 0)
		{
			$theme->switch_nest("manage_forums", "catrow/forum_titles", false);
		}
		else
		{
			$theme->switch_nest("manage_forums", "catrow/forum_titles", true);
		}
		$theme->add_nest("manage_forums", "catrow");
	}

	//
	// Output the page header
	//
	include($root_path . "includes/page_header.php");

	//
	// Output the main page
	//
	$theme->output("manage_forums");

	//
	// Output the page footer
	//
	include($root_path . "includes/page_footer.php");
}


function _generate_subforums($forum_id, $forum_route)
{
	global $db, $theme, $db_prefix ,$lang;

	$query = $db->query("SELECT `forum_id`, `forum_name`, `forum_description`, `forum_redirect_url`, `forum_topics`, `forum_posts` FROM `".$db_prefix."forums` WHERE `forum_cat_id` = '".$forum_id."' AND `forum_type` = 'f' ORDER BY `forum_orderby` ASC");

	while($result = $db->fetch_array($query))
	{
			if($result['forum_redirect_url'] != null)
			{
				$theme->switch_nest("manage_forums", "catrow/forumrow", false, array(
					"FORUM_ID" => $result['forum_id'],
					"FORUM_NAME" => $result['forum_name'],
					"FORUM_DESCRIPTION" => $result['forum_description'],
					"REDIRECTS" => sprintf($lang['X_Hits'], $result['forum_topics'])
				));
			}
			else
			{
				$theme->switch_nest("manage_forums", "catrow/forumrow", true, array(
					"FORUM_ID" => $result['forum_id'],
					"FORUM_NAME" => $result['forum_name'],
					"FORUM_DESCRIPTION" => $result['forum_description'],
					"TOPICS" => $result['forum_topics'],
					"POSTS" => $result['forum_posts']
				));
			}

			for($i = 0; $i < count($forum_route); $i++)
			{
				$theme->insert_nest("manage_forums", "catrow/forumrow/subforum", array(
					"SUBFORUM_ID" => $forum_route[$i]['id'],
					"SUBFORUM_NAME" => $forum_route[$i]['name']
				));
				$theme->add_nest("manage_forums", "catrow/forumrow/subforum");
			}

			$theme->add_nest("manage_forums", "catrow/forumrow");

			$forum_route_count = count($forum_route);

			$forum_route[$forum_route_count]['id'] = $result['forum_id'];
			$forum_route[$forum_route_count]['name'] = $result['forum_name'];
			_generate_subforums($result['forum_id'], $forum_route);
			unset($forum_route[$forum_route_count]);
	}
	return true;
}

function _generate_category_dropdown($forum_id, $template_name, $prefix, $check_selected = true)
{
	global $db, $theme, $db_prefix, $current_cat_id, $current_cat_type;

	if(isset($_GET['fid']))
	{
		$forum_query = $db->query("SELECT `forum_id`, `forum_name` FROM `".$db_prefix."forums`
								WHERE `forum_cat_id` = '" . $forum_id . "' AND `forum_type` = 'f' AND `forum_id` != '" . $_GET['fid'] . "'
								ORDER BY `forum_orderby` DESC");
	}
	else
	{
		$forum_query = $db->query("SELECT `forum_id`, `forum_name` FROM `".$db_prefix."forums`
								WHERE `forum_cat_id` = '" . $forum_id . "' AND `forum_type` = 'f'
								ORDER BY `forum_orderby` DESC");
	}

	while($forum_result = $db->fetch_array($forum_query))
	{
		if($forum_result['forum_id'] == $current_cat_id && $current_cat_type == "f" && $check_selected) {
			$selected = " selected=\"selected\"";
		} else {
			$selected = "";
		}

		$theme->insert_nest($template_name, "category_select", array(
			"CAT_ID" => "f" . $forum_result['forum_id'],
			"CAT_STYLE" => "font-weight:normal;",
			"CAT_PREFIX" => $prefix,
			"CAT_NAME" => $forum_result['forum_name'],
			"SELECTED" => $selected
		));
		$theme->add_nest($template_name, "category_select");

		_generate_category_dropdown($forum_result['forum_id'], $template_name, $prefix . "-+", $check_selected);
	}

	return true;
}

function _delete_subforums($forum_id)
{
	global $db, $db_prefix;

	$query = $db->query("SELECT `forum_id` FROM `".$db_prefix."forums` WHERE `forum_cat_id` = '".$forum_id."' AND `forum_type` = 'f'");

	while($result = $db->fetch_array($query))
	{
		_delete_subforums($result['forum_id']);
		$db->query("DELETE FROM `".$db_prefix."forums` WHERE `forum_id` = '".$result['forum_id']."'");

		$topic_query = $db->query("SELECT `topic_id` FROM `".$db_prefix."topics` WHERE `topic_forum_id` = '".$result['forum_id']."'");
		while($topic_result = $db->fetch_array($topic_query))
		{
			$db->query("DELETE FROM `".$db_prefix."posts` WHERE `post_topic_id` = '".$topic_result['topic_id']."'");
			$db->query("DELETE FROM `".$db_prefix."topics` WHERE `topic_id` = '".$topic_result['topic_id']."'");
		}
	}
	return true;
}

?>
