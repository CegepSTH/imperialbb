<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: page_header.php                                            # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

if(!defined('IN_IBB')) {
	die('Hacking Attempt');
}

$theme ->new_file("page_header", "page_header.tpl");
if(!isset($page_title)) {
    $page_title = $config['site_name'];
} 

if($config['board_offline']) {
	$page_title .= "  (" . $lang['Offline'] . ")";
}

if($user['user_level'] == 5) {
	$admin_link = '<a href="admin/">'.$lang['Administration_Panel'].'</a>';
} else {
        $admin_link = '';
}

$theme->replace_tags("page_header", array(
	"TITLE"      => $page_title,
	"SITE_NAME"  => $config['site_name'],
	"SITE_DESC"  => $config['site_desc'],
	"USERNAME"   => $user['username'],
	"ADMIN_LINK" => $admin_link,
));
$theme->output("page_header");

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
