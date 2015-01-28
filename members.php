<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: members.php                                                # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

define("IN_IBB", 1);
$root_path = "./";
include($root_path . "includes/common.php");
$language->add_file("members");
$theme->new_file("memberslist", "memberslist.tpl");

$member_count_query = $db2->query("SELECT count(`user_id`) AS 'member_count' FROM `_PREFIX_users`");

$member_count_result = $member_count_query->fetch();
$member_count = (intval($member_count_result['member_count']) - 1);

$pagination = $pp->paginate($member_count, $config['members_per_page']);

$theme->replace_tags("memberslist", array(
	"PAGINATION" => $pagination
));

$sql = $db2->query("SELECT *
	FROM `_PREFIX_users`
	WHERE `user_id` > '0'
	ORDER BY `user_id` ASC
	LIMIT " . $pp->limit . ""
);

while($result = $sql->fetch()) 
{
    $membername = '';
    $membername = format_membername($result['user_rank'],$result['user_id'],$result['username']);
	$theme->insert_nest("memberslist", "member_row", array(
		"ID" => $result['user_id'],
		"USERNAME" => $membername,
		"USER" => $result['username'],
		"POSTS" => $result['user_posts'],
		"DATE_JOINED" => create_date("D d M Y", $result['user_date_joined'])
	));
	$theme->add_nest("memberslist", "member_row");
}


$page_title = $config['site_name'] . " &raquo; " . $lang['Members_List'];

//
// Output the page header
//
include($root_path . "includes/page_header.php");

//
// Output the main page
//
$theme->output("memberslist");

//
// Output the page footer
//
include($root_path . "includes/page_footer.php");

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
