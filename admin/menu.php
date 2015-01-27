<?php

/**********************************************************
*
*			admin/menu.php
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

/* Usage :
"Block name" => array(
"Section 1" => "Section 1 URL",
"Section 2" => "Section 2 URL",
"Section 3" => "Section 3 URL"),
*/

$menu=array(
$lang['General_Admin'] => array(
	$lang['Configuration'] => "config.php",
),

$lang['Forum_Admin'] => array(
	$lang['Manage_Forums'] => "forums.php"
),

$lang['Usergroups'] => array(
	$lang['Managment'] => "usergroups.php",
	$lang['Permissions'] => "usergroups.php?func=permissions"
),

$lang['User_Managment'] => array(
	$lang['Edit_User'] => "users.php?func=edit",
	$lang['Delete_User'] => "users.php?func=delete",
	$lang['Add_Rank'] => "ranks.php?func=add",
	$lang['Manage_Ranks'] => "ranks.php"
),

$lang['BBCode_And_Smilies'] => array(
	$lang['Add_Smily'] => "smilies.php?func=add",
	$lang['Manage_Smilies'] => "smilies.php",
	$lang['BBCode'] => "bbcode.php"
),

$lang['Languages'] => array(
	$lang['Add_Language'] => "language.php?func=add",
	$lang['Manage_Languages'] => "language.php"
),

$lang['Templates'] => array(
	$lang['Add_Template'] => "template.php?func=add",
	$lang['Manage_Templates'] => "template.php"
));

function display_menu($m) {
	global $theme;

	foreach ($m as $section => $link) {
		if (!is_array($link)) {
			if($link == "") $link = "main.php";

			$theme->insert_nest("menu", "link_block/link", array(
				"LINK" => $link,
				"NAME" => $section
			));
			$theme->add_nest("menu", "link_block/link");
		} else {
			$theme->insert_nest("menu", "link_block", array(
				"SECTION" => $section
			));
			display_menu($link, $theme);
			$theme->add_nest("menu", "link_block");
		}
    }
}

$theme->new_file("menuheader", "menu_header.tpl");
$theme->replace_tags("menuheader", array(
	"JSCRIPTS_DIR"   => "./../jscripts",
));
$theme->new_file("menu", "menu.tpl");
display_menu($menu);
$theme->new_file("menufooter", "menu_footer.tpl");

$theme->output("menuheader");
$theme->output("menu");
$theme->output("menufooter");
?>
