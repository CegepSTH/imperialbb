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
require_once($root_path."includes/common.php");
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

			$db_cat = $db2->query("SELECT * FROM `_PREFIX_categories` ORDER BY `cat_orderby` ASC");
			
			while($cat_result = $db_cat->fetch()) {
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

				$db2->query("SELECT `forum_id`, `forum_name` 
					FROM `_PREFIX_forums`
					WHERE `forum_cat_id`=:fcid AND `forum_type` = 'c'
					ORDER BY `forum_orderby` ASC", array(":fcid" => $cat_result['cat_id']));

				while($forum_result = $db2->fetch())
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
			include_once($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("add_forum");

			//
			// Output the page footer
			//
			include_once($root_path . "includes/page_footer.php");

		} else {
			$db2->query("SELECT `forum_orderby` 
				FROM `_PREFIX_forums` 
				WHERE `forum_cat_id`=:cid 
				AND `forum_type`=:cidd 
				ORDER BY `forum_orderby` 
				DESC LIMIT 1", array(":cid" => substr($_POST['cid'], 1), ":cidd" => substr($_POST['cid'], 0, 1)));

			if($result = $db2->fetch()) {
				$orderby = ($result['forum_orderby'] + 1);
			} else {
				$orderby = 1;
			}

			//====================================
			// Set redirect URL to null if empty
			//====================================
			if(!empty($_POST['redirect_url'])) {
				$redirect_url = "'".trim($_POST['redirect_url'])."'";
			} else {
				$redirect_url = null;
			}

			$values = array(":cid" => substr($_POST['cid'], 1), ":cidd" => substr($_POST['cid'], 0, 1), ":name" => $_POST['name'],
				":desc" => $_POST['description'], ":redir" => $redirect_url);
			$sql = "INSERT INTO `_PREFIX_forums`
					(`forum_cat_id`, `forum_type`, `forum_name`, `forum_description`, `forum_redirect_url`, `forum_read`, `forum_post`, `forum_reply`, `forum_poll`, `forum_create_poll`, `forum_mod`, `forum_orderby`)
					VALUES (:cid, :cidd, :name, :desc, :redir, ";

			if(!isset($_POST['advanced_permissions'])) {
				if(substr($_POST['simple_select'], -1) == "H") {
					$forum_read = str_replace("H", "", $_POST['simple_select']);
				} else {
					$forum_read = "1";
				}
				$values[":fread"] = $forum_read;
				$values[":simps"] = $_POST['simple_select'];
				$values[":simpss"] = $_POST['simple_select'];
				$values[":simpsss"] = $_POST['simple_select'];
				$values[":simpssss"] = $_POST['simple_select'];
				$sql .= ":fread, :simps, :simpss, :simpsss, :simpssss, ";
				
				if($_POST['simple_select'] == "5") {
					$sql .= "'5'";
				} else {
					$sql .= "'4'";
				}

			} else {
				$values[":fread"] = $_POST['Read'];
				$values[":post"] = $_POST['Post'];
				$values[":reply"] = $_POST['Reply'];
				$values[":poll"] = $_POST['Poll'];
				$values[":cpoll"] = $_POST['Create_Poll'];
				$values[":mod"] = $_POST['Mod'];
				$sql .= ":fread, :post, :reply, :poll, :cpoll, :mod";
			}
			$values[":orderby"] = $orderby;
			$sql .= ", :orderby)";
			$db2->query($sql, $values);
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

		$db_forum = $db2->query("SELECT * FROM `_PREFIX_categories` ORDER BY `cat_orderby` ASC");
		while($cat_result = $db_forum->fetch()) {
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
			
			$db2->query("SELECT `forum_id`, `forum_name` FROM `_PREFIX_forums`
				WHERE `forum_cat_id`=:catr AND `forum_type` = 'c'
				ORDER BY `forum_orderby` ASC", 
				array(":catr" => $cat_result['cat_id']));

			while($forum_result = $db2->fetch())
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
		include_once($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("add_forum");

		//
		// Output the page footer
		//
		include_once($root_path . "includes/page_footer.php");
	}

} else if($_GET['func'] == "add_category") {

	$db2->query("SELECT `cat_orderby` FROM `_PREFIX_categories` ORDER BY `cat_orderby` DESC LIMIT 1");
	if($result = $db2->fetch()) {
		$orderby = ($result['cat_orderby'] + 1);
	} else {
		$orderby = 1;
	}

	if(strlen($_POST['name']) < 1) {
		error_msg($lang['Error'], sprintf($lang['No_x_content'], strtolower($lang['Category_Name'])));
	}

	$db2->query("INSERT INTO `_PREFIX_categories` (`cat_name`, `cat_orderby`) 
		VALUES(:name, :ordby)", array(":name" => $_POST['name'], ":ordby" => $orderby));

	info_box($lang['Create_Category'], $lang['Category_Created_Msg'], "forums.php");

} else if($_GET['func'] == "edit_category") {
	$db2->query("SELECT `cat_name` FROM `_PREFIX_categories` WHERE `cat_id`=:cid", array(":cid" => $_GET['cid']));
	if($result = $db2->fetch()) {
		if(isset($_POST['Submit'])) {
			if(strlen($_POST['name']) < 1) {
				error_msg($lang['Error'], sprintf($lang['No_x_content'], strtolower($lang['Category_Name'])));
			}

			$db2->query("UPDATE `_PREFIX_categories` 
				SET `cat_name`=:name 
				WHERE `cat_id`=:cid", 
				array(":name" => $_POST['name'], ":cid" => $_POST['cid']));

			info_box($lang['Edit_Category'], $lang['Category_Updated_Msg'], "forums.php");
		} else {
			$theme->new_file("edit_category", "edit_category.tpl");

			$theme->replace_tags("edit_category", array(
				"NAME" => $result['cat_name']
			));

			//
		// Output the page header
		//
		include_once($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("edit_category");

		//
		// Output the page footer
		//
		include_once($root_path . "includes/page_footer.php");
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
		if(!empty($_POST['redirect_url'])) {
			$redirect_url = "'".trim($_POST['redirect_url'])."'";
		} else {
			$redirect_url = null;
		}
		
		$values = array(":cid" => substr($_POST['cid'], 1), ":cidd" => substr($_POST['cid'], 0, 1), 
			":fname" => $_POST['name'], ":desc" => $_POST['description'], ":furl" => $redirect_url);
			 
		$sql = "UPDATE `_PREFIX_forums` 
			SET `forum_cat_id`=:cid, `forum_type`=:cidd, `forum_name`=:fname, `forum_description`=:desc, `forum_redirect_url`=:furl, ";
			
		if(!isset($_POST['advanced_permissions'])) {

			if(substr($_POST['simple_select'], -1) == "H") {
				$forum_read = str_replace("H", "", $_POST['simple_select']);
			} else {
				$forum_read = "1";
			}
			$values[":fread"] = $forum_read;
			$values[":fpost"] = $_POST['simple_select'];
			$values[":freply"] = $_POST['simple_select'];
			$values[":fpoll"] = $_POST['simple_select'];
			$values[":cpoll"] = $_POST['simple_select'];
			
			$sql .= "`forum_read`=:fread, `forum_post`=:fpost, `forum_reply`=:freply, `forum_poll`=:fpoll, `forum_create_poll`=:cpoll, ";
			if($_POST['simple_select'] == "5") {
				$sql .= "`forum_mod` = '5'";
			} else {
				$sql .= "`forum_mod` = '4'";
			}
		} else {
			$values[":fread"] = $_POST['Read'];
			$values[":fpost"] = $_POST['Post'];
			$values[":freply"] = $_POST['Reply'];
			$values[":fpoll"] = $_POST['Poll'];
			$values[":cpoll"] = $_POST['Create_Poll'];
			$values[":mod"] = $_POST['Mod'];
			$sql .= " `forum_read`=:fread, `forum_post`=:fpost, `forum_reply`=:freply, `forum_poll`=:fpoll, `forum_create_poll`=:cpoll, `forum_mod`=:mod";
		}
		$values[":fid"] = $_GET['fid'];
		$sql .= " WHERE `forum_id`=:fid";

		$db2->query($sql, $values);

		info_box($lang['Edit_Forum'], $lang['Forum_Updated_Msg'], "forums.php");
	} else {
		if(!isset($_GET['fid']) || !is_numeric($_GET['fid'])) error_msg($lang['Error'], $lang['Invalid_Forum_Id']);
		$theme->new_file("edit_forum", "edit_forum.tpl");

		$db2->query("SELECT * FROM `_PREFIX_forums` WHERE `forum_id`=:fid", array(":fid" => $_GET['fid']));
		if($result = $db2->fetch()) {
			if(($result['forum_read'] == $result['forum_post'] || $result['forum_read'] == 1) && $result['forum_post'] == $result['forum_reply'] && $result['forum_reply'] == $result['forum_poll'] && $result['forum_poll'] == $result['forum_create_poll'] && ($result['forum_mod'] == 4 || ($result['forum_read'] == 5 && $result['forum_mod'] == 5))) {
				if($result['forum_read'] != $result['forum_post'] || $result['forum_post'] == 1) {
					$hidden = false;
				} else {
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
			
			$db_cat = $db2->query("SELECT * FROM `_PREFIX_categories` ORDER BY `cat_orderby` ASC");
			
			while($cat_result = $db_cat->fetch()) {
				
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

				$db2->query("SELECT `forum_id`, `forum_name` FROM `_PREFIX_forums`
										WHERE `forum_cat_id`=:catid AND `forum_type` = 'c'
										ORDER BY `forum_orderby` ASC", array(":catid" => $cat_result['cat_id']));

				while($forum_result = $db2->fetch())
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
			include_once($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("edit_forum");

			//
			// Output the page footer
			//
			include_once($root_path . "includes/page_footer.php");
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

		$db_topic = $db2->query("SELECT `topic_id` FROM `_PREFIX_topics` WHERE `topic_forum_id`=:fid", array(":fid" => $_GET['fid']));
		
		while($topic_result = $db_topic->fetch()) {
			$value = array(":tid" => $topic_result['topic_id']);
			$db2->query("DELETE FROM `_PREFIX_posts` WHERE `post_topic_id`=:tid", $value);
			$db2->query("DELETE FROM `_PREFIX_topics` WHERE `topic_id`=:tid", $value);
		}
		
		$db2->query("DELETE FROM `_PREFIX_forums` WHERE `forum_id`=:fid", array(":fid" => $_GET['fid']));
		info_box($lang['Delete_Forum'], $lang['Forum_Deleted_Msg'], "forums.php");
	}
} else if($_GET['func'] == "delete_category") {
	if(isset($_POST['Submit'])) {
		if(!isset($_GET['cid'])) {
			info_box($lang['Error'], $lang['Invalid_Category_Id'], "forums.php");
		}

		if(!isset($_GET['move_to'])) $_GET['move_to'] = "0";

		if($_GET['move_to'] == "0") {
			$db_sub = $db2->query("SELECT `forum_id` FROM `_PREFIX_forums`
				WHERE `forum_cat_id`=:cid AND `forum_type` = 'c'", array(":cid" => $_GET['cid']));
				
			while($result = $db_sub->fetch()) {
				_delete_subforums($result['forum_id']);
				
				$db_del = $db2->query("SELECT `topic_id` FROM `_PREFIX_topics` WHERE `topic_forum_id`=:fid", array(":fid" => $result['forum_id']));
				
				while($topic_result = $db_del->fetch()) {
					$db2->query("DELETE FROM `_PREFIX_posts` WHERE `post_topic_id`=:tid", array(":tid" => $topic_result['topic_id']));
					$db2->query("DELETE FROM `_PREFIX_topics` WHERE `topic_id`=:tid", array(":tid" => $topic_result['topic_id']));
				}
				
				$db_sub->query("DELETE FROM `_PREFIX_forums` WHERE `forum_id`=:fid", array(":fid" => $result['forum_id']));
			}
			
			$db2->query("DELETE FROM `_PREFIX_categories` WHERE `cat_id`=:cid", array(":cid" => $_GET['cid']));
			info_box($lang['Delete_Category'], $lang['Category_Deleted_Msg'], "forums.php");
		} else {
			$db2->query("UPDATE `_PREFIX_forums` SET `forum_cat_id`=:move WHERE `cat_id`=:cid", array(":move" => $_GET['move_to'], ":cid" => $_GET['cid']));
			$db2->query("DELETE FROM `_PREFIX_categories` WHERE `cat_id`=:cid", array(":cid" => $_GET['cid']));
			info_box($lang['Delete_Category'], $lang['Category_Deleted_Msg'], "forums.php");
		}
	} else {
		$theme->new_file("delete_category", "delete_category.tpl");
		$db2->query("SELECT * FROM `_PREFIX_categories` WHERE `cat_id`!=:cid ORDER BY `cat_id`", array(":cid" => $_GET['cid']));
		
		while($result = $db2->fetch()) {
			$theme->insert_nest("delete_category", "move_to_options", array(
				"CAT_ID" => $result['cat_id'],
				"CAT_NAME" => $result['cat_name']
			));

			$theme->add_nest("delete_category", "move_to_options");
		}
		//
		// Output the page header
		//
		include_once($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("delete_category");

		//
		// Output the page footer
		//
		include_once($root_path . "includes/page_footer.php");
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
			$db2->query("SELECT c.`cat_id`, p.`cat_id`
				AS 'old_cat_id'
				FROM (`_PREFIX_categories` c
				LEFT JOIN `_PREFIX_categories` p
				ON p.`cat_orderby` = (c.`cat_orderby` ".$new_sign." 1))
				WHERE c.`cat_id`=:cid", array(":cid" => $_GET['cid']));

			if($result = $db2->fetch()) {
				if(!(empty($result['cat_id']) || empty($result['old_cat_id']))) {
					$db2->query("UPDATE `_PREFIX_categories` SET `cat_orderby` = (`cat_orderby` ".$new_sign." 1) WHERE `cat_id`=:cid", array(":cid" => $result['cat_id']));
					$db2->query("UPDATE `_PREFIX_categories` SET `cat_orderby` = (`cat_orderby` ".$old_sign." 1) WHERE `cat_id`=:ocid", array(":ocid" => $result['old_cat_id']));
				}
			}
		}
        else if(isset($_GET['fid']))
        {
			$old_sign = ($_GET['move'] == "up") ? "+" : "-";
			$new_sign = ($_GET['move'] == "up") ? "-" : "+";
			$db2->query("SELECT f.`forum_id`, p.`forum_id`
                                 AS 'old_forum_id'
								 FROM (`_PREFIX_forums` f
								 LEFT JOIN `_PREFIX_forums` p
                                 ON p.`forum_orderby` = (f.`forum_orderby` ".$new_sign." 1)
                                 AND p.`forum_cat_id` = f.`forum_cat_id`
                                 AND p.`forum_cat_id` = f.`forum_cat_id`)
								 WHERE f.`forum_id`=:fid", array(":fid" => $_GET['fid']));

			if($result = $db2->fetch()) {
				if(!(empty($result['forum_id']) || empty($result['old_forum_id']))) {
					$db2->query("UPDATE `_PREFIX_forums` SET `forum_orderby` = (`forum_orderby` ".$new_sign." 1) WHERE `forum_id`=:fid", array(":fid" => $result['forum_id']));
					$db2->query("UPDATE `_PREFIX_forums` SET `forum_orderby` = (`forum_orderby` ".$old_sign." 1) WHERE `forum_id`=:ofid", array(":ofid" => $result['old_forum_id']));
				}
			}
		}
	}
	
	Template::setBasePath($root_path . "templates/original/admin/");
	Template::addNamespace("L", $lang);
	$page_master = new Template("manage_forums.tpl");

	$db_cat = $db2->query("SELECT * FROM `_PREFIX_categories` ORDER BY `cat_orderby`");
	while ($category = $db_cat->fetch())
	{
		$category_content = "";

		$forum_count = 0;
		$forum_sql = $db2->query("SELECT * FROM `_PREFIX_forums`
			WHERE `forum_cat_id`=:cid
			AND `forum_type` = 'c'
			ORDER BY `forum_orderby`", array(":cid" => $category['cat_id']));
                                 
		while ($forum = $forum_sql->fetch()) {
			if($forum['forum_redirect_url'] != null) {
				$category_content .= $page_master->renderBlock("redirection_forum", array(
					"FORUM_ID" => $forum['forum_id'],
					"FORUM_NAME" => $forum['forum_name'],
					"FORUM_DESCRIPTION" => $forum['forum_description'],
					"REDIRECTS" => sprintf($lang['X_Hits'], $forum['forum_topics']),
					"PARENT_FORUMS" => $parent_forums
				));
			} else {
				$category_content .= $page_master->renderBlock("regular_forum", array(
					"FORUM_ID" => $forum['forum_id'],
					"FORUM_NAME" => $forum['forum_name'],
					"FORUM_DESCRIPTION" => $forum['forum_description'],
					"TOPICS" => $forum['forum_topics'],
					"POSTS" => $forum['forum_posts'],
					"PARENT_FORUMS" => $parent_forums
				));
			}

			//
			// Generate Sub-forums
			//
            $forum_route[0]['id'] = $forum['forum_id'];
            $forum_route[0]['name'] = $forum['forum_name'];
			_generate_subforums($forum['forum_id'], $forum_route, $page_master, $category_content);

			$forum_count++;
		}

		if($forum_count == 0) {
			$category_content = $page_master->renderBlock("no_forums_in_cat", array());
		} else {
			// Prepend the table header to the contents.
			$table_header = $page_master->renderBlock("forums_table_header", array());
			$category_content = $table_header .	$category_content;
		}

		$page_master->addToBlock("category", array(
			"CAT_ID" => $category['cat_id'],
			"CAT_NAME" => $category['cat_name'],
			"CAT_CONTENTS" => $category_content
		));
	}

	outputPage($page_master);
}

function _generate_subforums($forum_id, $forum_route, $page_master, &$category_content)
{
	global $db2, $theme, $db_prefix ,$lang;

	$subforums_sql = $db2->query("SELECT `forum_id`, `forum_name`, `forum_description`, `forum_redirect_url`, `forum_topics`, `forum_posts` 
		FROM `_PREFIX_forums` 
		WHERE `forum_cat_id`=:fid AND `forum_type` = 'f' ORDER BY `forum_orderby` ASC",
		array(":fid" => $forum_id));

	while($result = $subforums_sql->fetch()) {
		$parent_forums = "";

		for($i = 0; $i < count($forum_route); $i++) {
			$parent_forums .= $page_master->renderBlock("parent_forum", array(
				"SUBFORUM_ID" => $forum_route[$i]['id'],
				"SUBFORUM_NAME" => $forum_route[$i]['name']
			));
		}

		if($result['forum_redirect_url'] != null) {
			$category_content .= $page_master->renderBlock("redirection_forum", array(
				"FORUM_ID" => $result['forum_id'],
				"FORUM_NAME" => $result['forum_name'],
				"FORUM_DESCRIPTION" => $result['forum_description'],
				"REDIRECTS" => sprintf($lang['X_Hits'], $result['forum_topics']),
				"PARENT_FORUMS" => $parent_forums
			));
		} else {
			$category_content .= $page_master->renderBlock("regular_forum", array(
				"FORUM_ID" => $result['forum_id'],
				"FORUM_NAME" => $result['forum_name'],
				"FORUM_DESCRIPTION" => $result['forum_description'],
				"TOPICS" => $result['forum_topics'],
				"POSTS" => $result['forum_posts'],
				"PARENT_FORUMS" => $parent_forums
			));
		}

		$forum_route_count = count($forum_route);

		$forum_route[$forum_route_count]['id'] = $result['forum_id'];
		$forum_route[$forum_route_count]['name'] = $result['forum_name'];
		_generate_subforums($result['forum_id'], $forum_route, $page_master, $category_content);
		unset($forum_route[$forum_route_count]);
	}
	return true;
}

function _generate_category_dropdown($forum_id, $template_name, $prefix, $check_selected = true)
{
	global $db2, $theme, $current_cat_id, $current_cat_type;

	if(isset($_GET['fid'])) {
		$db2->query("SELECT `forum_id`, `forum_name` FROM `_PREFIX_forums`
			WHERE `forum_cat_id`=:forumid AND `forum_type` = 'f' AND `forum_id`!=:fid
			ORDER BY `forum_orderby` DESC",
			array(":forumid" => $forum_id, ":fid" => $_GET['fid']));
	} else {
		$db2->query("SELECT `forum_id`, `forum_name` FROM `_PREFIX_forums`
			WHERE `forum_cat_id`=:fid AND `forum_type` = 'f'
			ORDER BY `forum_orderby` DESC",
			array(":fid" => $forum_id));
	}
	
	while($forum_result = $db2->fetch()) {

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
	global $db2, $db_prefix;

	$db_sub = $db2->query("SELECT `forum_id` FROM `_PREFIX_forums` WHERE `forum_cat_id`=:fid AND `forum_type` = 'f'", array(":fid" => $forum_id));
	
	while($result = $db_sub->fetch()) {
		_delete_subforums($result['forum_id']);
		$db_sub->query("DELETE FROM `_PREFIX_forums` WHERE `forum_id`=:fid", array(":fid" => $result['forum_id']));

		$db_del = $db2->query("SELECT `topic_id` FROM `_PREFIX_topics` WHERE `topic_forum_id`=:fid", array(":fid" => $result['forum_id']));
		
		while($topic_result = $db_del->fetch()) {
			$value = array(":tid" => $topic_result['topic_id']);
			$db2->query("DELETE FROM `_PREFIX_posts` WHERE `post_topic_id`=:tid", $value);
			$db2->query("DELETE FROM `_PREFIX_topics` WHERE `topic_id`=:tid", $value);
		}
	}
	return true;
}
?>
