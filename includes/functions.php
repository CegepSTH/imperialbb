<?php
if(!defined("IN_IBB")) {
	die("Hacking Attempt");
}

//===========================================
// @Name: function confirm_msg();
// @Desc: Handles confirmations with redirect
//===========================================
function confirm_msg($name, $message, $url, $no_url = '', $values = '')
{
	global $config, $user, $lang, $page_gen_start, $db, $root_path;
	$tplConfirmMsg = new Template("confirm_msg.tpl");

	$tplConfirmMsg->setVars(array(
		"TITLE"     => $name,
		"MESSAGE"   => $message,
		"URL"       => $url,
		"NO_URL"    => (strlen($no_url) > 0) ? $no_url : "index.php",
		"CSRF_TOKEN" => CSRF::getHTML()
	));
	
	if(is_array($values)) {
		foreach($values as $name => $value) {
			$tplConfirmMsg->addToBlock("hidden_row", array(
				"NAME"  => $name,
				"VALUE" => $value
			));
		}
	}
	
	outputPage($tplConfirmMsg);
	exit();
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
	
	if(!$config['censor_enabled'] || !$config['censor_words']) {
		return $text;
	}
	
	$censored_words = split(" ",$config['censor_words']);
	foreach($censored_words as $index => $word) {
		$length = strlen($word);
        $replace = '';
        
		for($x = 1; $x <= $length; $x++) {
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
	global $db2, $root_path, $lang;

	if($remove_html) {
		$text = preg_replace("#<#", "&lt;", $text);
		$text = preg_replace("#>#", "&gt;", $text);
		$text = preg_replace("#\"#", "&quot;", $text);
	}

	$text = str_replace("\r\n", "<br>", $text);
	$text = str_replace("\n", "<br>", $text);

	if($insert_smilies) {
		$db2->query("SELECT `smilie_code`, `smilie_url` FROM `_PREFIX_smilies`");
		
		while ($row = $db2->fetch()) {
			$text = str_replace($row['smilie_code'],"<img src=\"" . $root_path . "images/smilies/".$row['smilie_url']."\" border=\"0\">", $text);
		}
	}

    if($censor_text) {
        $text = ibb_censor($text);
    }

	if($insert_bbcode) {
		$text = bbcode($text);
	}
	
	return $text;
}

//===========================================
// @Name: function insertsmilies();
// @Desc: Handles forum Smilie methods
//===========================================
function insertsmilies($post)
{
    global $db2, $root_path;
    
    $db2->query("SELECT `smilie_code`, `smilie_url` FROM `_PREFIX_smilies`");
    while ($row = $db2->fetch()) {
        $post = str_replace($row['smilie_code'],"<img src=\"" . $root_path . "images/smilies/".$row['smilie_url']."\" border=\"0\">",$post);
    }
    
    return $post;
}

function bbcode_fixed_replace($op, $end, array &$matches, $post) {
	foreach($matches as $key => $value) {
		$curr_value = substr($value, strpos($value, ":")+1, -1);
		
		if(intval($curr_value) < 0) {
			$post = str_replace($value, substr($value, 0, strpos($value, ":"))."]", $post);
			break;
		}
		
		$needle = "[". (strpos($value, "/") === false ? "/" : "") .substr($value, 1, strpos($value, ":")).$curr_value."]";
		
		if(in_array($needle, $matches)) {
			$post = str_replace($value, $op, $post);
			$post = str_replace($needle, $end, $post);
		} 
	}
	
	return $post;
}

function unbbcode($post)
{	
	global $lang;

	$matches = array();
	$post = str_replace("[code]", "\r\n------\r\nCode:\r\n", $post);
	$post = str_replace("[/code]", "\r\n-------\r\n", $post);
	
	$post = str_replace("[b]", "", $post);
	$post = str_replace("[/b]", "", $post);
	
	$post = str_replace("[i]", "", $post);
	$post = str_replace("[/i]", "", $post);
	
	$post = str_replace("[u]", "", $post);
	$post = str_replace("[/u]", "", $post);
	
	$post = str_replace("[hr]", "\r\n----------\r\n", $post);
	
	//[img]
	$post = preg_replace("#\[img\](.+://)((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\[/img\]#s",
		"", $post);

	// [url=]
	$post = preg_replace("#\[url=(([\w]+?://)?[\#$\w%~/&.\-;:=,?@+]*?)\]([^?\n\r\t].*?)\[/url\]#s", 
		"\\1", $post);
	
    // [url=www.domain.com]Domain[/url] (Without xxxx:// prefix)
    $post = preg_replace("#\[url=([\w\#$%&~/.\-;:=,?@+]*?)\]([^?\n\r\t].*?)\[/url\]#is", 
		"\\1\"", $post);

	$post = preg_replace("#\[url\](.+://)((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\[/url\]#is",
		"\\1\\2", $post);

    // [url]www.domain.com[/url] (Without xxxx:// prefix)
    $post = preg_replace("#\[url\]((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\[/url\]#is", "\\1", $post);

    // [color=xxx]Text[/color]
    $post = preg_replace("#\[color=([a-zA-Z]+|\#([0-9a-fA-F]{3}|[0-9a-fA-F]{6}))\]((\[(?!/color\])|(\n|.))*?)\[/color\]#s", 
		"\\3", $post);

    // [size=xxx]Text[/size]
    $post = preg_replace("#\[size=([1-4](\.5)?)\]((\[(?!/size\])|(\n|.))*?)\[/size\]#s", 
		"\\3", $post);

    // [align=xxx]Text[/align]
    $post = preg_replace("#\[align=(left|center|right)\]((\[(?!/align\])|(\n|.))*?)\[/align\]#s", 
		"\\2", $post);
	$post = preg_replace("#\[quote=(.*?)\]#", "\r\n------\r\nQuote:\r\n", $post);
	$post = str_replace("[quote]", "\r\n------\r\nQuote:\r\n", $post);
	$post = str_replace("[/quote]", "\r\n------\r\n", $post);

    return $post;
}

//===========================================
// @Name: function bbcode();
// @Desc: Handles BBCode methods
//===========================================
function bbcode($post)
{	
	global $lang;
	$matches = array();

	// Code
	$matches = array();
	$post = match_nested_tags($post, "[code]", "[/code]", $matches);
	
	$subs = array();
	preg_match_all("#\[code:0\](.*?)\[\/code:0\]#s", $post, $subs);
	
	// Anti-parse code muahauhau
	foreach($subs as $key => $value) {
		if(empty($value)) {
			continue;
		}
		
		$filtered = str_replace("[", "&#91;", $value[0]);
		$filtered = str_replace("]", "&#93;", $filtered);
		$post = str_replace("[code:0]".$value[0]."[/code:0]", 
			"<div class=\"quotetable\"><strong>".$lang['Code']."</strong><br><div style=\"font-family: monospace, serif;margin-left:1.25%;\">".$filtered."</div></div>",
			$post);
	}
	// Replace all occurences like [code:-1] :^)
	$post = preg_replace("#&\#91;code:-?[1-9]\&\#93;#", "[code]", $post);
	$post = preg_replace("#&\#91;\/code:-?[1-9]\&\#93;#", "[/code]", $post);

	// Bold tag. 
	$post = match_nested_tags($post, "[b]", "[/b]", $matches);
	$post = bbcode_fixed_replace("<strong>", "</strong>", $matches, $post);
		
	// Italic tag.
	$matches = array();
	$post = match_nested_tags($post, "[i]", "[/i]", $matches);
	$post = bbcode_fixed_replace("<em>", "</em>", $matches, $post);

	// Underline tag.
	$matches = array();
	$post = match_nested_tags($post, "[u]", "[/u]", $matches);
	$post = bbcode_fixed_replace("<span style=\"text-decoration:underline;\">", "</span>", $matches, $post);

	// HR
	$post = str_replace("[hr]", "<hr>", $post);
		
	//[img]
	$post = preg_replace("#\[img\](.+://)((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\[/img\]#s",
		"<img src=\"$1$2\">", $post);

	// [url=]
	$post = preg_replace("#\[url=(([\w]+?://)?[\#$\w%~/&.\-;:=,?@+]*?)\]([^?\n\r\t].*?)\[/url\]#s", 
		"<a href=\"\\1\" target=\"blank\">\\3</a>", $post);
	
    // [url=www.domain.com]Domain[/url] (Without xxxx:// prefix)
    $post = preg_replace("#\[url=([\w\#$%&~/.\-;:=,?@+]*?)\]([^?\n\r\t].*?)\[/url\]#is", 
		"<a href=\"http://\\1\" target=\"blank\">\\2</a>", $post);

	$post = preg_replace("#\[url\](.+://)((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\[/url\]#is",
		"<a href=\"\\1\\2\" target=\"blank\">\\2</a>", $post);

    // [url]www.domain.com[/url] (Without xxxx:// prefix)
    $post = preg_replace("#\[url\]((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\[/url\]#is", "<a href=\"http://$1\" target=\"blank\">$1</a>", $post);

    // [color=xxx]Text[/color]
    $post = preg_replace("#\[color=([a-zA-Z]+|\#([0-9a-fA-F]{3}|[0-9a-fA-F]{6}))\]((\[(?!/color\])|(\n|.))*?)\[/color\]#s", 
		"<span style=\"color:$1;\">$3</span>", $post);

    // [size=xxx]Text[/size]
    $post = preg_replace("#\[size=([1-4](\.5)?)\]((\[(?!/size\])|(\n|.))*?)\[/size\]#s", 
		"<span style=\"font-size:$1em;\">$3</span>", $post);

    // [align=xxx]Text[/align]
    $post = preg_replace("#\[align=(left|center|right)\]((\[(?!/align\])|(\n|.))*?)\[/align\]#s", 
		"<span style=\"display: block;text-align:$1;\">$2</span>", $post);

	// Quotes
	$matches = array();
	$post = match_nested_tags($post, "[quote]", "[/quote]", $matches);
	$post = bbcode_fixed_replace("<div class=\"quotetable\"><strong>".$lang['Quote']."</strong><br><div style=\"margin-left:1.25%;\">", 
		"</div></div>", $matches, $post);
	
    return $post;
}

//===========================================
// @Name: function shortentext();
// @Desc: Handles sub stringing descriptions
//===========================================
function shortentext($text, $length, $remove_bb = true)
{
    if($remove_bb) {
        $text = preg_replace("!\[(.*?)\]!is", "", $text);
    }
    
    if (strlen($text) > $length) {
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
    global $config, $lang, $user, $root_path;
    
    if(empty($from)) {
        $from = $config['site_name']."\" <".$config['admin_email'].">";
    }
    
    $language = ($user['user_id'] > 0) ? $user['user_language_folder'] : $config['language_folder'];
    
    if(!file_exists($root_path . "language/$language/email/$template.tpl")) {
        $body = $template;
    } else {
        $template = file_get_contents($root_path . "language/$language/email/$template.tpl");
        
        if(count($tags) > 0) {
            foreach($tags as $tag => $data) {
                $template = preg_replace("/\{$tag\}/", $data, $template);
            }
        }
        $template = preg_replace("/\{EMAIL_SIG\}/", $lang['Thanks'] . "\n".$config['site_name'], $template);
        $body = $template;
    }
    
    if(!mail($to, $subject, $body, "From: ".$from."")) {
		showMessage(ERR_CODE_UNABLE_SEND_MAIL);
    }
}

//===========================================
// @Name: function emailexists();
// @Desc: Performs a check on the given email address
//===========================================
function emailexists($emailaddress)
{
	global $db2;
	$result = $db2->query("SELECT *
		FROM `_PREFIX_users`
		WHERE `user_email` = :emailaddress
		LIMIT 1",
		array(':emailaddress' => $emailaddress ));

	$result2 = $result->fetch();
	if($result2) {
		return "1";
	} else {
		return "Hiiii";
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
	
	for ($i = 0; $i < $totalChar; $i++) {
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
	global $lang;
	
	$optsel = '';
	if($sel == 1) {
		if(is_array($selval)) {
			if(in_array($value, $selval)) {
				$optsel = " selected='selected'";
			}
		} elseif($selval == $value) {
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
	global $lang, $months;
	
	if(!isset($months)) {
		$months = explode('-', $lang['profile_months']);
	}
	
	return $months[$number -1];
}

//===========================================
// @Name: function format_membername();
// @Desc: Handles membername formatting with rank
//===========================================
function format_membername($member_rank, $member_id, $member_name)
{
	global $config, $user, $lang, $db2;
	
	$format = '';
	
	if(!isset($ranks) || empty($ranks)) {
		static $ranks = null;
		
		$db2->query("SELECT *
			FROM `_PREFIX_ranks`
			WHERE `rank_id` = :member_rank",
			array(':member_rank' => $member_rank));
			
		$ranks = $db2->fetchAll();
	}
	
	$format .= "<a href=\"profile.php?id=".$member_id."\"><span style=\"";
	
	foreach($ranks as $rank) {
		if(!empty($rank['rank_color'])) {
			$format .= " color: ".$rank['rank_color'].";";
		} 
		
		if($rank['rank_bold'] == 1) {
			$format .= " font-weight: bold;";
		} else {
			$format .= " font-weight: normal;";
		}
		
		if($rank['rank_underline'] == 1) {
			$format .= " text-decoration: underline;";
		} else {
			$format .= " text-decoration: none;";
		}
		
		if($rank['rank_italics'] == 1) {
			$format .= " font-style: italic;";
		} else {
			$format .= " font-style: normal;";
		}
	}
	
	$format .="\">" . $member_name . "</span></a>";
	
	return $format;
}

//===========================================
// @Name: function load_forum_stats();
// @Desc: Loads the forums stats,online,etc.
//===========================================
function load_forum_stats($page_master)
{
	global $config, $user, $lang, $db2;

	// A session is considered online if the last action time is in the
	// last 30 minutes.
	$online_time_limit = time() - 30 * 60;

	//Online Listing
	$sql = $db2->query("SELECT u.*
		FROM `_PREFIX_users` AS u
		WHERE u.`user_id` IN (
			SELECT DISTINCT s.`user_id`
			FROM `_PREFIX_sessions` AS s
			WHERE s.`time` > :time_limit
		);",
		array(
			":time_limit" => $online_time_limit
		)
	);
	$online_list = '';
	$users_online = 0;
	
	while($result = $sql->fetch()) {
		if($result['user_id'] != -1) {
			$users_online++;
			$online_list .= format_membername($result['user_rank'],$result['user_id'],$result['username']).', ';
		}
	}

	$guests_online = 0;
	$db2->query("SELECT COUNT(*) AS `guest_count`
			FROM `_PREFIX_sessions` AS s
			WHERE s.`time` > :time_limit AND s.`user_id` = -1;",
		array(
			":time_limit" => $online_time_limit
		)
	);
	$result = $db2->fetch();
	$guests_online = $result["guest_count"];
	
	$total_online = $users_online + $guests_online;
	$length = strlen($online_list) - 2;
	$online_list = substr($online_list, 0, $length);
	
	if(strlen($online_list) < 1) {
			$online_list = "<strong>".$lang['None']."</strong>";
	}

	//Rank Listing
	$rank_list = '';
	$db2->query("SELECT `rank_name`, `rank_color`, `rank_bold`, `rank_underline`, `rank_italics`
						   FROM `_PREFIX_ranks`
						   WHERE `rank_orderby` > '0'
						   ORDER BY `rank_orderby`"
	);
	
	while($result = $db2->fetch()) {
		$rank_list .= "[ <span style=\"color: ".$result['rank_color'].";";

		if($result['rank_bold'] == 1) {
			$rank_list .= " font-weight: bold;";
		}

		if($result['rank_underline'] == 1) {
			$rank_list .= " text-decoration: underline;";
		}

		if($result['rank_italics'] == 1) {
			$rank_list .= " font-style: italic;";
		}

		$rank_list .= "\">".$result['rank_name']."</span> ]&nbsp;&nbsp;";
	}

	//Online today listing
	$todaylist = '';
	$online_today = '';
	$onlinetoday = 0;
	$stime = time()-(60*60*24);
    $sql = $db2->query("SELECT * FROM `_PREFIX_users` WHERE `user_lastvisit` > $stime ORDER BY `user_lastvisit` DESC");
    
	while($result = $sql->fetch()) {
		if($result['user_id'] != -1) {
			$onlinetoday++;
			$todaylist .= format_membername($result['user_rank'],$result['user_id'],$result['username']).', ';
		}
	}
	$length = strlen($todaylist) - 2;
	$todaylist = substr($todaylist, 0, $length);
	
	if(strlen($todaylist) < 1) {
			$todaylist = "<strong>".$lang['None']."</strong>";
	}
	
	$online_today = sprintf($lang['stats_online_today'], number_format($onlinetoday));

	//Forums Newest Member
	$newestmember = '';
	$newest_member = '';
	$db2->query("SELECT * FROM `_PREFIX_users` ORDER BY `user_id` DESC LIMIT 1");
	$lastmember = $db2->fetch();
	
	if(!$lastmember['username']) {
		$newestmember = "<strong>".$lang['None']."</strong>";
	} else {
		$newestmember = format_membername($lastmember['user_rank'],$lastmember['user_id'],$lastmember['username']);
	}
	
	$newest_member = sprintf($lang['stats_newest_member'], $newestmember);

	//Forums total members
	$totalusers = '';
	$total_users = '';
	$db2->query("SELECT COUNT(*) AS 'total_members' FROM `_PREFIX_users` WHERE `user_id` > 0");
	
	if($res = $db2->fetch()) {
		$totalusers = $res['total_members'];
	}
	$total_users = sprintf($lang['stats_total_members'], number_format($totalusers));

	//Forums total posts/topics
	$totaltopics = '';
	$totalposts  = '';
	$total_topics_posts = '';
	$db2->query("SELECT COUNT(*) AS 'total_topics' FROM `_PREFIX_topics`");
	
	if($result = $db2->fetch()) {
		$totaltopics = $result['total_topics'];
	}
	
	$db2->query("SELECT COUNT(*) AS 'total_posts' FROM `_PREFIX_posts`");
	
	if($result = $db2->fetch()) {
		$totalposts = $result['total_posts'];
	}
	
	$total_topics_posts = sprintf($lang['stats_total_poststopics'], number_format($totalposts), number_format($totaltopics));

	//Forums Member Birthdays
	$bdaycount = 0;
	$bdaytime = time();
	$currentdate = create_date('m-d', $bdaytime);
	$currentyear = intval(create_date('Y', $bdaytime));
	$db2->query("SELECT * FROM `_PREFIX_users` WHERE `user_birthday` LIKE '%-$currentdate' ORDER BY `username` ASC");
	$bdaybit = '';
	$sep = '';
	
	while($bday = $db2->fetch()) {
		$birthyear = intval(substr($bday['user_birthday'], 0, 4));
		$age = $currentyear - $birthyear;
		
		if($age < 1 || $age > 200) {
			$age = '';
		} else {
			$age = '&nbsp;( '.$age.' )';
		}
		
		$bdaybit .= $sep.format_membername($bday['user_rank'],$bday['user_id'],$bday['username']).$age;
		$bdaycount++;
		$sep = ', ';
	}
	
	if($bdaycount > 0) {
		$page_master->addToBlock("forumstats_birthdays", array(
			"BDAY_LIST" => $bdaybit
		));
	}

	$page_master->setVars(array(
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
		"ONLINE_TODAY" => $todaylist
	));
}

function before($inthat, $this) {
	$arr = explode($this, $inthat, 2);
	$first = $arr[0];
	
	return $first; 
}

function str_replace_once($needle, $replace, $haystack) {
    $pos = strpos($haystack, $needle);
    if ($pos === false) {
        return $haystack;
    }
    return substr_replace($haystack, $replace, $pos, strlen($needle));
}

function countWord($needle, $haystack) {
	$count = 0;
	$c = str_replace($needle, "", $haystack, $count);
	return $count;
}

/**
 * Match nested tags. 
 * 
 * Example: [quote] [quote] [/quote] [/quote]
 * 		With a normal parser would return a match like
 * 		[quote:0][quote:1] [/quote:0] [/quote:1] 
 * 		With a more complex structure: 
 * 		[quote:0] [quote:1] [/quote:0] [/quote:1] [quote:3] [/quote:3]
 * 
 * However, this functions fixes the behavior and results:
 * 		[quote:0] [quote:1] [/quote:1] [/quote:0] [quote:0] [quote:1][/quote:1] [/quote:0]
 * 
 * 
 * @param $code Full code as string to parse.
 * @param $opening Opening tag.
 */
function match_nested_tags($code, $opening, $end, 
	array &$matches = array(), $delimS = "[", $delimE = "]") 
{ 
    $ix = 0; 
    $iy = 0; 
    $nbr_op = countWord($opening, $code);

    while($ix < $nbr_op) { 
        if(countWord($opening, before($code, $end)) > 0) {
            // The following piece of code replace the default [tag] by [tag:#] 
            $rep = $delimS . substr($opening, 1, (strlen($opening)-2)).":".$ix.$delimE;
            $matches[] = $rep;
            $code = str_replace_once($opening, $rep, $code); 
            $iy++; 
        } elseif(countWord($end, before($code, $opening)) > 0)  { 
            $iy = $iy-1; 
            $rep = $delimS . substr($end, 1, (strlen($end)-2)).":".($ix-1).$delimE;
            $matches[] = $rep;
            $code = str_replace_once($end, $rep, $code); 
            $ix = $ix-2; 
        } 

        $ix++; 
    } 

    while(strpos($code, $end) !== false) { 
		$rep = $delimS . substr($end, 1, (strlen($end)-2)).":".($iy-1).$delimE;
		$matches[] = $rep;
		$code = str_replace_once($end, $rep, $code); 
        $iy=$iy-1; 
    } 
    
	$pat = substr($end, 0, -1);

	// Replace negatives [/code:-1]
    $code = preg_replace("#\\$delimS$pat:-[0-9]\\$delimE#", "", $code); 

    return $code; 
} 

function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

function parseBirthday($str_birthday) {
	$birthday = explode("-", $str_birthday);
	$day = $birthday[2];
	$month = $birthday[1];
	$year = $birthday[0] ?: '';
	$res = array("day" => $day, "month" => $month, "year" => $year);
	return $res;
}

function showMessage($err_code, $str_returnUrl = "index.php") {
	$_SESSION['return_url'] = $str_returnUrl;
	header("location: message.php?code=".$err_code);
	exit();
}

?>
