<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: page_footer.php                                       # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

if(!defined("IN_IBB"))
{
	die("Hacking Attempt");
}

$theme->new_file("page_footer", "page_footer.tpl");
// Wrap up the timer
$page_gen_stop = explode(' ',microtime());
$page_gen_stop = $page_gen_stop[0] + $page_gen_stop[1];
// Fetch the total parse time
$generation_time = round($page_gen_stop - $page_gen_start, 3);
// Setup the php parsing time
$phptime = round(($generation_time - $db->querytime), 3);
// Setup the SQL and PHP percentage stuff
$percentphp = number_format((($phptime/$generation_time)*100), 2).'%';
$percentsql = number_format((($db->querytime/$generation_time)*100), 2).'%';
if($user['user_level'] == 5)
{
        $admin_link = '&nbsp;&ndash;&nbsp;<a href="admin/">'.$lang['Administration_Panel'].'</a>';
}
else
{
        $admin_link = '';
}

// Insert the data
$theme->replace_tags("page_footer", array(
    "ADMIN_LINK" => $admin_link,
	"FOOTER" => $config['footer'],
	"GENERATION_TIME" => sprintf($lang['Page_Generated_In_X_Seconds'], $generation_time),
	"QUERY_COUNT" => sprintf($lang['Query_Count'], $db->query_count),
    "SQLTIME" => sprintf($lang['sqltime'], $db->querytime, $percentsql),
    "PHPTIME" => sprintf($lang['phptime'], $phptime, $percentphp),
));
// Output the footer
$theme->output("page_footer");
// Wrap up the the DB stuff
$db->close();

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*======================================================================*/
?>
