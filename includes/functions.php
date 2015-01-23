<?php
/*======================================================================*\
|| #################################################################### ||
|| #  				  Imperial Bulletin Board v2.x                    # ||
|| # ---------------------------------------------------------------- # ||
|| #  For licence, version amd changelog questions or concerns,       # ||
|| #  navigate to the docs/ folder or visit the forums at the		  # ||
|| #  website, http://www.imperialbb.com/forums. with your questions. # ||
|| # ---------------------------------------------------------------- # ||
|| # Name: functions.php                                              # ||
|| # ---------------------------------------------------------------- # ||
|| #                "Copyright © 2006 M-ka Network"                   # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

if(!defined("IN_IBB"))
{
        die("Hacking Attempt");
}

//===========================================
// @Name: function info_box();
// @Desc: Handles redirects with message
//===========================================
function info_box($name, $message, $link)
{
	global $config, $user, $theme, $lang, $page_gen_start, $db, $root_path;
	$theme->new_file("info_box", "info_box.tpl", "");
	$theme->replace_tags("info_box", array(
		"LINK"      => $link,
		"TITLE"     => $name,
		"MESSAGE"   => $message
	));
	include($root_path . "includes/page_header.php");
	$theme->output("info_box");
	include($root_path . "includes/page_footer.php");
	exit();
}

//===========================================
// @name: function error_msg();
// @Desc: Handles error message displays
//===========================================
function error_msg($name, $message)
{
	global $config, $user, $theme, $lang, $page_gen_start, $db, $root_path;
	if(!isset($theme))
	{
		die("<b>".$name."</b><br /><br />".$message."");
	}
	$theme->new_file("error_msg", "error_msg.tpl", "");
	$theme->replace_tags("error_msg", array(
		"TITLE"     => $name,
		"MESSAGE"   => $message
	));
	include($root_path. "includes/page_header.php");
	$theme->output("error_msg");
	include($root_path . "includes/page_footer.php");
	exit();
}

/*function error_msg($name = '', $message = '', $link = '', $timeout = 3, $echo = TRUE)
{
	global $config, $user, $theme, $lang, $page_gen_start, $db, $root_path;

	if(!isset($theme))
	{
		die("<b>$name</b><br /><br />$message");
	}

	$timeout = $timeout * 1000;
	$link 	 = str_replace('&amp;', '&', $link);

	if(empty($name))
	{
		$name = '';
	}

	if(empty($message))
	{
		$message = '';
	}

	if(empty($link))
	{
		$link = FALSE;
	}
	elseif(isset($link) && $link < 0)
	{
		if($echo)
		{
			echo '<script type="text/javascript">window.setTimeout("history.go('.$link.')", '.$timeout.');</script>';
		}
		else
		{
			return '<script type="text/javascript">window.setTimeout("history.go('.$link.')", '.$timeout.');</script>';
		}
	}
	else
	{
		if($echo)
		{
			echo '<script type="text/javascript">window.setTimeout("document.location = \''.$link.'\';", '.$timeout.');</script>';
		}
		else
		{
			return '<script type="text/javascript">window.setTimeout("document.location = \''.$link.'\';", '.$timeout.');</script>';
		}
	}

	$theme->new_file("error_msg", "error_msg.tpl", "");
	$theme->replace_tags("error_msg", array(
		"TITLE" => $name,
		"MESSAGE" => $message
	));

	// Output the page header
	include($root_path. "includes/page_header.php");

	// Output the main page
	$theme->output("error_msg");

	// Output the page footer
	include($root_path . "includes/page_footer.php");

	// Exit the script
	exit();
}*/

//===========================================
// @Name: function confirm_msg();
// @Desc: Handles confirmations with redirect
//===========================================
function confirm_msg($name, $message, $url, $no_url = '', $values = '')
{
	global $config, $user, $theme, $lang, $page_gen_start, $db, $root_path;
	$theme->new_file("confirm_msg", "confirm_msg.tpl");
	$theme->replace_tags("confirm_msg", array(
		"TITLE"     => $name,
		"MESSAGE"   => $message,
		"URL"       => $url,
		"NO_URL"    => (strlen($no_url) > 0) ? $no_url : "index.php"
	));
	if(is_array($values))
	{
		foreach($values as $name => $value)
		{
			$theme->replace_tags("confirm_msg", array(
				"NAME"  => $name,
				"VALUE" => $value
			));
		}
	}
	include($root_path . "includes/page_header.php");
	$theme->output("confirm_msg");
	include($root_path . "includes/page_footer.php");
}

//===========================================
// @Name: function create_date();
// @Desc: Handles date and time formatting
//===========================================
function create_date($format, $stamp)
{
        global $config;
        return (gmdate($format, $stamp + (3600 * $config['timezone'])));
}

//===========================================
// @Name: function ibb_censor();
// @Desc: Handles forum censoring methods
//===========================================
function ibb_censor($text)
{
	global $config;
	if(!$config['censor_enabled'] or !$config['censor_words'])
    {
		return $text;
	}
	$censored_words = split(" ",$config['censor_words']);
	foreach($censored_words as $index => $word)
    {
		$length = strlen($word);
        $replace = '';
		for($x = 1; $x <= $length; $x++)
        {
			$replace .= $config['censor_replace'];
		}
		$text = preg_replace("|".$word."|i",$replace,$text);
	}
	return $text;
}

//===========================================
// @Name: function format_text();
// @Desc: Handles bbcode,smilie and censor methods
// @Usage: format_text($text[, true[, true[, true]]]);
//===========================================
function format_text($text, $insert_bbcode=true, $insert_smilies=true, $remove_html=true, $censor_text=true)
{
	global $db2, $db_prefix, $root_path, $lang;

	if($remove_html)
	{
		$text = ereg_replace("<", "&lt;", $text);
		$text = ereg_replace(">", "&gt;", $text);
		$text = ereg_replace(chr(34), "&quot;", $text);
	}

	if($insert_smilies)
	{
		$sql = $db2->query("SELECT `smilie_code`, `smilie_url` FROM `".$db_prefix."smilies`");
		while ($row = $sql->fetch())
        {
			$text = str_replace($row['smilie_code'],"<img src=\"" . $root_path . "images/smilies/".$row['smilie_url']."\" border=\"0\">", $text);
		}
	}

    if($censor_text)
    {
        $text = ibb_censor($text);
    }

	if($insert_bbcode)
	{
		// [b]Bold Text[/b]
		$bb_search[] = "#\[b\]((\[(?!/b\])|(\n|.))*?)\[/b\]#is";
		$bb_replace[] = "<b>\\1</b>";

		// [u]Underlined Text[/u]
		$bb_search[] = "#\[u\]((\[(?!/u\])|(\n|.))*?)\[/u\]#is";
		$bb_replace[] = "<u>\\1</u>";

		// [i]Italics Text[/i]
		$bb_search[] = "#\[i\]((\[(?!/i\])|(\n|.))*?)\[/i\]#is";
		$bb_replace[] = "<i>\\1</i>";

		// [hr]
		$bb_search[] = "#\[hr\]#is";
		$bb_replace[] = "<hr />";

		// [img]http://www.domain.com[/img]
		$bb_search[] = "#\[img\](http://)([\w\#$%&~/.\-;:=,?@\[\]+]*?)\[/img\]#is";
		$bb_replace[] = "<img src=\"\\1\\2\" />";

		// [url=http://www.domain.com]Domain[/url] (With xxxx:// prefix)
		$bb_search[] = "#\[url=(([\w]+?://)?[\#$\w%~/&.\-;:=,?@+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
		$bb_replace[] = "<a href=\"\\1\" target=\"blank\">\\3</a>";

		// [url=www.domain.com]Domain[/url] (Without xxxx:// prefix)
		$bb_search[] = "#\[url=([\w\#$%&~/.\-;:=,?@+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
		$bb_replace[] = "<a href=\"http://\\1\" target=\"blank\">\\2</a>";

		// [url]http://www.domain.com[/url] (With xxxx:// prefix)
		$bb_search[] = "#\[url\](.+://)((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\[/url\]#is";
		$bb_replace[] = "<a href=\"\\1\\2\" target=\"blank\">\\2</a>";
	
		// [url]www.domain.com[/url] (Without xxxx:// prefix)
		$bb_search[] = "#\[url\]((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\[/url\]#is";
		$bb_replace[] = "<a href=\"http://\\1\" target=\"blank\">\\1</a>";

		// [color=xxx]Text[/color]
		$bb_search[] = "#\[color=([a-zA-Z]+|\#([0-9a-fA-F]{3}|[0-9a-fA-F]{6}))\]((\[(?!/color\])|(\n|.))*?)\[/color\]#is";
		$bb_replace[] = "<span style=\"color:\\1\">\\3</span>";
	
		// [size=xxx]Text[/size]
		$bb_search[] = "#\[size=([1-4](\.5)?)\]((\[(?!/size\])|(\n|.))*?)\[/size\]#is";
		$bb_replace[] = "<span style=\"font-size:\\1ex\">\\3</span>";
	
		// [align=xxx]Text[/align]
		$bb_search[] = "#\[align=(left|center|right)\]((\[(?!/align\])|(\n|.))*?)\[/align\]#is";
		$bb_replace[] = "<div align=\"\\1\">\\2</div>";

		// [quote]Text[/quote]
		$bb_search[] = "#\[quote\]((\[(?!/quote\])|(\n|.))*?)\[/quote\]#is";
		$bb_replace[] = "<table width=\"90%\" align=\"center\" class=\"quotetable\"><tr><td width=\"100%\" height=\"25\"><b>".$lang['Quote']."</b></td></tr><tr><td>\\1</td></table>";
	
		// [quote=xxx]Text[/quote]
		$bb_search[] = "#\[quote=([\w\#$%&~/.\-;:=,?@\r\n \[\]\(\)\?+]*?)\]((\[(?!/quote\])|(\n|.))*?)\[/quote\]#is";
		$bb_replace[] = "<table width=\"90%\" align=\"center\" class=\"quotetable\"><tr><td width=\"100%\" height=\"25\"><b>".$lang['Quote']."</b>&nbsp;&nbsp;Username: \\1</td></tr><tr><td>\\2</td></table>";
	
		// [code]Text[/code]
		$bb_search[] = "#\[code\]((\[(?!/code\])|(\n|.))*?)\[/code\]#is";
		$bb_replace[] = "<table width=\"90%\" align=\"center\" class=\"quotetable\"><tr><td width=\"100%\" height=\"25\"><b>".$lang['Code']."</b></td></tr><tr><td>\\1</td></table>";

		// [code=zzz]Text[/code]
		$bb_search[] = "#\[code=([\w\#$%&~/.\-;:=,?@\r\n \[\]\(\)\?+]*?)\]((\[(?!/code\])|(\n|.))*?)\[/code\]#is";
		$bb_replace[] = "<table width=\"90%\" align=\"center\" class=\"quotetable\"><tr><td width=\"100%\" height=\"25\"><b>".$lang['Code']."</b>&nbsp;&nbsp;Username: \\1</td></tr><tr><td>\\2</td></table>";

		$text = preg_replace($bb_search, $bb_replace, $text);
		$text = eregi_replace('
        ', '<br />', $text);
	}
	return $text;
}

//===========================================
// @Name: function insertsmilies();
// @Desc: Handles forum Smilie methods
//===========================================
function insertsmilies($post)
{
    global $db2, $db_prefix, $root_path;
    $sql = $db2->query("SELECT `smilie_code`, `smilie_url` FROM `".$db_prefix."smilies`");
    while ($row = $sql->fetch())
    {
        $post = str_replace($row['smilie_code'],"<img src=\"" . $root_path . "images/smilies/".$row['smilie_url']."\" border=\"0\">",$post);
    }
    return $post;
}

//===========================================
// @Name: function bbcode();
// @Desc: Handles BBCode methods
//===========================================
function bbcode($post, $change_html = true)
{
    global $db, $lang;
    // Change HTML to none HTML //
    if($change_html)
    {
            $post = eregi_replace("<", "&lt;", $post);
            $post = eregi_replace(">", "&gt;", $post);
    }

    // [b]Bold Text[/b]
    $bb_search[] = "#\[b\]([\w\#$%&~/.\-;:=,?@\r\n \[\]+]*?)\[/b\]#is";
    $bb_replace[] = "<b>\\1</b>";

    // [u]Underlined Text[/u]
    $bb_search[] = "#\[u\]([\w\#$%&~/.\-;:=,?@\r\n \[\]+]*?)\[/u\]#is";
    $bb_replace[] = "<u>\\1</u>";

    // [i]Italics Text[/i]
    $bb_search[] = "#\[i\]([\w\#$%&~/.\-;:=,?@\r\n \[\]+]*?)\[/i\]#is";
    $bb_replace[] = "<i>\\1</i>";

    // [hr]
    $bb_search[] = "#\[hr\]#is";
    $bb_replace[] = "<hr />";

// [img]http://www.domain.com[/img]
    $bb_search[] = "#\[img\](.+://)((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\[/img\]#is";
    $bb_replace[] = "<img src=\"\\1\\2\" />";

    // [url=http://www.domain.com]Domain[/url] (With xxxx:// prefix)
    $bb_search[] = "#\[url=(([\w]+?://)?[\#$\w%~/&.\-;:=,?@+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
    $bb_replace[] = "<a href=\"\\1\" target=\"blank\">\\3</a>";

    // [url=www.domain.com]Domain[/url] (Without xxxx:// prefix)
    $bb_search[] = "#\[url=([\w\#$%&~/.\-;:=,?@+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
    $bb_replace[] = "<a href=\"http://\\1\" target=\"blank\">\\2</a>";

    // [url]http://www.domain.com[/url] (With xxxx:// prefix)
    $bb_search[] = "#\[url\](.+://)((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\[/url\]#is";
    $bb_replace[] = "<a href=\"\\1\\2\" target=\"blank\">\\2</a>";

    // [url]www.domain.com[/url] (Without xxxx:// prefix)
    $bb_search[] = "#\[url\]((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\[/url\]#is";
    $bb_replace[] = "<a href=\"http://\\1\" target=\"blank\">\\1</a>";

    // [color=xxx]Text[/color]
    $bb_search[] = "#\[color=([a-zA-Z]+|\#([0-9a-fA-F]{3}|[0-9a-fA-F]{6}))\]((\[(?!/color\])|(\n|.))*?)\[/color\]#is";
    $bb_replace[] = "<span style=\"color:\\1\">\\3</span>";

    // [size=xxx]Text[/size]
    $bb_search[] = "#\[size=([1-4](\.5)?)\]((\[(?!/size\])|(\n|.))*?)\[/size\]#is";
    $bb_replace[] = "<span style=\"font-size:\\1ex\">\\3</span>";

    // [align=xxx]Text[/align]
    $bb_search[] = "#\[align=(left|center|right)\]((\[(?!/align\])|(\n|.))*?)\[/align\]#is";
    $bb_replace[] = "<div align=\"\\1\">\\2</div>";

    // [quote]Text[/quote]
    $bb_search[] = "#\[quote\]((\[(?!/quote\])|(\n|.))*?)\[/quote\]#is";
    $bb_replace[] = "<table width=\"90%\" align=\"center\" class=\"quotetable\"><tr><td width=\"100%\" height=\"25\"><b>".$lang['Quote']."</b></td></tr><tr><td>\\1</td></table>";

    // [quote=xxx]Text[/quote]
    $bb_search[] = "#\[quote=([\w\#$%&~/.\-;:=,?@\r\n \[\]\(\)\?+]*?)\]((\[(?!/quote\])|(\n|.))*?)\[/quote\]#is";
    $bb_replace[] = "<table width=\"90%\" align=\"center\" class=\"quotetable\"><tr><td width=\"100%\" height=\"25\"><b>".$lang['Quote']."</b>&nbsp;&nbsp;Username: \\1</td></tr><tr><td>\\2</td></table>";

    // [code]Text[/code]
    $bb_search[] = "#\[code\]((\[(?!/code\])|(\n|.))*?)\[/code\]#is";
    $bb_replace[] = "<table width=\"90%\" align=\"center\" class=\"quotetable\"><tr><td width=\"100%\" height=\"25\"><b>".$lang['Code']."</b></td></tr><tr><td>\\1</td></table>";

    // [code=zzz]Text[/code]
    $bb_search[] = "#\[code=([\w\#$%&~/.\-;:=,?@\r\n \[\]\(\)\?+]*?)\]((\[(?!/code\])|(\n|.))*?)\[/code\]#is";
    $bb_replace[] = "<table width=\"90%\" align=\"center\" class=\"quotetable\"><tr><td width=\"100%\" height=\"25\"><b>".$lang['Code']."</b>&nbsp;&nbsp;Username: \\1</td></tr><tr><td>\\2</td></table>";

    $post = preg_replace($bb_search, $bb_replace, $post);
    $post = eregi_replace('', '<br>', $post);
    return $post;
}

//===========================================
// @Name: function shortentext();
// @Desc: Handles sub stringing descriptions
//===========================================
function shortentext($text, $length, $remove_bb = true)
{
    if($remove_bb)
    {
        $text = preg_replace("!\[(.*?)\]!is", "", $text);
    }
    if (strlen($text) > $length)
    {
        $text = substr($text,0,$length);
        $text = $text."...";
    }
    return $text;
}

//===========================================
// @Name: function email();
// @Desc: Handles emailing methods
//===========================================
function email($subject, $template, $tags = array(), $to, $from = '')
{
    global $config, $lang, $ldb_prefix, $user, $root_path;
    if(empty($from))
    {
        $from = $config['site_name']."\" <".$config['admin_email'].">";
    }
    $language = ($user['user_id'] > 0) ? $user['user_language_folder'] : $config['language_folder'];
    if(!file_exists($root_path . "language/$language/email/$template.tpl"))
    {
        $body = $template;
    }
    else
    {
        $template = file_get_contents($root_path . "language/$language/email/$template.tpl");
        if(count($tags) > 0)
        {
            foreach($tags as $tag => $data)
            {
                $template = preg_replace("/\{$tag\}/", $data, $template);
            }
        }
        $template = preg_replace("/\{EMAIL_SIG\}/", $lang['Thanks'] . "\n".$config['site_name'], $template);
        $body = $template;
    }
    if(!mail($to, $subject, $body, "From: ".$from.""))
    {
        error_msg($lang['Error'], $lang['Unable_To_Send_Email']);
    }
}

//===========================================
// @Name: function changehtml();
// @Desc: Handles HTML tag replacements
//===========================================
function changehtml($string)
{
    $string = ereg_replace("<", "&lt;", $string);
    $string = ereg_replace(">", "&gt;", $string);
    $string = ereg_replace(chr(34), "&quot;", $string);
    return $string;
}

//===========================================
// @Name: function userexists();
// @Desc: Performs a check on the given username
//===========================================
function userexists($username)
{
    global $db2, $db_prefix;
    $result = $db2->query("SELECT *
		FROM `".$db_prefix."users`
		WHERE `username` = :username",
		array(
			':username' => $username
		)
	);
    $result2 = $result->fetch();
    if($result2)
    {
        return "1";
    }
    else
    {
        return "0";
    }
}

//===========================================
// @Name: function generate_activation_key();
// @Desc: Builds an activation key for new users
//===========================================
function generate_activate_key($totalChar = 15)
{
	$salt = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
	srand(microtime()*1000000);
	$activate_key="";
	for ($i=0;$i<$totalChar;$i++)
    {
		$activate_key = $activate_key . substr ($salt, rand() % strlen($salt), 1);
	}
	return $activate_key;
}

//===========================================
// @Name: function fetch_make_options();
// @Desc: Builds the options for a select menu
//===========================================
function fetch_make_options($value, $text, $selval = '', $sel = 1)
{
	global $theme, $lang;
	$optsel = '';
	if($sel == 1)
	{
		if(is_array($selval))
		{
			if(in_array($value, $selval))
			{
				$optsel = " selected='selected'";
			}
		}
		elseif($selval == $value)
		{
			$optsel = " selected='selected'";
		}
	}
	return "<option value='".$value."' ".$optsel.">".$text."</option>";
}

//===========================================
// @Name: function fetch_months();
// @Desc: Fetchs and builds the array of months
//===========================================
function fetch_months($number)
{
	global $lang, $months, $theme;
	if(!isset($months))
	{
		$months = explode('-', $lang['profile_months']);
	}
	return $months[$number -1];
}

//===========================================
// @Name: function ifelse();
// @Desc: Performs a basic conditional method
//===========================================
function ifelse($condition, $true='', $false='')
{
	return ($condition ? $true : $false);
}

//===========================================
// @Name: function format_membername();
// @Desc: Handles membername formatting with rank
//===========================================
function format_membername($member_rank, $member_id, $member_name)
{
	global $config, $user, $theme, $lang, $db2, $db_prefix;

	$format = '';
	$rsql = $db2->query("SELECT *
		FROM `".$db_prefix."ranks`
		WHERE `rank_id` = :member_rank",
		array(
			':member_rank' => $member_rank
		)
	);
	while($rank = $rsql->fetch())
	{
		$format .= "<a href=\"profile.php?id=".$member_id."\"><span style=\"";
		if(!empty($rank['rank_color']))
		{
			$format .= " color: ".$rank['rank_color'].";";
		}
		if($rank['rank_bold'] == 1)
		{
			$format .= " font-weight: bold;";
		}
		else
		{
			$format .= " font-weight: normal;";
		}
		if($rank['rank_underline'] == 1)
		{
			$format .= " text-decoration: underline;";
		}
		else
		{
			$format .= " text-decoration: none;";
		}
		if($rank['rank_italics'] == 1)
		{
			$format .= " font-style: italic;";
		}
		else
		{
			$format .= " font-style: normal;";
		}
		$format .="\">" . $member_name . "</span></a>";
	}
	return $format;
}

//===========================================
// @Name: function load_forum_stats();
// @Desc: Loads the forums stats,online,etc.
//===========================================
function load_forum_stats()
{
	global $config, $user, $theme, $lang, $db2, $db_prefix;

	//Online Listing
	$onlinesql = $db2->query("SELECT u.* FROM `".$db_prefix."sessions` s LEFT JOIN `".$db_prefix."users` u ON (u.`user_id` = s.`user_id`)");
	$online_list = '';
	$users_online = 0;
	$guests_online = 0;
	while($result = $onlinesql->fetch())
	{
		if($result['user_id'] != -1)
		{
			$users_online++;
			$online_list .= format_membername($result['user_rank'],$result['user_id'],$result['username']).', ';
		}
		else
		{
				  $guests_online++;
		}
	}
	$total_online = $users_online + $guests_online;
	$length = strlen($online_list) - 2;
	$online_list = substr($online_list, 0, $length);
	if(strlen($online_list) < 1)
	{
			$online_list = "<strong>".$lang['None']."</strong>";
	}

	//Rank Listing
	$rank_list = '';
	$ranksql = $db2->query("SELECT `rank_name`, `rank_color`, `rank_bold`, `rank_underline`, `rank_italics`
						   FROM `".$db_prefix."ranks`
						   WHERE `rank_orderby` > '0'
						   ORDER BY `rank_orderby`"
	);
	while($result = $ranksql->fetch())
	{
		$rank_list .= "[ <span style=\"color: ".$result['rank_color'].";";

		if($result['rank_bold'] == 1)
		{
			$rank_list .= " font-weight: bold;";
		}

		if($result['rank_underline'] == 1)
		{
			$rank_list .= " text-decoration: underline;";
		}

		if($result['rank_italics'] == 1)
		{
			$rank_list .= " font-style: italic;";
		}

		$rank_list .= "\">".$result['rank_name']."</span> ]&nbsp;&nbsp;";
	}

	//Online today listing
	$todaylist = '';
	$online_today = '';
	$onlinetoday = 0;
	$stime = time()-(60*60*24);
    $todaysql = $db2->query("SELECT * FROM `".$db_prefix."users` WHERE `user_lastvisit` > $stime ORDER BY `user_lastvisit` DESC");
	while($result = $todaysql->fetch())
	{
		if($result['user_id'] != -1)
		{
			$onlinetoday++;
			$todaylist .= format_membername($result['user_rank'],$result['user_id'],$result['username']).', ';
		}
	}
	$length = strlen($todaylist) - 2;
	$todaylist = substr($todaylist, 0, $length);
	if(strlen($todaylist) < 1)
	{
			$todaylist = "<strong>".$lang['None']."</strong>";
	}
	$online_today = sprintf($lang['stats_online_today'], number_format($onlinetoday));

	//Forums Newest Member
	$newestmember = '';
	$newest_member = '';
	$newestsql = $db2->query("SELECT * FROM `".$db_prefix."users` ORDER BY `user_id` DESC LIMIT 1");
	$lastmember = $newestsql->fetch();
	if(!$lastmember['username'])
	{

		$newestmember = "<strong>".$lang['None']."</strong>";
	}
	else
	{
		$newestmember = format_membername($lastmember['user_rank'],$lastmember['user_id'],$lastmember['username']);
	}
	$newest_member = sprintf($lang['stats_newest_member'], $newestmember);

	//Forums total members
	$totalusers = '';
	$total_users = '';
	$usertotalsql = $db2->query("SELECT COUNT(*) AS 'total_members' FROM `".$db_prefix."users` WHERE `user_id` > 0");
	if($res = $usertotalsql->fetch())
	{
		$totalusers = $res['total_members'];
	}
	$total_users = sprintf($lang['stats_total_members'], number_format($totalusers));

	//Forums total posts/topics
	$totaltopics = '';
	$totalposts  = '';
	$total_topics_posts = '';
	$topictotalsql = $db2->query("SELECT COUNT(*) AS 'total_topics' FROM `".$db_prefix."topics`");
	if($result = $topictotalsql->fetch())
	{
		$totaltopics = $result['total_topics'];
	}
	$posttotalsql = $db2->query("SELECT COUNT(*) AS 'total_posts' FROM `".$db_prefix."posts`");
	if($result = $posttotalsql->fetch())
	{
		$totalposts = $result['total_posts'];
	}
	$total_topics_posts = sprintf($lang['stats_total_poststopics'], number_format($totalposts), number_format($totaltopics));

	//Forums Member Birthdays
	$bdaycount = 0;
	$theme->insert_nest("board_home", "forumstats_birthdays");
	$bdaytime = time();
	$currentdate = create_date('m-d', $bdaytime);
	$currentyear = intval(create_date('Y', $bdaytime));
	$birth = $db2->query("SELECT * FROM `".$db_prefix."users` WHERE `user_birthday` LIKE '%-$currentdate' ORDER BY `username` ASC");
	$bdaybit = '';
	$sep = '';
	while($bday = $db2->fetch())
	{
		$birthyear = intval(substr($bday['user_birthday'], 0, 4));
		$age = $currentyear - $birthyear;
		if($age < 1 || $age > 200)
		{
			$age = '';
		}
		else
		{
			$age = '&nbsp;( '.$age.' )';
		}
		$bdaybit .= $sep.format_membername($bday['user_rank'],$bday['user_id'],$bday['username']).$age;
		$bdaycount++;
		$sep = ', ';
	}
	if($bdaycount > 0)
	{
		$theme->add_nest("board_home", "forumstats_birthdays");
	}

	$theme->replace_tags("board_home", array(
		"TOTAL_ONLINE" => strval($total_online),
		"USERS_ONLINE" => strval($users_online),
		"GUESTS_ONLINE" => strval($guests_online),
		"TOTAL_POSTS" => strval($totalposts),
		"TOTAL_TOPICS" => strval($totaltopics),
		"ONLINE_LIST" => $online_list,
		"NEWEST_MEMBER" => $newest_member,
		"TOTAL_TOPICS_POSTS" => $total_topics_posts,
		"TOTAL_USERS" => $total_users,
		"RANKS_LIST" => $rank_list,
		"TODAY_TOTAL" => $online_today,
		"ONLINE_TODAY" => $todaylist,
		"BDAY_LIST" => $bdaybit,
	));
}

/*======================================================================*\
|| #################################################################### ||
|| #                 "Copyright © 2006 M-ka Network"                  # ||
|| #################################################################### ||
\*=====================================================================
