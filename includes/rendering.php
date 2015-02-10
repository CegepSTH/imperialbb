<?php

function getAdminMenuDefinition() {
	global $lang;

/* Usage :
"Block name" => array(
"Section 1" => "Section 1 URL",
"Section 2" => "Section 2 URL",
"Section 3" => "Section 3 URL"),
*/

	$menu = array(
		$lang['General_Admin'] => array(
			$lang['Configuration'] => "config.php",
		),
		
		$lang['Forum_Admin'] => array(
			$lang['Manage_Forums'] => "forums.php"
		),
		
		$lang['Usergroups'] => array(
			$lang['Managment'] => "usergroups.php",
		),
		
		$lang['User_Managment'] => array(
			$lang['Edit_User'] => "users.php?func=search",
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
			$lang['Manage_Templates'] => "template.php"
		)
	);

	return $menu;
}

function renderAdminMenu() {
	global $root_path;

	$sidebar_template = new Template("sidebar.tpl");

	$menu_def = getAdminMenuDefinition();
	foreach($menu_def as $section_name => $section_links) {
		$links_html = "";
		foreach($section_links as $name => $link) {
			$single_link_block = $sidebar_template->renderBlock("link",	array(
				"LINK" => $link,
				"NAME" => $name
			));

			$links_html .= $single_link_block->output;
		}

		$sidebar_template->addToBlock("link_block", array(
			"SECTION" => $section_name,
			"LINKS" => $links_html
		));
	}

	return $sidebar_template->render();
}

/**
 * outputPage Outputs the page master with the specified master layout.
 * 
 * @param $page_master The page master to output.
 * @param $page_title The title of the page (optional).
 */
function outputPage($page_master, $page_title = null) {
	global $page_gen_start, $config, $lang, $user;

	$page_gen_stop = explode(' ',microtime());
	$page_gen_stop = $page_gen_stop[0] + $page_gen_stop[1];

	$generation_time = round($page_gen_stop - $page_gen_start, 3);

	if(is_null($page_title)) {
		$page_title = $config['site_name'];
	}

	if($config['board_offline']) {
		$page_title .= "  (" . $lang['Offline'] . ")";
	}

	$layout_master = new Template("layout_master.tpl");
	$layout_master->setVars(array(
		"TITLE"      => $page_title,
		"SITE_NAME"  => $config['site_name'],
		"USERNAME"   => $user['username'],
		"GENERATION_TIME" => sprintf($lang['Page_Generated_In_X_Seconds'], $generation_time)
	));

	$layout_master->addToTag("content", $page_master);

	if(defined("IN_ADMIN")) {
		$admin_menu = renderAdminMenu();
		$layout_master->addToTag("sidebar", $admin_menu);
	} else {
		if($user['user_id'] > 0) {
			$admin_link_header = "";
			$admin_link_footer = "";
			if($user['user_level'] == 5) {
				$admin_link_header = $layout_master->renderBlock("navh_admin_link", array());
				$admin_link_footer = $layout_master->renderBlock("navf_admin_link", array());
			}

			$layout_master->addToBlock("navh_logged_in", array("ADMIN_LINK" => $admin_link_header));
			$layout_master->addToBlock("navf_logged_in", array("ADMIN_LINK" => $admin_link_footer));
			$layout_master->addToBlock("name_logged_in", array("USERNAME" => $user['username']));
		} else {
			$layout_master->addToBlock("navh_guest", array());
			$layout_master->addToBlock("navf_guest", array());
			$layout_master->addToBlock("name_guest", array());
		}
	}

	echo($layout_master->render());
}

?>

