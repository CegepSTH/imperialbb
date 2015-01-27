<?php

/**********************************************************
*
*			admin/ranks.php
*
*		ImperialBB 2.X.X - By Nate and James
*
*		     (C) The IBB Group
*
***********************************************************/

define("IN_IBB", 1);
define("IN_ADMIN", 1);

$root_path = "../";
require_once($root_path . "includes/common.php");

$language->add_file("admin/ranks");

if(!isset($_GET['func'])) $_GET['func'] = "";
if($_GET['func'] == "add")
{
	if(isset($_POST['Submit']))
	{
		if(!isset($_POST['bold'])) $_POST['bold'] = "0";
		if(!isset($_POST['underline'])) $_POST['underline'] = "0";
		if(!isset($_POST['italics'])) $_POST['italics'] = "0";

		// Sort the rank orderby
		if(!isset($_POST['display_rank'])) {
			$rank_orderby = "0";
		} else {
			$query = $db2->query("SELECT `rank_orderby` FROM `_PREFIX_ranks` ORDER BY `rank_orderby` DESC LIMIT 1");
			
			if($result = $db2->fetch()) {
				$rank_orderby = ($result['rank_orderby'] + 1);
			} else {
				$rank_orderby = "1";
			}
		}
		
        if(isset($_POST['special_rank'])) {
        	$special_rank = "1";
        	$minimum_posts = "0";
        } else {
        	$special_rank = "0";
        	$minimum_posts = $_POST['minimum_posts'];
        }
        
		$values = array(":name" => $_POST['name'], ":color" => $_POST['color'], ":image" => $_POST['image'], ":bold" => $_POST['bold'],
			":underline" => $_POST['underline'], ":italics" => $_POST['italics'], ":rorderby" => $rank_orderby, ":srank" => $special_rank, 
			":minposts" => $special_rank);
		
		$db2->query("INSERT INTO `_PREFIX_ranks` (`rank_name`, `rank_color`, `rank_image`, `rank_bold`, `rank_underline`, `rank_italics`, `rank_orderby`, `rank_special`, `rank_minimum_posts`) 
		VALUES (:name, :color, :image, :bold, :underline, :italics, :rorderby, :srank, :minposts)", $values);
		
		info_box($lang['Create_New_Rank'], $lang['Rank_Created_Msg'], "ranks.php");
	}
	else
	{
		$theme->new_file("add_rank", "edit_rank.tpl");

		$theme->replace_tags("add_rank", array(
			"ACTION" => $lang['Add_Rank'],
			"NAME" => "",
			"COLOR" => "#000000",
			"IMAGE" => "",
			"BOLD" => "",
			"UNDERLINE" => "",
			"ITALICS" => "",
			"DISPLAY_RANK" => "checked=\"checked\"",
			"SPECIAL_RANK" => "",
			"MINIMUM_POSTS" => 0
		));

		//
		// Output the page header
		//
		include_once($root_path . "includes/page_header.php");

		//
		// Output the main page
		//
		$theme->output("add_rank");

		//
		// Output the page footer
		//
		include_once($root_path . "includes/page_footer.php");
	}
}
else if($_GET['func'] == "edit")
{
	if(isset($_POST['Submit']))
	{
		if(!isset($_POST['bold'])) $_POST['bold'] = "0";
		if(!isset($_POST['underline'])) $_POST['underline'] = "0";
		if(!isset($_POST['italics'])) $_POST['italics'] = "0";
		if(!isset($_POST['special_rank'])) $_POST['special_rank'] = "0";
        if(!isset($_POST['minimum_posts'])) $_POST['minimum_posts'] = "0";

		// Sort the rank orderby
		$query = $db2->query("SELECT r.`rank_orderby`, p.`rank_orderby` AS 'old_rank_orderby'
			FROM (`_PREFIX_ranks` r
			LEFT JOIN `_PREFIX_ranks` p ON p.`rank_id`=:rid)
			ORDER BY `rank_orderby` DESC LIMIT 1", array(":rid" => $_GET['id']));
		$result = $db2->fetch();

		if(!isset($_POST['display_rank'])) {
			$rank_orderby = "0";
		}
		else {
			if($result['old_rank_orderby'] > 0) {
				$rank_orderby = $result['old_rank_orderby'];
			} else {
				if(!empty($result['rank_orderby'])) {
					$rank_orderby = ($result['rank_orderby'] + 1);
				} else {
					$rank_orderby = "1";
				}
			}
		}

		if($rank_orderby != $result['old_rank_orderby'] && $result['old_rank_orderby'] != 0) {
			$db2->query("UPDATE `_PREFIX_ranks` SET `rank_orderby` = (`rank_orderby` - 1) 
				WHERE `rank_orderby` > :orank", array(":orank" => $result['old_rank_orderby']));
		}

		$values = array(":name" => $_POST['name'], ":color" => $_POST['color'], ":image" => $_POST['image'], 
			":bold" => $_POST['bold'], ":underline" => $_POST['underline'], ":italics" => $_POST['italics'],
			":rorderby" => $rank_orderby, ":rspecial" => $_POST['special_rank'], ":rminposts" => $_POST['minimum_posts'], ":rid" => $_GET['id']);
		
		$db2->query("UPDATE `_PREFIX_ranks` SET `rank_name`=:name, `rank_color`=:color, `rank_image`=:image, `rank_bold`=:bold, `rank_underline`=:underline,
			`rank_italics`=:italics, `rank_orderby`=:rorderby, `rank_special`=:rspecial, `rank_minimum_posts`=:rminposts 
			WHERE `rank_id`=:rid", $values);
			
		info_box($lang['Edit_Rank'], $lang['Rank_Updated_Msg'], "ranks.php");
	}
	else
	{
		$sql = $db2->query ("SELECT * FROM `_PREFIX_ranks` WHERE `rank_id`=:rid LIMIT 1", array(":rid" => $_GET['id']));
		if ($result = $db2->fetch()) {
			$theme->new_file("edit_rank", "edit_rank.tpl");
			$theme->replace_tags("edit_rank", array(
				"ACTION" => $lang['Edit_Rank'],
				"NAME" => $result['rank_name'],
				"COLOR" => $result['rank_color'],
				"IMAGE" => $result['rank_image'],
				"BOLD" => ($result['rank_bold'] == 1) ? 'checked="checked"' : '',
				"UNDERLINE" => ($result['rank_underline'] == 1) ? 'checked="checked"' : '',
				"ITALICS" => ($result['rank_italics'] == 1) ? 'checked="checked"' : '',
				"DISPLAY_RANK" => ($result['rank_orderby'] > 0) ? 'checked="checked"' : '',
				"SPECIAL_RANK" => ($result['rank_special'] == 1) ? 'checked="checked"' : '',
				"MINIMUM_POSTS" => $result['rank_minimum_posts']
			));
			//
			// Output the page header
			//
			include_once($root_path . "includes/page_header.php");

			//
			// Output the main page
			//
			$theme->output("edit_rank");

			//
			// Output the page footer
			//
			include_once($root_path . "includes/page_footer.php");
		} else {
			error_msg($lang['Error'], $lang['Invalid_Rank_Id']);
		}
	}
}
else if($_GET['func'] == "delete")
{
	$db2->query("DELETE FROM `_PREFIX_ranks` WHERE `rank_id`=:rid", array(":rid" => $_GET['id']));
	info_box($lang['Delete_Rank'], $lang['Rank_Deleted_Msg'], "ranks.php");
}
else
{
	if(isset($_GET['move']))
	{
		if(!isset($_GET['id'])) {
			error_msg($lang['Error'], $lang['Invalid_Rank_Id']);
		}
		
		$old_sign = ($_GET['move'] == "up") ? "+" : "-";
		$new_sign = ($_GET['move'] == "up") ? "-" : "+";
		$query = $db2->query("SELECT r.`rank_id`, p.`rank_id` AS 'old_rank_id'
							FROM (`_PREFIX_ranks` r
							LEFT JOIN `_PREFIX_ranks` p ON p.`rank_orderby` = (r.`rank_orderby` ".$new_sign." 1) AND p.`rank_orderby` > 0)
							WHERE r.`rank_id`=:rid AND r.`rank_orderby` > 0",
							array(":rid" => $_GET['id']));

		if($result = $db2->fetch()) {
			if(!(empty($result['rank_id']) || empty($result['old_rank_id']))) {
				$db2->query("UPDATE `_PREFIX_ranks` SET `rank_orderby` = (`rank_orderby` ".$new_sign." 1) WHERE `rank_id`=:rid", 
					array(":rid" => $result['rank_id']));
				$db2->query("UPDATE `_PREFIX_ranks` SET `rank_orderby` = (`rank_orderby` ".$old_sign." 1) WHERE `rank_id`=:orid", 
					array(":orid" => $result['old_rank_id']));
			}
		}
	}
	
	$theme->new_file("ranks", "manage_ranks.tpl");
	$db2->query("SELECT * FROM `_PREFIX_ranks`
		ORDER BY `rank_orderby`, `rank_id`");
						
	while ($result = $db2->fetch()) {
		$rank_style = "color:".$result['rank_color'].";";
		
		if($result['rank_bold'] == 1) {
			$rank_style .= " font-weight:bold;";
		} else {
			$rank_style .= "";
		}
		
		if($result['rank_underline'] == 1) {
			$rank_style .= " text-decoration: underline;";
		} else {
			$rank_style .= "";
		}
		
		if($result['rank_italics'] == 1) {
			$rank_style .= " font-style:italic;";
		} else {
			$rank_style .= "";
		}
		
		$rank_style .= "";

		$nest_prefix = ($result['rank_orderby'] > 0) ? "displayed" : "not_displayed";

		$theme->insert_nest("ranks", $nest_prefix . "_rank_row", array(
			"ID" => $result['rank_id'],
			"NAME" => $result['rank_name'],
			"MINIMUM_POSTS" => $result['rank_minimum_posts'],
			"RANK_STYLE" => $rank_style,
			"COLOR" => $result['rank_color'],
			"BOLD" => ($result['rank_bold'] == "1") ? "CHECKED" : "",
			"UNDERLINE" => ($result['rank_underline'] == "1") ? "CHECKED" : "",
			"ITALICS" => ($result['rank_italics'] == "1") ? "CHECKED" : ""
		));
		$theme->add_nest("ranks", $nest_prefix . "_rank_row");
	}
	//
	// Output the page header
	//
	include_once($root_path . "includes/page_header.php");

	//
	// Output the main page
	//
	$theme->output("ranks");

	//
	// Output the page footer
	//
	include_once($root_path . "includes/page_footer.php");
}

?>
