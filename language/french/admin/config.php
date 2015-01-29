<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: config.php                                                 # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

if(!defined("IN_IBB")) {
        die("Hacking Attempt");
}

//
// General Config
//
$lang['General_Configuration'] = "General Configuration";
$lang['site_name'] = "Site Name";
$lang['site_desc'] = "Site Description";
$lang['url'] = "URL";
$lang['footer'] = "Footer";
$lang['register_auth_type'] = "Require Email Validation";
$lang['timezone'] = "Timezone";

//
// Offline Config
//
$lang['Offline_Configuration'] = "Offline Configuration";
$lang['board_offline'] = "Board Offline";
$lang['offline_message'] = "Offline Message";

//
// Post Config
//
$lang['Post_Configuration'] = "Post Configuration";
$lang['html_enabled'] = "HTML Enabled";
$lang['bbcode_enabled'] = "BBCode Enabled";
$lang['smilies_enabled'] = "Smilies Enabled";
$lang['smilies_url'] = "Smilies URL";
$lang['allow_vote_after_results'] = "Allow voting after viewing poll results";

//
// Avatar Config
//
$lang['Avatar_Configuration'] = "Avatar Configuration";
$lang['allow_uploaded_avatar'] = "Allow Uploaded Avatar";
$lang['allow_remote_avatar'] = "Allow Remote Avatar";
$lang['avatar_upload_dir'] = "Avatar Upload Directory";
$lang['avatar_max_upload_size'] = "Max avatar Upload Size (in bytes)";
$lang['avatar_max_upload_height'] = "Max Avatar Upload Height";
$lang['avatar_max_upload_width'] = "Max Avatar Upload Width";
$lang['avatar_upload_mime_types'] = "Avatar Upload Mime Types";

//
// Email Config
//
$lang['Email_Configuration'] = "Email Configuration";
$lang['admin_email'] = "Admin Email Address";
$lang['use_smtp'] = "Use SMTP To Send Email";
$lang['smtp_host'] = "SMTP Host";
$lang['smtp_user'] = "SMTP Username";
$lang['smtp_pass'] = "SMTP Password";

//
// FTP Config
//
$lang['FTP_Configuration'] = "FTP Configuration";
$lang['ftp_user'] = "FTP Username";
$lang['ftp_pass'] = "FTP Password";
$lang['ftp_path'] = "FTP Path";

//
// Template Config
//
$lang['Template_Configuration'] = "Template Configuration";
$lang['default_template'] = "Default Template";

//
// Language Config
//
$lang['Language_Configuration'] = "Language Configuration";
$lang['default_language'] = "Default Language";

//
// Pagination Config
$lang['topics_per_page'] = "Topics Per Page";
$lang['posts_per_page'] = "Posts Per Page";
$lang['members_per_page'] = "Members Per Page";
$lang['pm_per_page'] = "PM's Per Page";
$lang['general_per_page'] = "General Per Page";
$lang['paginate_pernum'] = "Paginate Pernum";

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
